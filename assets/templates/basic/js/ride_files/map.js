(function($, window) {
    'use strict';
    if (!window.appConfig) {
        console.error('appConfig missing');
        return;
    }

    const {
        defaultMap,
        routes            : { calculateFare, createRide, suggestedPlaces, placeDetails, locationByLatLng },
        paymentStatusCodes: { CASH, GATEWAY },
        assets            : { locationIcon },
        notifier          : {  warning },
        loader            : { showTypingLoader, locationLoader, carRunningAnimation },
        modalAction       : { showNotifyModal },
        generalSetting    : { operatingCountry },
        csrfToken,
        runningRide
    } = window.appConfig;

    const canRunLocationBasedScripts = () =>
        window.apiComponentRestriction !== undefined &&
        window.userCurrentCity !== undefined &&
        window.userCurrentLatitude !== undefined &&
        window.userCurrentLongitude !== undefined;

    let selectedPickup          = null;
    let selectedDestination     = null;
    let activeRequest           = null;
    let serviceData             = [];
    let map                     = null;
    let adminCountryRestriction = operatingCountry ? operatingCountry.split(',').map(code => 'country:' + code.trim().toLowerCase()).join('|') : '';
    let pickupMarker, destinationMarker, directionsService, directionsRenderer, routeLine;

    /*---------------------------------------------------------------------------------
    * Google Places
    *---------------------------------------------------------------------------------- */

    const routeMarker = (pickup, destination) => {
        pickupMarker?.setMap(null);
        destinationMarker?.setMap(null); 

        pickupMarker = new google.maps.Marker({ 
            map, 
            position: pickup,
            label: "Pickup",
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png'
            } 
        });

        destinationMarker = new google.maps.Marker({ 
            map, 
            position: destination,
            label : "Destination",  
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png'
            } 
        });
    };

    const focusSingleLocation = (location, type = 'pickup') => {
        if (!location || !map) {
            return;
        }

        if (window.manualRouteLine) {
            window.manualRouteLine.setMap(null);
        }

        if (window.routeInfoWindow) {
            window.routeInfoWindow.close();
        }

        if (type === 'pickup' && pickupMarker) {
            pickupMarker.setMap(null);
        }

        if (type === 'destination' && destinationMarker) {
            destinationMarker.setMap(null);
        }

        map.panTo(location);

        setTimeout(() => {
            map.setZoom(16);
        }, 1000);

        const marker = new google.maps.Marker({
            map,
            position: location,
            icon: {
                url: type === 'pickup'
                    ? 'https://maps.google.com/mapfiles/ms/icons/green-dot.png'
                    : 'https://maps.google.com/mapfiles/ms/icons/red-dot.png'
            }
        });

        if (type === 'pickup') {
            pickupMarker = marker;
        } else {
            destinationMarker = marker;
        }

        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div style="
                    padding: 8px 12px;
                    background: #7c4dff;
                    color: #fff;
                    font-weight: 600;
                    border-radius: 8px;
                    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
                    font-size: 14px;
                    text-align: center;
                ">
                    ${type === 'pickup' ? 'Pickup Location' : 'Destination Location'}
                </div>
            `,
            disableAutoPan: false,
            pixelOffset: new google.maps.Size(0, -30),
            zIndex: 9999,
        });

        infoWindow.open(map, marker);

    };

   
    const getInputValue = (selector) => {
        const val = $(selector).val();
        
        if (!val || val.trim() === "") {
            return null;
        }

        const parsed = parseFloat(val);

        return !isNaN(parsed) ? parsed : null;
    };

    let requestedPickupLat       = getInputValue('input[name="pickup_latitude"]');
    let requestedPickupLng       = getInputValue('input[name="pickup_longitude"]');
    let requestedDestinationLat  = getInputValue('input[name="destination_latitude"]');
    let requestedDestinationLng  = getInputValue('input[name="destination_longitude"]');


    if (requestedPickupLat !== null && requestedPickupLng !== null && requestedDestinationLat !== null && requestedDestinationLng !== null) {
        setTimeout(() => {
            selectedPickup      = { lat: requestedPickupLat, lng: requestedPickupLng };
            selectedDestination = { lat: requestedDestinationLat, lng: requestedDestinationLng };
            tryDrawRoute();
        }, 1000);
    } else if (requestedPickupLat !== null && requestedPickupLng !== null) {
        setTimeout(() => {
            focusSingleLocation({ 
                lat: requestedPickupLat,
                lng: requestedPickupLng 
            }, 'pickup');
        }, 1000);
    } else if (requestedDestinationLat !== null && requestedDestinationLng !== null) {
        setTimeout(() => {
            focusSingleLocation({ 
                lat: requestedDestinationLat,
                lng: requestedDestinationLng
            }, 'destination');
        }, 1000);
    }

    const drawRoute = (pickup, destination) => {
        if (!window.directionsService) {
            window.directionsService = new google.maps.DirectionsService();
        }

        if (!window.directionsRenderer) {
            window.directionsRenderer = new google.maps.DirectionsRenderer({
                suppressMarkers: true
            });

            window.directionsRenderer.setMap(map);
        }

        const request = {
            origin: pickup,
            destination: destination,
            travelMode: google.maps.TravelMode.DRIVING
        };

        window.directionsService.route(request, function(result, status) {
            if (status === 'OK') {
                window.directionsRenderer.setDirections(result);

                const route = result.routes[0];
                const bounds = new google.maps.LatLngBounds();
                route.overview_path.forEach(latLng => bounds.extend(latLng));
                map.fitBounds(bounds);

                if (window.manualRouteLine) {
                    window.manualRouteLine.setMap(null);
                }

                window.manualRouteLine = new google.maps.Polyline({
                    path: route.overview_path,
                    geodesic: true,
                    strokeColor: "#7c4dff",
                    strokeOpacity: 0.9,
                    strokeWeight: 5,
                    map: map
                });

                const leg = route.legs[0];
                const distance = leg.distance.text;
                const duration = leg.duration.text;
                const midpoint = route.overview_path.length ? route.overview_path[Math.floor(route.overview_path.length / 2)] : pickup;

                const infoContent = `
                    <div class="location-distance-box">
                        <p><strong>Distance:</strong> ${distance}</p>
                        <p><strong>Duration:</strong> ${duration}</p>
                    </div>
                `;

                if (window.routeInfoWindow) window.routeInfoWindow.close();

                window.routeInfoWindow = new google.maps.InfoWindow({
                    content: infoContent,
                    position: midpoint
                });

                window.routeInfoWindow.open(map);
            } else {
                showNotifyModal('error', 'Directions request failed due to: ' + status);

                if (routeLine) routeLine.setMap(null);

                routeLine = new google.maps.Polyline({
                    path: [pickup, destination],
                    geodesic: true,
                    strokeColor: "#ff0000",
                    strokeOpacity: 0.7,
                    strokeWeight: 3,
                    map: map,
                });
            }
        });
    };


    const calculateFareOfDistance = (pickup, destination) => {

        if (isNaN(pickup.lat) || isNaN(pickup.lng)) {
            return showNotifyModal('error', 'Invalid pickup coordinates');
        }

        if (isNaN(destination.lat) || isNaN(destination.lng)) {
            return showNotifyModal('error', 'Invalid destination coordinates');
        }

        const $serviceDiv  = $('.serviceDiv');
        const previousHtml = $serviceDiv.html();

        $serviceDiv.html(showTypingLoader('Calculating fare'));

        const payload = {
            pickup_latitude      : pickup.lat,
            pickup_longitude     : pickup.lng,
            destination_latitude : destination.lat,
            destination_longitude: destination.lng
        };

        $.get(calculateFare, payload, null, 'json')
            .done(({ status, message, data } = {}) => {

                if (status == 'success') {
                    $serviceDiv.html(data.html);
                    serviceData = data.data || [];
                    $('[name=pickup_latitude]').val(pickup.lat);
                    $('[name=pickup_longitude]').val(pickup.lng);
                    $('[name=destination_latitude]').val(destination.lat);
                    $('[name=destination_longitude]').val(destination.lng);
                } else {
                    showNotifyModal('error', message);
                    $serviceDiv.html(previousHtml);
                }

            }).fail((e) => {
                showNotifyModal('error', 'Fare calculation failed');
                $serviceDiv.html(previousHtml);
            });
    };

    const tryDrawRoute = () => {

        if($(".pickup-form").length && selectedDestination && selectedPickup){
            $(".pickup-form").find('button[type="submit"]').attr('disabled', false).removeClass('disabled');
        }
        

        if (!$('#map').length) {
            return;
        }

        if (!selectedPickup && !selectedDestination) {
            return;
        }

        if (selectedPickup && !selectedDestination) {
            setTimeout(() => {
                focusSingleLocation(selectedPickup, 'pickup');
            }, 500);

            return;
        }

        if (!selectedPickup && selectedDestination) {
            setTimeout(() => {
                focusSingleLocation(selectedDestination, 'destination');
            }, 500);

            return;
        }

      

        pickupMarker?.setMap(null);
        destinationMarker?.setMap(null);

        

        routeMarker(selectedPickup, selectedDestination);
        drawRoute(selectedPickup, selectedDestination);
        calculateFareOfDistance(selectedPickup, selectedDestination);
    };

    const toggleButtonSpinner = ($icon, show = true) => {
        const $input       = $icon.closest('.input-group-custom').find('.input-group-custom__input');
        const spinnerColor = $icon.hasClass('text--base') ? 'text--base' : 'text--white';
        
        if (show) {
            if (!$icon.data('original-html')) {
                $icon.data('original-html', $icon.html());
            }

            $input.prop('disabled', true);
            $icon.addClass('disabled').html(`<span class="spinner-border spinner-border-sm ${spinnerColor}" role="status"></span>`);

        } else {
            $input.prop('disabled', false);
            $icon.removeClass('disabled').html($icon.data('original-html'));
        }
    };

    const setPickupLocation = () => {
        const $form = $('.findDriverForm');
        let   lat   = Number($form.find('#pickup_latitude').val());
        let   lng   = Number($form.find('#pickup_longitude').val());

        if (lat && lng) {
            selectedPickup = { lat, lng };
        }
    };

    
    const fillUpLocation = (response, address = null, params, type) => {
        setPickupLocation();

        if (!response?.location) {
            return;
        }

        const lat = typeof response.location.lat == 'function' ? response.location.lat() : response.location.lat;
        const lng = typeof response.location.lng == 'function' ? response.location.lng() : response.location.lng;

        if (lat == null || lng == null) {
            return;
        }

        let location = address ? address : (response.formatted_address ?? '');

        $(params.latInput).val(lat);
        $(params.lngInput).val(lng);
        $(params.input).val(location);

        if (params.locationInput) {
            $(params.locationInput).val(location);
        }

        if (type === 'pickup') {
            selectedPickup = { lat, lng };
            $('[name=pickup_location]').val(location);
        } else {
            selectedDestination = { lat, lng };
            $('[name=destination_location]').val(location);
        }
    };

    const fetchPlaceSuggestions = (input, callback, dropdownSelector) => {
        if (!input) {
            return callback([]);
        }
       
        const $dropdown = $(dropdownSelector);
        $dropdown.empty().append(locationLoader()).show();

        if (activeRequest && activeRequest.readyState !== 4) {
            activeRequest.abort();
        }

        activeRequest = $.get(suggestedPlaces, {
            input     : input,
            components: adminCountryRestriction
        }).done(res => {
            if (res.status === 'success') {
                callback(res.data?.predictions || []);
            } else {
                callback([]);
            }
        }).fail((jqXHR, textStatus) => {
            if (textStatus != 'abort') {
                callback([]);
            }
        }).always(() => {
            $dropdown.find('li.loading').remove();
            activeRequest = null;
        });
    };

    const fetchPlaceDetails = (placeId, callback) => {
        if (!placeId) {
            return callback([]);
        }

        $.get(placeDetails, {
            place_id : placeId
        }).done(res => {
            if (res.status === 'success') {
                callback(res.data || null);
            } else {
                warning(res.message || 'Failed to fetch place details');
                callback(null);
            }
        }).fail(() => callback(null));
    };

    /* ------------------------------------------------------------------
     * Autocomplete
     * ------------------------------------------------------------------ */

    const createDropDownItem = ({ placeId = null, description = '', isNoResults = false }) => {
        if (isNoResults) {
            return $(`
                <li class="search-input-dropdown__item search-input-dropdown__no-results">
                    ${description}
                </li>
            `);
        }

        return $(`
            <li class="search-input-dropdown__item" data-place-id="${placeId}" style="cursor:pointer;">
                <span class="location__icon">
                    <img src="${locationIcon}" alt="location icon">
                </span>
                <span class="location-name">${description}</span>
            </li>
        `);
    };

    const handleLocationClear = (type) => {
        if (type === 'pickup') {
            $('[name=pickup_location]').val('');
            $('#pickup_latitude').val('');
            $('#pickup_longitude').val('');
            selectedPickup = null;

            if (pickupMarker) {
                pickupMarker.setMap(null);
                pickupMarker = null;
            }
        } else if (type === 'destination') {
            $('[name=destination_location]').val('');
            $('#destination_latitude').val('');
            $('#destination_longitude').val('');
            selectedDestination = null;

            if (destinationMarker) {
                destinationMarker.setMap(null);
                destinationMarker = null;
            }
        }

        if (window.manualRouteLine) {
            window.manualRouteLine.setMap(null);
            window.manualRouteLine = null;
        }

        if (window.directionsRenderer) {
            window.directionsRenderer.set('directions', null);
        }

        if (type === 'pickup' && selectedDestination) {
            focusSingleLocation(selectedDestination, 'destination');
        } else if (type === 'destination' && selectedPickup) {
            focusSingleLocation(selectedPickup, 'pickup');
        }
    };

    const getDebouncerDelay = val => {
        return val.length <= 4 ? 500 : 300;
    };

    const renderMenu = dropdown => {
        dropdown.empty();

        dropdown.addClass('is-initial-menu');

        const hasLocationAccess  = window.hasLocationPermission || window.apiComponentRestriction;
        const currentLocTitle    = hasLocationAccess ? 'Your location' : 'Allow location access';
        const currentLocSubtitle = hasLocationAccess ? 'Use your current location as pickup address' : 'It provides your pickup address';

        const menuHtml = `
            <li class="search-input-dropdown__item menu-option-current">
                <span class="menu-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M3 12h3m12 0h3M12 3v3m0 12v3"></path></svg>
                </span>
                <div class="menu-content d-flex flex-column">
                    <span class="location-name">${currentLocTitle}</span>
                    <span class="location-subtitle">${currentLocSubtitle}</span>
                </div>
            </li>
            <li class="search-input-dropdown__item menu-option-search">
                <span class="menu-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </span>
                <div class="menu-content d-flex flex-column">
                    <span class="location-name">Search pickup location</span>
                    <span class="location-subtitle">Enter your address manually</span>
                </div>
            </li>
        `;

        dropdown.append(menuHtml).show();
    };

    const changeIconState = (hasInput, type) => {
        const isPickup = type === 'pickup';
        const $container = $(isPickup ? '.location-icon-pickup' : '.location-icon-destination');
        const $clearIcon = $container.find('.icon-clear');

        if (isPickup) {
            const $fetchIcon = $container.find('.icon-fetch');

            $fetchIcon.toggleClass('d-none', hasInput);
            $clearIcon.toggleClass('d-none', !hasInput);
        } else {
            $clearIcon.toggleClass('d-none', !hasInput);
        }
    };

    const setupAutoComplete = (inputSelector, latSelector, lngSelector, dropdownSelector, type) => {
        const $input    = $(inputSelector);
        const $lat      = $(latSelector);
        const $lng      = $(lngSelector);
        const $dropdown = $(dropdownSelector);

        if (!$dropdown.length) {
            return showNotifyModal('error', 'Something is missing on the system');
        } 

        let index         = -1;
        let debounceTimer = null;

        const getItems = () => $dropdown.find('.search-input-dropdown__item').not('.loading, .search-input-dropdown__no-results');

        const highlight = i => {
            const $items = getItems();

            if (!$items.length) {
                return;
            }

            index = (i + $items.length) % $items.length;
            $items.removeClass('highlighted').eq(index).addClass('highlighted');
        };

        const selectItem = () => getItems().eq(index).trigger('pointerdown');

        const renderList = predictions => {
            $dropdown.empty();
            index = -1;

            if (!predictions?.length) {
                $dropdown.append(createDropDownItem({
                    description: 'No results',
                    isNoResults: true
                }));
                
                return $dropdown.show();
            }

            predictions.forEach(p => $dropdown.append(createDropDownItem({
                placeId    : p.place_id,
                description: p.description
            })));

            $dropdown.show();
        };

        $dropdown.on('pointerdown', '.search-input-dropdown__item', e => {
            const $item = $(e.currentTarget);
            e.preventDefault();

            if (type == 'pickup') {
                if ($item.hasClass('menu-option-current')) {
                    

                    if (!window.apiComponentRestriction) {
                    // SET THE GLOBAL FLAG HERE
                        window.isAutoFetching = true; 
                
                        // Call the function from the other file
                        $dropdown.hide();
                        window.reinitializeLocation();
                    } else {
                        $dropdown.hide();
                        window.handleCurrentLocationAction();
                    }

                    return;
                }
    
                if ($item.hasClass('menu-option-search')) {
                    $dropdown.hide();
                    $input.focus();
                    return;
                }
            }
            
            if ($item.hasClass('search-input-dropdown__no-results')) {
                return;
            }

            const placeId = $item.data('place-id');
            const text    = $item.find('.location-name').text();
            const $btn    = $input.closest('.input-group-custom').find('.input-group-custom__icon');
            const $icon   = $btn.closest('.input-group-custom').find('.location__icon');
            
            toggleButtonSpinner($icon, true);
            $dropdown.hide().empty();

            fetchPlaceDetails(placeId, details => {
                toggleButtonSpinner($icon, false);

                if (!details?.location) {
                    $input.prop('disabled', false);
                    return showNotifyModal('warning', 'No place details found');
                }

                fillUpLocation(details, text, {
                    input        : $input,
                    latInput     : $lat,
                    lngInput     : $lng,
                    locationInput: $input.length ? $input: null
                }, type);

                tryDrawRoute();
            });
        });

        if (type == 'pickup') {
            $input.on('focus', () => {
                if (!$input.val().trim()) {

                    if (!window.isAutoFetching) {
                        renderMenu($dropdown);
                    }
                }
            });
        }

        $input.on('keydown', e => {
            if (!$dropdown.is(':visible')) {
                return;
            } 

            const actions = {
                ArrowDown: () => highlight(index + 1),
                ArrowUp  : () => highlight(index - 1),
                Enter    : () => selectItem()
            };

            const action = actions[e.key];

            if (!action) {
                return;
            }

            e.preventDefault();
            return action();
        });

        $input.on('input', () => {
            clearTimeout(debounceTimer);

            const val = $input.val().trim();

            if (!val) {

                if (activeRequest) {
                    activeRequest.abort();
                    activeRequest = null;
                }

                handleLocationClear(type);

                if (type == 'pickup') {
                    changeIconState(false, 'pickup');

                    if (!window.isAutoFetching) {
                        renderMenu($dropdown);
                    }
                } else {
                    changeIconState(false, 'destination');
                    $dropdown.hide().empty();
                }

                return;
            }

            changeIconState(true, type);

            if (val.length < 3) {
                $dropdown.hide().empty();
                return;
            }

            debounceTimer = setTimeout(() => {
               

                if ($dropdown.hasClass('is-initial-menu')) {
                    $dropdown.removeClass('is-initial-menu');
                }

                fetchPlaceSuggestions(val, renderList, dropdownSelector);    
            }, getDebouncerDelay(val));
        });

        $(document).on('pointerdown.autocomplete', e => {
            if (!$(e.target).closest($input).length && !$(e.target).closest($dropdown).length) {
                $dropdown.hide();
            }
        });
    };

    /* ------------------------------------------------------------------------------------
    * Current Location selection
    * ------------------------------------------------------------------------------------ */

    const getLocationByLatLng = (lat, lng, params, type = 'pickup') => {

        const payload = {
            lat: lat,
            lng: lng
        };

        return $.get(locationByLatLng, payload, null, 'json')
            .done(({ status, message, data } = {}) => {

                if (status == 'success') {
                    fillUpLocation(data, null, params, type);
                } else {
                    showNotifyModal('error', message || 'Location not found');
                }
            })
            .fail(() => showNotifyModal('error', 'Failed to resolve location'));
    };

    const getCurrentLocation = (successCallback, errorCallback) => {
        if ($('#pickup_suggestions').length) {
            $('#pickup_suggestions').hide();
        }

        if (!navigator.geolocation) {
            return errorCallback('Geolocation is not supported on your browser');
        }

        navigator.geolocation.getCurrentPosition(
            (position) => {

                const { latitude: lat, longitude: lng, accuracy } = position.coords;


                successCallback({ lat, lng, accuracy });
            },
            (err) => {

                const messages = {
                    1: 'Location permission denied',
                    2: 'Position unavailable',
                    3: 'Location request time out'
                };

                errorCallback(messages[err.code] || 'Unable to get your location');
            },
            {
                enableHighAccuracy: true,
                timeout           : 10000,
                maximumAge        : 0
            }
        );
    };

    window.handleCurrentLocationAction = () => {

        if ($('#pickup_suggestions').length) {
            $('#pickup_suggestions').hide();
        }

        const $icon      = $('.location-icon-pickup');
        const components = window.apiComponentRestriction;

        if (!components) {
            window.reinitializeLocation();
        }

        toggleButtonSpinner($icon, true);

        getCurrentLocation(
            (location) => {
                if (!location?.lat || !location?.lng) {
                    toggleButtonSpinner($icon, false);
                    return;
                }

                getLocationByLatLng(location.lat, location.lng, {
                    input: '#pickup_location_input',
                    latInput: '#pickup_latitude',
                    lngInput: '#pickup_longitude',
                    locationInput: '#pickup_location_input'
                })
                .done(() => {
                    tryDrawRoute();
                })
                .always(() => {
                    toggleButtonSpinner($icon, false);
                    const hasValue = $('#pickup_location_input').val().trim().length > 0;
                    changeIconState(hasValue, 'pickup');
                })
                .fail(() => {
                    toggleButtonSpinner($icon, false);
                    changeIconState(false, 'pickup');
                });
            },
            (errMessage) => {
                toggleButtonSpinner($icon, false);
                changeIconState(false, 'pickup');
            }
        );
    };

   


    $(document).on('click', '.icon-clear', e => {
        e.preventDefault();
        
        const $btn       = $(e.currentTarget);
        const $container = $btn.closest('.input-group-custom');
        const type       = $container.find('#pickup_location_input').length > 0 ? 'pickup' : 'destination';
        
        if (type == 'pickup') {
            $('#pickup_location_input').val('');
            $('#pickup_latitude').val('');
            $('#pickup_longitude').val('');
        } else {
            $('#destination_location_input').val('');
            $('#destination_latitude').val('');
            $('#destination_longitude').val('');
        }
        
        changeIconState(false, type);
        handleLocationClear(type);
        $container.find('.search-input-dropdown__wrapper').hide().empty();
        tryDrawRoute();
    });

    /* ------------------------------------------------------------------------------------
     * Map & Route
     * ------------------------------------------------------------------------------------ */

    const getStoredLocation = () => {
        const lat = parseFloat(localStorage.getItem("userLat"));
        const lng = parseFloat(localStorage.getItem("userLng"));
        
        return Number.isFinite(lat) && Number.isFinite(lng) ? { lat: lat, lng: lng } : null;
    };
  
    window.initMap = () => {

        if (map) {
            return;
        }
      
        if (!window.isLocationReady) {
            return;
        }
        
        const storedLocation = getStoredLocation();
  
        let lat = parseFloat(defaultMap?.lat);
        let lng = parseFloat(defaultMap?.lng);
        
        const hasValidDefault = Number.isFinite(lat) && Number.isFinite(lng);
      
        const center = storedLocation ? storedLocation : hasValidDefault ? { lat, lng } : null;

        map = new google.maps.Map(document.getElementById('map'), {
            center: center,
            zoom  : 12,
        });

        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer({
            suppressMarkers: true,
            polylineOptions: {
                strokeColor: '#7c4dff',
                strokeOpacity: 0.9,
                strokeWeight: 5
            }
        });

        directionsRenderer.setMap(map);

        if (runningRide) {
            const pickup = {
                lat: parseFloat(runningRide.pickup_latitude),
                lng: parseFloat(runningRide.pickup_longitude)
            };

            const destination = {
                lat: parseFloat(runningRide.destination_latitude),
                lng: parseFloat(runningRide.destination_longitude)
            };

            routeMarker(pickup, destination);
            drawRoute(pickup, destination);
        }

        window.mapInitPending = false;
    };

    $(document).on('click', '.serviceOption', e => {

        const serviceId = Number($(e.currentTarget).val());
        
        if (!serviceId) {
            return showNotifyModal('error', 'Invalid service selected');
        }

        if (!serviceData?.length) {
            return showNotifyModal('error', 'Select pickup and destination before');
        }

        const service = serviceData.find(s => s.service_id == serviceId);
        
        if (!service) {
            return showNotifyModal('error', 'Selected service is not available right now');
        }

        $(document).find('.offerAmount').val(Number(service.recommend_amount ?? 0));
        $('[name=service_id]').val(serviceId);
    });

    $(document).on('click', '.note-btn', e => {
        const $button = $(e.currentTarget);
        $button.closest('.input-group__wrapper').find('.note-input').toggleClass('show');
    });

    $(document).on('submit', '.findDriverForm', e => {
        e.preventDefault();
    
        const $form   = $(e.currentTarget);
        const $button = $form.find('[type="submit"]');
    
        const payload = {
            service_id           : $('[name=service_id]').val(),
            pickup_latitude      : $('[name=pickup_latitude]').val(),
            pickup_longitude     : $('[name=pickup_longitude]').val(),
            pickup_location      : $('[name=pickup_location]').val(),
            destination_latitude : $('[name=destination_latitude]').val(),
            destination_longitude: $('[name=destination_longitude]').val(),
            destination_location : $('[name=destination_location]').val(),
            offer_amount         : $('[name=offer_amount]').val(),
            gateway_currency_id  : $('[name=gateway_currency_id]').val(),
            number_of_passenger  : $('[name=number_of_passenger]').val(),
            note                 : $('[name=note]').val()
        };
    
        payload.payment_type = payload.gateway_currency_id === 'cash' ? CASH : GATEWAY;
        payload._token = csrfToken;
    
        $('[name=payment_type]').val(payload.payment_type);
    
        if (!payload.pickup_location) {
            return showNotifyModal('error', 'The pickup location not found');
        }
         if (!payload.destination_location) {
            return showNotifyModal('error', 'The destination location not found');
        }

        if (!payload.pickup_latitude) {
            return showNotifyModal('error', 'The pickup latitude not found');
        }
      
        if (!payload.pickup_longitude) {
            return showNotifyModal('error', 'The pickup longitude not found');
        }
    
        if (!payload.destination_latitude) {
            return showNotifyModal('error', 'The destination latitude not found');
        }
        
        if (!payload.destination_longitude) {
            return showNotifyModal('error', 'The destination longitude not found');
        }
       
        if (!payload.service_id) {
            return showNotifyModal('error', 'The service is not selected.');
        }
        if (!payload.offer_amount) {
            return showNotifyModal('error', 'The offer amount is required. And it should be greater than 0.');
        }
    
        if (!payload.payment_type) {
            return showNotifyModal('error', 'The payment type filed is not found');
        }
        if (!payload.gateway_currency_id) {
            return showNotifyModal('error', 'The payment gateway filed is required.');
        }
    
        if (!payload.number_of_passenger) {
            return showNotifyModal('error', 'The passenger filed is required.');
        }
    
        const $findDriverDiv = $('.findDriverDiv');
        const previousHtml   = $findDriverDiv.html();
    
        $button.prop('disabled', true);
        $findDriverDiv.html(showTypingLoader('Creating Ride'));

        const rollback = () => {
            $findDriverDiv.find('.select2-container').remove();
            $findDriverDiv.html(previousHtml);
            $button.prop('disabled', false);

            window.paymentSelectInteraction();

            const $currentForm = $('.findDriverForm');

            $currentForm.find('[name=gateway_currency_id]').val(payload.gateway_currency_id).trigger('change');
            $currentForm.find('[name=payment_type]').val(payload.payment_type);
            $currentForm.find('[name=offer_amount]').val(payload.offer_amount);
            $currentForm.find('[name=service_id]').val(payload.service_id);
            $currentForm.find('[name=note]').val(payload.note);
            $currentForm.find('[name=number_of_passenger]').val(payload.number_of_passenger);
            
            $currentForm.find('[name=pickup_latitude]').val(payload.pickup_latitude);
            $currentForm.find('[name=pickup_longitude]').val(payload.pickup_longitude);
            $currentForm.find('[name=pickup_location]').val(payload.pickup_location);
            $currentForm.find('[name=destination_latitude]').val(payload.destination_latitude);
            $currentForm.find('[name=destination_longitude]').val(payload.destination_longitude);
            $currentForm.find('[name=destination_location]').val(payload.destination_location);
        };

        $.post(createRide, payload, null, 'json')
            .done(({ status, message, data } = {}) => {
                
                if (status === 'success') {
                    $('.serviceDiv').remove();
                    $findDriverDiv.remove();
    
                    $('.ride_detail_wrapper').html(data.ride_detail_html);
                    $('.ride_location_wrapper').html(data.location_html);
    
                    showNotifyModal('success', message);
                    carRunningAnimation();
                } else {
                    showNotifyModal('error', message);
                    rollback();
                }
    
            })
            .fail(() => {
                showNotifyModal('error', 'Something went wrong while finding driver');
                rollback();
            });
    });
  
    if ($('#pickup_suggestions').length) {
        setupAutoComplete(
            '#pickup_location_input',
            '#pickup_latitude',
            '#pickup_longitude',
            '#pickup_suggestions',
            'pickup'
        );
    }

    if ($('#destination_suggestions').length) {
        setupAutoComplete(
            '#destination_location_input',
            '#destination_latitude',
            '#destination_longitude',
            '#destination_suggestions',
            'destination'
        );
    }

    if ($('.progress_bar .value_text').length) {
        carRunningAnimation();
    }

})(jQuery, window);