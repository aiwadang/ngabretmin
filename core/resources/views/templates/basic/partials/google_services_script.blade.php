<script src="https://maps.googleapis.com/maps/api/js?key={{ gs('google_maps_client_api_key') }}&libraries=places,geometry"></script>
<script>
    'use strict';

    let map;
    let directionsService;
    let directionsRenderer;

    let pickupMarker;
    let destinationMarker;
    let driverMarker = null;

    const geocoder = new google.maps.Geocoder();

    /* ===============================
       ENHANCED SVG MARKERS
    ================================ */
    function svgPickupMarker() {
        return {
            url: `data:image/svg+xml;charset=UTF-8,
        <svg xmlns='http://www.w3.org/2000/svg' width='48' height='48' viewBox='0 0 48 48'>
            <defs>
                <filter id='shadow' x='-50%25' y='-50%25' width='200%25' height='200%25'>
                    <feGaussianBlur in='SourceAlpha' stdDeviation='2'/>
                    <feOffset dx='0' dy='2' result='offsetblur'/>
                    <feComponentTransfer>
                        <feFuncA type='linear' slope='0.3'/>
                    </feComponentTransfer>
                    <feMerge>
                        <feMergeNode/>
                        <feMergeNode in='SourceGraphic'/>
                    </feMerge>
                </filter>
            </defs>
            <g filter='url(%23shadow)'>
                <path d='M24 4 C15 4 8 11 8 20 C8 30 24 44 24 44 S40 30 40 20 C40 11 33 4 24 4 Z' 
                      fill='%234CAF50' stroke='white' stroke-width='2'/>
                <circle cx='24' cy='20' r='8' fill='white'/>
                <text x='24' y='25' text-anchor='middle' font-size='14' font-weight='bold' 
                      fill='%234CAF50' font-family='Arial'>P</text>
            </g>
        </svg>`,
            scaledSize: new google.maps.Size(48, 48),
            anchor: new google.maps.Point(24, 44)
        };
    }

    function svgDestinationMarker() {
        return {
            url: `data:image/svg+xml;charset=UTF-8,
        <svg xmlns='http://www.w3.org/2000/svg' width='48' height='48' viewBox='0 0 48 48'>
            <defs>
                <filter id='shadow2' x='-50%25' y='-50%25' width='200%25' height='200%25'>
                    <feGaussianBlur in='SourceAlpha' stdDeviation='2'/>
                    <feOffset dx='0' dy='2' result='offsetblur'/>
                    <feComponentTransfer>
                        <feFuncA type='linear' slope='0.3'/>
                    </feComponentTransfer>
                    <feMerge>
                        <feMergeNode/>
                        <feMergeNode in='SourceGraphic'/>
                    </feMerge>
                </filter>
            </defs>
            <g filter='url(%23shadow2)'>
                <path d='M24 4 C15 4 8 11 8 20 C8 30 24 44 24 44 S40 30 40 20 C40 11 33 4 24 4 Z' 
                      fill='%23FF5252' stroke='white' stroke-width='2'/>
                <circle cx='24' cy='20' r='8' fill='white'/>
                <text x='24' y='25' text-anchor='middle' font-size='14' font-weight='bold' 
                      fill='%23FF5252' font-family='Arial'>D</text>
            </g>
        </svg>`,
            scaledSize: new google.maps.Size(48, 48),
            anchor: new google.maps.Point(24, 44)
        };
    }

    function svgDriverMarker() {
        return {
            url: `data:image/svg+xml;charset=UTF-8,
        <svg xmlns='http://www.w3.org/2000/svg' width='52' height='52' viewBox='0 0 52 52'>
            <defs>
                <filter id='shadow3' x='-50%25' y='-50%25' width='200%25' height='200%25'>
                    <feGaussianBlur in='SourceAlpha' stdDeviation='2'/>
                    <feOffset dx='0' dy='2' result='offsetblur'/>
                    <feComponentTransfer>
                        <feFuncA type='linear' slope='0.3'/>
                    </feComponentTransfer>
                    <feMerge>
                        <feMergeNode/>
                        <feMergeNode in='SourceGraphic'/>
                    </feMerge>
                </filter>
            </defs>
            <g filter='url(%23shadow3)'>
                <circle cx='26' cy='26' r='20' fill='%237c4dff' stroke='white' stroke-width='3'/>
                <path d='M14 26 L20 20 L32 20 L38 26 L38 32 L14 32 Z' fill='white'/>
                <circle cx='20' cy='34' r='3' fill='white'/>
                <circle cx='32' cy='34' r='3' fill='white'/>
                <rect x='22' y='22' width='8' height='6' fill='%237c4dff' rx='1'/>
            </g>
        </svg>`,
            scaledSize: new google.maps.Size(52, 52),
            anchor: new google.maps.Point(26, 26)
        };
    }

    /* ===============================
       INIT MAP
    ================================ */
    function initMap(pickup, destination, driverLocation = null) {

        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 14,
            center: pickup,
            styles: [{
                featureType: "poi",
                elementType: "labels",
                stylers: [{
                    visibility: "off"
                }]
            }]
        });

        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer({
            map: map,
            suppressMarkers: true,
            polylineOptions: {
                strokeColor: '#7c4dff',
                strokeWeight: 5,
                strokeOpacity: 0.7
            }
        });

        pickupMarker = new google.maps.Marker({
            position: pickup,
            map: map,
            draggable: true,
            icon: svgPickupMarker(),
            zIndex: 1000,
            animation: google.maps.Animation.DROP
        });

        destinationMarker = new google.maps.Marker({
            position: destination,
            map: map,
            draggable: true,
            icon: svgDestinationMarker(),
            zIndex: 1000,
            animation: google.maps.Animation.DROP
        });

        attachGlobalTooltip(pickupMarker, 'Pickup Location', '#4CAF50');
        attachGlobalTooltip(destinationMarker, 'Destination', '#FF5252');

        if (driverLocation) {
            addDriver(driverLocation.lat, driverLocation.lng);
        }

        drawRoute();

        pickupMarker.addListener('dragend', onLocationChange);
        destinationMarker.addListener('dragend', onLocationChange);
    }

    /* ===============================
       DRAW ROUTE
    ================================ */
    function drawRoute() {
        directionsService.route({
            origin: pickupMarker.getPosition(),
            destination: destinationMarker.getPosition(),
            travelMode: google.maps.TravelMode.DRIVING
        }, (res, status) => {
            if (status === 'OK') {
                directionsRenderer.setDirections(res);

                const bounds = new google.maps.LatLngBounds();
                bounds.extend(pickupMarker.getPosition());
                bounds.extend(destinationMarker.getPosition());
                if (driverMarker) {
                    bounds.extend(driverMarker.getPosition());
                }
                map.fitBounds(bounds, 100);
            }
        });
    }

    /* ===============================
       LOCATION CHANGE
    ================================ */
    function onLocationChange() {
        drawRoute();
    }

    /* ===============================
       DRIVER
    ================================ */
    function addDriver(lat, lng) {

        if (driverMarker) driverMarker.setMap(null);

        driverMarker = new google.maps.Marker({
            position: {
                lat,
                lng
            },
            map: map,
            icon: svgDriverMarker(),
            zIndex: 1001
        });

        attachGlobalTooltip(driverMarker, 'Driver', '#7c4dff');
    }

    function updateDriverLocation(lat, lng) {
        if (!driverMarker) return;
        driverMarker.setPosition({
            lat,
            lng
        });
    }

    /* ===============================
       ENHANCED TOOLTIP WITH DISTANCE
    ================================ */
    function attachGlobalTooltip(marker, title, color) {

        const infoWindow = new google.maps.InfoWindow({
            disableAutoPan: true,
            pixelOffset: new google.maps.Size(0, -10)
        });

        function updateTooltip() {
            geocoder.geocode({
                location: marker.getPosition()
            }, (res, status) => {
                if (status === 'OK' && res[0]) {
                    const address = res[0].formatted_address;

                    // Calculate distance
                    let distanceInfo = '';
                    if (marker === pickupMarker) {
                        const distance = google.maps.geometry.spherical.computeDistanceBetween(
                            pickupMarker.getPosition(),
                            destinationMarker.getPosition()
                        );
                        distanceInfo = `<div style="color: #666; font-size: 12px; padding-top: 8px; border-top: 1px solid #eee; margin-top: 8px;">📍 ${(distance / 1000).toFixed(2)} km to destination</div>`;
                    } else if (marker === destinationMarker) {
                        const distance = google.maps.geometry.spherical.computeDistanceBetween(
                            pickupMarker.getPosition(),
                            destinationMarker.getPosition()
                        );
                        distanceInfo = `<div style="color: #666; font-size: 12px; padding-top: 8px; border-top: 1px solid #eee; margin-top: 8px;">📍 ${(distance / 1000).toFixed(2)} km from pickup</div>`;
                    } else if (marker === driverMarker) {
                        const distance = google.maps.geometry.spherical.computeDistanceBetween(
                            driverMarker.getPosition(),
                            pickupMarker.getPosition()
                        );
                        distanceInfo = `<div style="color: #666; font-size: 12px; padding-top: 8px; border-top: 1px solid #eee; margin-top: 8px;">🚗 ${(distance / 1000).toFixed(2)} km to pickup</div>`;
                    }

                    const content = `
                        <div style="padding: 4px; min-width: 200px;">
                            <div style="font-weight: 600; color: ${color}; margin-bottom: 6px; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">${title}</div>
                            <div style="color: #333; line-height: 1.4; font-size: 13px;">${address}</div>
                            ${distanceInfo}
                        </div>
                    `;

                    infoWindow.setContent(content);
                }
            });
        }

        marker.addListener('mouseover', () => {
            updateTooltip();
            infoWindow.open({
                map,
                anchor: marker,
                shouldFocus: false
            });
        });

        marker.addListener('mouseout', () => infoWindow.close());
        marker.addListener('dragend', updateTooltip);

        updateTooltip();
    }

    /* ===============================
       DEMO DRIVER MOVEMENT
    ================================ */
    function simulateDriverMovement() {
        if (!driverMarker) return;

        let {
            lat,
            lng
        } = driverMarker.getPosition().toJSON();

        setInterval(() => {
            lat += (Math.random() - 0.5) * 0.0004;
            lng += (Math.random() - 0.5) * 0.0004;
            updateDriverLocation(lat, lng);
        }, 4000);
    }

    /* ===============================
       INIT
    ================================ */
    window.onload = () => {

        initMap({
            lat: 23.8103,
            lng: 90.4125
        }, {
            lat: 23.7806,
            lng: 90.4070
        }, {
            lat: 23.8050,
            lng: 90.4100
        });

        simulateDriverMovement();
    };
</script>
