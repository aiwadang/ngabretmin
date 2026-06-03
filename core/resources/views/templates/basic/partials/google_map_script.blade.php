@push('script')
    <script>
        'use strict';

        function getLocationByLatLon(lat, lng, callback, params) {
            $.ajax({
                type: "GET",
                url: "{{ route('google.map.location.by.lat.lng') }}",
                data: {
                    lat: lat,
                    lng: lng
                },
                dataType: "JSON",
                success: response => {
                    callback(response, params);
                }
            });
        }

        function getSuggestedPlaces(value, callback, params = {}) {
            value = value.trim();
            if (!value) {
                return;
            }

            $.ajax({
                type: "GET",
                url: "{{ route('google.map.suggested.places') }}",
                data: {
                    input     : value,
                    components: window.apiComponentRestriction || '',
                },
                dataType: "JSON",
                success: response => {
                    callback(response, params);
                }
            });
        }

        function showSuggestedPlaces(response, params = {}) {
            if (response.status) {
                let html = '';
                let predictions = response.data.predictions;

                const iconUrl = "{{ getImage('assets/images/location.png') }}";
                predictions.forEach(({
                    place_id,
                    description
                }) => {
                    html += `<li class="search-input-dropdown__item locationItem" 
                    data-formatted_address="${description}" 
                    data-container="${params?.container || ''}" 
                    data-input_field="${params?.input_field || ''}"
                    data-lat_input_id="${params?.lat_input_id || ''}" 
                    data-lon_input_id="${params?.lon_input_id || ''}" 
                    data-place-id="${place_id}" 
                    style="cursor:pointer;">
                    <span class="location__icon">
                        <img src="${iconUrl}" alt="location icon">
                    </span>
                    <span class="location-name">${description}</span>
                </li>`;
                });

                if ($(params.container).length > 0) {
                    $(params.container).html(html);
                    $(params.container).show();
                }
            } else {
                const iconUrl = "{{ getImage('assets/images/location.png') }}";
                let html = `<li class="search-input-dropdown__item">
                                    <span class="location__icon">
                                        <img src="${iconUrl}" alt="img">
                                    </span>
                                    <span class="location-name">@lang('No Results Found')</span>
                                </li>`;

                if ($(params.container).length > 0) {
                    $(params.container).html(html);
                    $(params.container).show();
                }
            }
        }

        $(document).on('click', '.locationItem', function() {
            let placeId = $(this).data('place-id');
            if (!placeId) return;

            let container = $(this).data('container');

            let params = {
                input_field: $(this).data('input_field'),
                lat_input_id: $(this).data('lat_input_id'),
                lon_input_id: $(this).data('lon_input_id'),
            }

            $(params.input_field).val($(this).data('formatted_address'));
            $(container).hide();

            getLocationDetails(placeId, fillupLocation, params);
        });

        function getLocationDetails(placeId, callback, params = {}) {
            let url = "{{ route('google.map.place.details', ':placeId') }}";
            url = url.replace(':placeId', placeId);

            $.get("{{ route('google.map.place.details') }}", {
                place_id : placeId
            }).done(res => {
                if (res.status == 'success') {
                    callback(res.data);
                }
            });
        }

        function fillupLocation(response, params) {
            if (response.status) {
                $(params.lat_input_id).val(response.data.location.lat);
                $(params.lon_input_id).val(response.data.location.lng);
                $(params.input_field).val(response.data.formatted_address);
            }
        }

        async function getCurrentLocation() {
            let lat;
            let lon;

            if (navigator.permissions) {
                try {
                    const permission = await navigator.permissions.query({
                        name: 'geolocation'
                    });
                    if (permission.state === 'denied') {
                        showMessage('Please enable location from your browser settings.', 'warning');
                        return false;
                    }
                } catch (err) {
                    showMessage('Unable to get your location. Please enable location permissions and try again.', 'warning');
                    return false;
                }
            }

            if (!navigator.geolocation) {
                showMessage('Geolocation is not supported by your browser.', 'error');
                return false;
            }

            try {
                const position = await new Promise((resolve, reject) => {
                    navigator.geolocation.getCurrentPosition(resolve, reject, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    });
                });

                lat = position.coords.latitude;
                lon = position.coords.longitude;

                hideMessage();

                return {
                    lat,
                    lon
                };

            } catch (error) {
                showMessage('Unable to get your location. Please enable location permissions and try again.', 'warning');
                return false;
            }
        }

        const showMessage = (msg, type = 'warning', container = $('.locationPermissionAlertBox')) => {
            if (!container.length) return;
            const colorClass = {
                info: 'alert-info',
                success: 'alert-success',
                warning: 'alert-warning',
                error: 'alert-danger'
            } [type] || 'alert-warning';

            container.html(`<div class="alert ${colorClass}">${msg}</div>`).show();
        };

        const hideMessage = (container = $('.locationPermissionAlertBox')) => {
            if (container.length) container.hide().html('');
        };
    </script>
@endpush
