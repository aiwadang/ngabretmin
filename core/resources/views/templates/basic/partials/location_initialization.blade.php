<div class="status-modal-overlay" id="statusModal">
    <div class="status-modal">
        <div class="status-icon" id="statusIcon">
            <i class="las"></i>
        </div>

        <h3 class="status-title" id="statusTitle"></h3>

        <p class="status-message" id="statusMessage"></p>

        <button class="status-close-btn">@lang('OK')</button>
    </div>
</div>

@push('style')
    <style>
        .status-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999999;

            opacity: 0;
            transform: scale(0);
            transition: all 0.3s ease;
        }

        .status-modal-overlay.active {
            opacity: 1;
            transform: scale(1);
        }

        .status-modal {
            width: 90%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 16px;
            padding: 30px 20px;
            text-align: center;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }

        .status-icon {
            font-size: 60px;

        }

        .status-title {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .status-message {
            font-size: 15px;
            color: #555;
            margin-bottom: 25px;
        }

        .status-close-btn {
            padding: 8px 25px;
            border-radius: 8px;
            border: none;
            background: hsl(var(--base));
            color: #fff;
            cursor: pointer;
            transition: 0.3s;
        }

        .status-close-btn:hover {
            opacity: 0.9;
        }

        /* ===== Status Colors ===== */

        .status-success .status-icon,
        .status-success .status-title {
            color: #28a745;
        }

        .status-warning .status-icon,
        .status-warning .status-title {
            color: #f0ad4e;
        }

        .status-error .status-icon,
        .status-error .status-title {
            color: #dc3545;
        }
    </style>
@endpush

<script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>

<script>
    (function($) {

        $(function($) {
            'use strict';

            window.isGoogleReady = false;
            window.isLocationReady = false;

            if (!window.appConfig) {
                return notify('error', 'Something is missing on the system');
            }

            const {
                googleMapsApiKey,
                modalAction: {
                    showNotifyModal,
                    closeNotifyModal
                },
                routes: {
                    locationByLatLng
                }
            } = window.appConfig;

            if (!googleMapsApiKey) {
                return;
            }

            closeNotifyModal();

            const clearStoredLocation = () => {
                localStorage.removeItem("userCountry");
                localStorage.removeItem("userCity");
                localStorage.removeItem("userLat");
                localStorage.removeItem("userLng");
                localStorage.removeItem("locationInitialized");

                window.apiComponentRestriction = null;
                window.autocompleteRestrictions = null;
                window.hasLocationPermission = false;
                window.userCurrentCity = '';
                window.userCurrentLatitude = '';
                window.userCurrentLongitude = '';
            };

            window.triggerMapIfReady = () => {

                if (window.isGoogleReady) {
                    if (typeof window.initMap === "function") {
                        window.initMap();
                    }
                }
            };

            const waitForGoogle = setInterval(() => {
                if (window.google && window.google.maps) {
                    window.isGoogleReady = true;
                    clearInterval(waitForGoogle);

                    triggerMapIfReady();
                }
            }, 100);

            if (navigator.permissions) {
                navigator.permissions.query({
                    name: 'geolocation'
                }).then(function(permissionStatus) {

                    if (permissionStatus.state === 'denied') {
                        clearStoredLocation();
                    }
                    permissionStatus.onChange = () => {
                        if (permissionStatus.state === 'denied' || permissionStatus.state ===
                            'prompt') {
                            clearStoredLocation();
                        } else if (permissionStatus.state === 'granted') {
                            reinitializeLocation();
                        }
                    };
                });
            }

            const getCountryFromCoords = (lat, lng, callback) => {

                if (!lat || !lng || isNaN(lat) || isNaN(lng)) {
                    showNotifyModal('error', "Invalid coordinates provided");
                    return callback(null);
                }

                const payload = {
                    lat: lat,
                    lng: lng
                };

                $.get(locationByLatLng, payload, null, 'json')
                    .done(({
                        status,
                        message,
                        data
                    } = {}) => {
                        if (status != 'success') {
                            showNotifyModal('error', message);
                            return callback(null);
                        }

                        callback({
                            lat: data.lat,
                            lng: data.lng,
                            countryCode: data.countryCode,
                            city: data.city
                        })
                    })
                    .fail(() => callback(null));
            };

            const tryInitMap = () => {
                const lat = parseFloat(localStorage.getItem("userLat"));
                const lng = parseFloat(localStorage.getItem("userLng"));

                const hasValidLocation = Number.isFinite(lat) && Number.isFinite(lng);

                if (!hasValidLocation) {

                    if (!window._locationRequested) {
                        window._locationRequested = true;

                        setTimeout(() => {
                            reinitializeLocation();
                        }, 500);

                    } else {
                        if (window.google?.maps && typeof window.initMap === "function") {
                            window.initMap();
                        }
                    }

                    return;
                }

                if (window.google?.maps && typeof window.initMap === "function") {
                    window.initMap();
                } else {
                    window.mapInitPending = true;
                }
            };

            const initAutoCompleteWithCountry = (countryCode, city, lat, lng) => {

                window.autocompleteRestrictions = countryCode ? {
                    country: countryCode
                } : {};
                window.apiComponentRestriction = countryCode ? `country:${countryCode}` : '';

                window.userCurrentCity = city || '';
                window.userCurrentLatitude = lat || '';
                window.userCurrentLongitude = lng || '';
            };

            const askLocationAndSetCountryInSession = () => {

                if (location.protocol !== "https:" && location.hostname !== "localhost") {
                    window.hasLocationPermission = false;
                    return;
                }

                if (!navigator.geolocation) {
                    window.hasLocationPermission = false;
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    ({
                        coords
                    }) => {

                        const {
                            latitude: lat,
                            longitude: lng
                        } = coords;

                        window.hasLocationPermission = true;

                        getCountryFromCoords(lat, lng, geo => {

                            if (!geo?.countryCode) {
                                return;
                            }

                            localStorage.setItem("userCountry", geo.countryCode);
                            localStorage.setItem("userCity", geo.city || "");
                            localStorage.setItem("userLat", geo.lat);
                            localStorage.setItem("userLng", geo.lng);

                            window.autocompleteRestrictions = {
                                country: geo.countryCode
                            };
                            window.apiComponentRestriction = `country:${geo.countryCode}`;

                            if (!localStorage.getItem("locationInitialized")) {
                                localStorage.setItem("locationInitialized", "true");
                            }

                            initAutoCompleteWithCountry(geo.countryCode, geo.city, geo.lat,
                                geo.lng);
                        });

                    },
                    ({
                        code
                    }) => {

                        window.hasLocationPermission = false;

                    }
                );
            };

            const initLocation = () => {
                const storedCountry = localStorage.getItem("userCountry");
                const storedCity = localStorage.getItem("userCity");
                const storedLatitude = localStorage.getItem("userLat");
                const storedLongitude = localStorage.getItem("userLng");

                if (storedCountry && storedLatitude && storedLongitude) {

                    initAutoCompleteWithCountry(
                        storedCountry,
                        storedCity,
                        parseFloat(storedLatitude),
                        parseFloat(storedLongitude)
                    );

                    window.isLocationReady = true;
                    triggerMapIfReady();
                } else {
                    window.isLocationReady = true;
                    triggerMapIfReady();
                }
            };

            initLocation();


            window.reinitializeLocation = () => {
                if ($('#pickup_suggestions').length) {
                    $('#pickup_suggestions').hide();
                }

                if (!navigator.geolocation) {
                    window.hasLocationPermission = false;
                    return;
                }

                localStorage.removeItem("userCountry");
                localStorage.removeItem("userCity");
                localStorage.removeItem("userLat");
                localStorage.removeItem("userLng");

                window.apiComponentRestriction = null;
                window.autocompleteRestrictions = null;


                navigator.geolocation.getCurrentPosition(
                    ({
                        coords
                    }) => {

                        const {
                            latitude: lat,
                            longitude: lng
                        } = coords;

                        window.hasLocationPermission = true;

                        getCountryFromCoords(lat, lng, geo => {

                            if (!geo?.countryCode) {
                                return notify("error", "Unable to detect your country.");
                            }

                            localStorage.setItem("userCountry", geo.countryCode);
                            localStorage.setItem("userCity", geo.city || "");
                            localStorage.setItem("userLat", geo.lat);
                            localStorage.setItem("userLng", geo.lng);

                            window.autocompleteRestrictions = {
                                country: geo.countryCode
                            };
                            window.apiComponentRestriction = `country:${geo.countryCode}`;

                            if (window.isAutoFetching) {
                                window.isAutoFetching = false;
                                if (typeof window.handleCurrentLocationAction ===
                                    "function") {
                                    window.handleCurrentLocationAction();
                                }
                            }

                            window.isLocationReady = true;
                            triggerMapIfReady();
                        });

                    },
                    ({
                        code
                    }) => {

                        const messages = {
                            1: 'Location permission denied. Give permission on browser to use this feature.',
                            2: 'Position unavailable. Please check your device location.',
                            3: 'Location request timed out.'
                        };

                        window.isAutoFetching = false;
                        window.hasLocationPermission = false;
                        showNotifyModal('error',messages[code] || 'Unable to refresh location.');
                    }
                );
            };

        });
    })(jQuery);
</script>
