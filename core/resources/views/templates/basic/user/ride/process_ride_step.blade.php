@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="section-padding pb-40">
        <div class="container">
            <div class="booking-wrapper">
                <div class="booking-wrapper__left">
                    <div class="ride_location_wrapper">
                        @if ($runningRide)
                            @include($activeTemplate . 'user.ride.running_ride_location', [
                                'runningRide' => $runningRide,
                            ])
                        @else
                            @include($activeTemplate . 'user.ride.location_box', [
                                'pickup' => $pickup,
                                'runningRide' => $runningRide,
                            ])
                        @endif
                    </div>

                    @if (!$runningRide)
                        <div class="common-card booking-ride-box mb-4 serviceDiv">
                            @include($activeTemplate . 'user.ride.services', [
                                'services' => $services,
                                'rideType' => $rideType,
                            ])
                        </div>
                    @endif

                    <div class="find_driver_wrapper">
                        @if (!$runningRide)
                            @include($activeTemplate . 'user.ride.find_driver', [
                                'paymentMethods' => $paymentMethods,
                                'pickup' => $pickup,
                            ])
                        @endif
                    </div>

                    <div class="ride_detail_wrapper">
                        @include($activeTemplate . 'user.ride.ride_detail', [
                            'runningRide' => $runningRide,
                        ])
                    </div>

                    <div class="bids_wrapper">
                        @include($activeTemplate . 'user.ride.bids', [
                            'runningRideBids' => $runningRideBids,
                        ])
                    </div>

                    <div class="active_ride_wrapper">
                        @if ($runningRide?->status == Status::RIDE_ACTIVE)
                            @include($activeTemplate . 'user.ride.active_ride', [
                                'ride' => $runningRide,
                            ])
                        @endif
                    </div>

                    <div class="running_ride_wrapper">
                        @if ($runningRide?->status == Status::RIDE_RUNNING)
                            @include($activeTemplate . 'user.ride.running_ride', [
                                'runningRide' => $runningRide,
                            ])
                        @endif
                    </div>

                    <div class="ride_end_wrapper">
                        @if ($runningRide?->status == Status::RIDE_END && $runningRide?->payment_status == Status::PAYMENT_PENDING)
                            @include($activeTemplate . 'user.ride.ride_end', [
                                'runningRide' => $runningRide,
                                'methods' => $paymentMethods,
                            ])
                        @elseif ($runningRide?->status == Status::RIDE_END && $runningRide?->payment_status == Status::WAITING_FOR_CASH_PAYMENT)
                            @include($activeTemplate . 'user.ride.payment_confirm_pending', [
                                'runningRide' => $runningRide,
                            ])
                        @endif
                    </div>
                </div>

                <div class="booking-wrapper__right">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Ride Reject Modal --}}
    <div class="modal custom--modal fade" id="rideCancelModal" tabindex="-1" aria-labelledby="rideCancelModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="ride-request-banner d-flex gap-2 justify-content-between">
                    <h5 class="modal-title">@lang('Cancel Ride')</h5>
                    <div class="ride-request__header justify-content-end">
                        <button class="ride-request__close-btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                </div>
                <form class="modal-body pt-2 no-submit-loader rideCancelForm">
                    <div class="ride-review-wrapper text-center">
                        <input type="hidden" name="ride_id">

                        <div class="form-group">
                            <textarea class="form--control" name="cancel_reason" placeholder="@lang('Cancel Reason')" required></textarea>
                        </div>
                        <div class="ride-review__btn">
                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Bid Popup modal --}}

    <div class="modal custom--modal fade" id="bidPopupModal" tabindex="-1" aria-labelledby="bidPopupModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header ride-request__header border-0 pb-0">
                    <div class="ride-request__title-group">
                        <span class="ride-request__icon" aria-hidden="true">
                            <i class="las la-exclamation"></i>
                        </span>
                        <h5 class="modal-title ride-request__title" id="bidPopupModalLabel">@lang('New Driver Request')</h5>
                    </div>
                    <button type="button" class="ride-request__close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>

                <div class="modal-body p-3">
                    <div class="ride-request-banner">
                        <div class="ride-request__content">
                            <div class="driver-info">
                                <img src="" alt="Driver Avatar" class="driver-avatar">
                                <div class="driver-details">
                                    <div class="driver-name"></div>
                                    <div class="driver-car-rating">
                                        <span class="star-rating">
                                            <i class="fa-solid fa-star"></i>
                                            <strong class="rating__value"></strong>
                                        </span>
                                        <span class="separator"> •</span>
                                        <span class="car-model"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="ride-request__actions-group">
                                <div class="ride-fare"></div>
                                <div class="d-flex flex-wrap gap-3">
                                    <button class="btn btn--base bidConfirmBtn" data-rep="popup"
                                        data-bid_id="{{ 0 }}">@lang('Confirm')</button>
                                    <button class="btn btn-outline--danger bidRejectBtn" data-rep="popup"
                                        data-bid_id="{{ 0 }}">@lang('Reject')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Coupon Modal --}}
    <div class="modal custom--modal fade" id="couponModal" tabindex="-1" aria-labelledby="couponModalLabel"
        aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content couponModalContent">
            </div>
        </div>
    </div>

    {{-- Tips Modal --}}
    <div class="modal custom--modal fade" id="tipsModal" tabindex="-1" aria-labelledby="tipsModalLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="ride-request-banner">
                    <div class="ride-request__header justify-content-between align-items-center">
                        <h5 class="mb-0">
                            @lang('Add Tips')
                        </h5>
                        <button class="ride-request__close-btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                </div>
                <div class="modal-body pt-2">
                    <form class="ride-review-wrapper tipsForm no-submit-loader">
                        <input type="hidden" name="ride_id">
                        <div class="ride-review">
                            <ul class="tips-list">
                                @foreach (gs('tips_suggest_amount') ?? [] as $tipsAmount)
                                    <li style="display:inline-flex; align-items:center;">
                                        <span role="button" class="tipsAmountBtn tips-badge"
                                            data-tips_amount="{{ $tipsAmount }}">{{ gs('cur_sym') . $tipsAmount }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="form-group">
                            <label>@lang('Amount')</label>
                            <div class="input--group input-group">
                                <input class="form--control form-control" type="number" min="0" name="amount"
                                    placeholder="@lang('Custom Amount')">
                                <span class="input-group-text h-49">{{ __(gs('cur_text')) }}</span>
                            </div>
                        </div>
                        <div class="ride-review__btn">
                            <button type="submit"class="btn btn--base w-100">@lang('Continue')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Review Modal --}}
    <div class="modal custom--modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="ride-request-banner">
                    <div class="ride-request__header justify-content-end">
                        <button class="ride-request__close-btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <div class="ride-confirmation__header ride-confirmation__review mb-0">
                        <div class="ride-confirmation__driver-info">
                            <div class="ride-confirmation__driver-info__thumb">
                                <span class="star-rating"><i class="fa-solid fa-star"></i>
                                    <strong></strong></span>
                                <img src="" alt="Driver Avatar" class="driver-avatar">
                            </div>
                            <div class="driver-details">
                                <div class="driver-name"></div>
                                <div class="driver-car-rating">
                                    <span class="car-model"></span>
                                </div>
                            </div>
                        </div>
                        <div class="ride-confirmation__fare text-right">
                            <strong class="fs-18 carModal"></strong>
                        </div>
                    </div>
                </div>
                <div class="modal-body pt-2">
                    <form class="ride-review-wrapper text-center reviewForm">
                        <h6 class="title mb-0">@lang('Rate your experience')</h6>
                        <div class="ride-review">
                            <ul class="star-rating">
                                <li class="active star-rating__item"><i class="fa-solid fa-star"></i></li>
                                <li class="star-rating__item"><i class="fa-solid fa-star"></i></li>
                                <li class="star-rating__item"><i class="fa-solid fa-star"></i></li>
                                <li class="star-rating__item"><i class="fa-solid fa-star"></i></li>
                                <li class="star-rating__item"><i class="fa-solid fa-star"></i></li>
                            </ul>
                        </div>

                        <input type="hidden" name="ride_id" required>
                        <input type="hidden" name="rating" required>

                        <div class="form-group">
                            <label for="" class="form--label text-start d-block">@lang('Review')</label>
                            <textarea name="review" class="form--control" placeholder="@lang('Enter Review')" required></textarea>
                        </div>
                        <div class="ride-review__btn">
                            <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    @include($activeTemplate . 'partials.js-config')
    @include($activeTemplate . 'partials.location_initialization')
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('style')
    <style>
        .ride-confirmation__header {
            justify-content: unset;
            flex-wrap: unset;

        }

        .call__btn {
            height: 48px;
            width: 48px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 16px;
            color: hsl(var(--base));
            background-color: hsl(var(--base) / 0.2);
            flex-shrink: 0;
        }

        .rating__value {
            line-height: 1;
            padding-left: 2px;
        }

        .security-code-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 28px;
            margin: 20px auto;
            flex-direction: row;
            justify-content: start;
            align-items: center;
        }

        .code-digit {
            width: 40px;
            height: 40px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            border: none;
            border-radius: 8px;
            outline: none;
            transition: border-color 0.3s ease;
            background-color: #F4F4F6;
        }


        .security-code-inputs {
            display: flex;
            gap: 6px !important;
        }

        .booking-wrapper__right #map {
            width: 100%;
            height: 850px;
            border-radius: 8px;
        }

        @media screen and (max-width:991px) {
            .booking-wrapper__right #map {
                height: 400px;
            }
        }

        .star-rating__item.active i {
            color: #FFD700;
        }

        .h-49 {
            height: 49px !important;
        }

        .location-distance-box {
            border-radius: 6px;
            padding: 0px;
            min-width: 180px;
        }

        .location-distance-box p {
            font-size: 1rem;
        }

        .location-distance-box strong {
            color: hsl(var(--base))
        }

        .location-distance-box p:not(:last-child) {
            margin-bottom: 5px;
        }

        .ride-request__close-btn {
            border: 1px solid #ddd;
            border-radius: 50%;
            padding: 2px;
            font-size: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .payment-select-wrapper {
            height: 45px;
        }

        .ride-request-banner {
            padding: 24px;
        }

        .driver-car-rating {
            display: flex;
            align-items: center
        }

        .destination-info__tip {
            border-bottom: 1px dashed hsl(var(--border-color));
            margin-bottom: 6px;
            padding-bottom: 6px;
        }

        .destination-info__tip-right .add-tips {
            border: 1px solid #ddd;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            background: hsl(var(--base)/0.2);
            font-size: 14px;
        }

        .tips-badge {
            padding: 6px 18px !important;
            background: hsl(var(--success) / 0.2);
            color: hsl(var(--success));
            border-radius: 8px;
            font-weight: 600;
        }

        .tips-list {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            margin-bottom: 10px;
        }

        .coupon-modal-card {
            background-color: hsl(var(--base)/0.15);
            padding: 16px;
            border-radius: 16px
        }

        .search-input-dropdown__item.highlighted {
            background-color: hsl(var(--base) / 0.05) !important;
            color: white;
        }

        .typing-loader-wrapper {
            height: 70px;
            cursor: default;
            user-select: none;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #6c757d;
        }

        /* dots container */
        .typing-loader {
            display: flex;
            gap: 8px;
            margin-bottom: 6px;
        }

        /* individual dots */
        .typing-loader span {
            width: 12px;
            height: 12px;
            background-color: #6c757d;
            border-radius: 50%;
            animation: typingDot 1.4s infinite ease-in-out both;
        }

        /* delay for each dot */
        .typing-loader span:nth-child(1) {
            animation-delay: 0s;
        }

        .typing-loader span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-loader span:nth-child(3) {
            animation-delay: 0.4s;
        }

        /* animation */
        @keyframes typingDot {
            0% {
                opacity: 0.3;
                transform: translateY(0);
            }

            20% {
                opacity: 1;
                transform: translateY(-4px);
            }

            40% {
                opacity: 0.3;
                transform: translateY(0);
            }

            100% {
                opacity: 0.3;
            }
        }

        /* text below dots */
        .typing-loader--lg span {
            width: 14px;
            height: 14px;
        }

        .typing-loader--lg {
            gap: 10px;
        }

        .typing-text {
            font-size: 0.9rem;
            font-style: italic;
        }

        .fare-loader-wrapper {
            padding: 12px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .fare-loader-wrapper .typing-text {
            font-size: 1rem;
        }

        .typing-loader--button {
            display: flex;
            gap: 4px;
        }

        .typing-loader--button span {
            width: 6px;
            height: 6px;
            background-color: #fff;
            border-radius: 50%;
            animation: typingDot 1.2s infinite ease-in-out both;
        }

        .typing-loader--button span:nth-child(1) {
            animation-delay: 0s;
        }

        .typing-loader--button span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-loader--button span:nth-child(3) {
            animation-delay: 0.4s;
        }

        .search-input-dropdown__no-results {
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: default;
            pointer-events: none;
            color: #888;
            padding: 0.5rem 0;
            font-style: normal;
            width: 100%;
        }

        /* Skeleton animation */
        .skeleton-card,
        .skeleton-text,
        .skeleton-input,
        .skeleton-btn,
        .skeleton-circle {
            background: linear-gradient(90deg, #eee 25%, #f5f5f5 50%, #eee 75%);
            background-size: 200% 100%;
            animation: shimmer 1.2s infinite;
            border-radius: 8px;
        }

        .skeleton-card {
            height: 60px;
        }

        .skeleton-text {
            height: 20px;
            margin-bottom: 10px;
        }

        .skeleton-input {
            height: 40px;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .skeleton-btn {
            height: 40px;
            border-radius: 6px;
        }

        .skeleton-circle {
            width: 24px;
            height: 24px;
            border-radius: 50%;
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }
    </style>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/pusher.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/broadcasting.js') }}"></script>

    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ gs('google_maps_client_api_key') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/ride_files/realtime.js') }}"></script>

    <script src="{{ asset($activeTemplateTrue . 'js/ride_files/map.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/ride_files/ride-cancel.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/ride_files/bid.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/ride_files/coupon.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/ride_files/tips.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/ride_files/payment.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/ride_files/bid-realtime.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/ride_files/review.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            function formatState(state) {
                if (!state.id) {
                    return state.text;
                }
                var image = $(state.element).data('image');
                var $state = $(
                    '<span><img src="' + image + '" class="img-fluid me-2" width="20" height="20" /> ' +
                    state
                    .text + '</span>'
                );
                return $state;
            }

            window.paymentSelectInteraction = () => {
                $('.payment-select').select2({
                    templateResult: formatState,
                    templateSelection: formatState,
                    width: "100%",
                    escapeMarkup: function(m) {
                        return m;
                    }
                });
            };

            window.paymentSelectInteraction();


            $('.star-rating__item').on('click', function() {
                const $stars = $(this).parent().children('.star-rating__item');
                const index = $(this).index() + 1;

                $stars.each(function(i) {
                    if (i < index) {
                        $(this).addClass('active');
                    } else {
                        $(this).removeClass('active');
                    }
                });

                $('input[name="rating"]').val(index);
            });
        })(jQuery);
    </script>
@endpush
