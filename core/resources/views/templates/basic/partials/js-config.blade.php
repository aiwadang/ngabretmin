@php
    $methods = $paymentMethods->map(function ($method) {
        return [
            'method_code' => $method->method_code,
            'currency' => $method->currency,
            'min_amount' => $method->min_amount,
            'max_amount' => $method->max_amount,
            'name' => __($method->name),
            'image' => getImage(getFilePath('gateway') . '/' . $method->image, getFileSize('gateway')),
        ];
    });

    $methodsJson = json_encode($methods, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    $runningRideJson = json_encode($runningRide, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);

    $operatingCountryObject = gs('operating_country');
    $countryCodes = [];

    if ($operatingCountryObject) {
        foreach ($operatingCountryObject as $code => $data) {
            $countryCodes[] = $code;
        }
    }

    $countryString = implode(',', $countryCodes);
@endphp

<script>
    const formatOption = state => {
        if (!state.id) {
            return state.text;
        }

        const image = $(state.element).data('image');
        return `<span><img src="${image}" width="20" height="20" class="img-fluid me-2" /> ${state.text}</span>`;
    };

    const initSelect2 = () => {
        $('.payment-select').select2({
            templateResult: formatOption,
            templateSelection: formatOption,
            escapeMarkup: m => m
        });
    };

    const showTypingLoader = (text = '') => $(`
        <div class="fare-loader-wrapper">
            <div class="typing-loader typing-loader--lg">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <div class="typing-text text--secondary">
                ${text}
            </div>
        </div>
    `);

    const locationLoader = () => $(`
        <li class="search-input-dropdown__item typing-loader-wrapper">
            <div class="typing-loader">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <div class="typing-text">
                Searching location
            </div>
        </li>
    `);

    const buttonLoader = (button, loading) => {
        if (loading) {
            if (!button.data('original-html')) {
                button.data('original-html', button.html());
            }

            button.addClass('disabled').attr("disabled", true).html(`
                <div class="button-loader d-flex gap-2 flex-wrap align-items-center justify-content-center">
                    <div class="spinner-border"></div><span>Loading...</span>
                </div>
            `);
        } else {
            if (button.data('original-html')) {
                button.html(button.data('original-html'));
            }

            if (!button.hasClass('banner__button')) {
                button.removeClass('disabled').attr('disabled', false);
            }
        }
    };

    const restoreButton = button => {
        const $btn = $(button);

        if (!$btn.length) {
            return;
        }

        const originalHtml = $btn.data('original-html');

        if (originalHtml) {
            $btn.html(originalHtml).prop('disabled', false);
        }
    };

    const formatRating = rating => Math.round(Math.max(0, Math.min(5, rating)) * 2) / 2;

    const showStatusModal = (type, message) => {
        const $modal = $('#statusModal');
        const $icon = $modal.find('#statusIcon i');

        const types = {
            success: {
                icon: 'las la-check-circle',
                title: 'Success',
                class: 'status-success'
            },
            warning: {
                icon: 'las la-exclamation-triangle',
                title: 'Warning',
                class: 'status-warning'
            },
            error: {
                icon: 'las la-times-circle',
                title: 'Error',
                class: 'status-error'
            }
        };

        const config = types[type] || types.error;

        $modal.removeClass('status-success status-warning status-error').addClass(config.class);

        $icon.attr('class', config.icon);

        $modal.find('#statusTitle').text(config.title);
        $modal.find('#statusMessage').text(message);
        $modal.addClass('active');
    };

    const closeNotifyModal = () => {
        $(document).on('click', '.status-close-btn', function() {
            $('#statusModal').removeClass('active');
        });
    }

    const carRunningAnimation = () => {
        const $bar = $('.progress');
        const $car = $('.progress_bar .value_text');

        const interval = 16;
        const speed = 2;
        const fadeRange = 30;

        let pos = -$car.width();

        setInterval(() => {
            const width = $bar.width();
            const carW = $car.outerWidth();

            pos += speed;

            if (pos > width + carW) {
                pos = -carW;
            }

            let opacity = 1;

            if (pos < fadeRange) {
                opacity = Math.max(0, pos / fadeRange);
            } else if (pos > width - fadeRange) {
                opacity = Math.max(0, (width - pos) / fadeRange);
            }

            $car.css({
                left: pos + 'px',
                opacity: opacity
            });
        }, interval);
    };

    window.appConfig = {
        googleMapsApiKey: "{{ gs('google_maps_client_api_key') }}",
        defaultMap: {
            lat: parseFloat("{{ gs('default_map_latitude') }}"),
            lng: parseFloat("{{ gs('default_map_longitude') }}")
        },
        routes: {
            calculateFare: "{{ route('user.ride.calculate') }}",
            createRide: "{{ route('user.ride.create') }}",
            rejectBid: "{{ route('user.ride.reject') }}",
            acceptBid: "{{ route('user.ride.accept') }}",
            cancelRide: "{{ route('user.ride.cancel') }}",
            couponIndex: "{{ route('user.coupon.index') }}",
            couponApply: "{{ route('user.coupon.apply') }}",
            couponRemove: "{{ route('user.coupon.remove') }}",
            addTips: "{{ route('user.tips.add') }}",
            removeTips: "{{ route('user.tips.remove') }}",
            storeReview: "{{ route('user.review.store') }}",
            savePayment: "{{ route('user.ride.payment.save') }}",
            suggestedPlaces: "{{ route('google.map.suggested.places') }}",
            placeDetails: "{{ route('google.map.place.details') }}",
            locationByLatLng: "{{ route('google.map.location.by.lat.lng') }}"
        },
        csrfToken: '{{ csrf_token() }}',
        paymentStatusCodes: {
            CASH: '{{ Status::PAYMENT_TYPE_CASH }}',
            GATEWAY: '{{ Status::PAYMENT_TYPE_GATEWAY }}'
        },
        assets: {
            locationIcon: "{{ getImage('assets/images/location.png') }}",
            cash: "{{ getImage('assets/images/payment.png') }}"
        },
        runningRide: JSON.parse('{!! $runningRideJson !!}'),
        paymentMethods: JSON.parse('{!! $methodsJson !!}'),
        userId: "{{ auth()->id() }}",
        pusher: {
            key: "{{ config('broadcasting.connections.pusher.key') }}",
            cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
            authEndpoint: "{{ route('pusher.auth') }}"
        },
        generalSetting: {
            curText: "{{ __(gs('cur_text')) }}",
            curSym: "{{ gs('cur_sym') }}",
            allowPrecision: "{{ gs('allow_precision') }}",
            distanceUnit: "{{ gs('distance_unit') == Status::KM_UNIT ? __('KM') : __('Miles') }}",
            operatingCountry: "{{ $countryString }}"
        },
        initSelectTwo: initSelect2,
        notifier: {
            error: msg => notify('error', msg),
            success: msg => notify('success', msg),
            warning: msg => notify('warning', msg)
        },
        loader: {
            showTypingLoader: text => showTypingLoader(text),
            locationLoader: locationLoader,
            buttonLoader: (button, loading) => buttonLoader(button, loading),
            restoreButton: (button) => restoreButton(button),
            carRunningAnimation: carRunningAnimation
        },
        modalAction: {
            showNotifyModal: (type, message) => showStatusModal(type, message),
            closeNotifyModal: closeNotifyModal
        },
        formatRating: rating => formatRating(rating),
    };
</script>
