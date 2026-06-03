@php
    $rideType     = $ride->ride_type    == Status::CITY_RIDE ? trans('City Ride') : trans('Intercity Ride');
    $service      = trans($ride->service?->name ?? 'N/A');
    $paymentType  = $ride->payment_type == Status::PAYMENT_TYPE_CASH ? trans('Cash Payment') : ($ride->payment_type == Status::PAYMENT_TYPE_GATEWAY ? trans('Online Payment') : trans('Wallet Payment'));
    $distanceUnit = gs('distance_unit') == Status::KM_UNIT ? trans('Kilometers') : trans('Mile');
    $driver       = $ride->driver;

    function generateStars($rating = 0)
    {
        $maxStars = 5;
        $rating = max(0, min(5, $rating));
        $html = '<div class="ride-user__stars">';

        for ($i = 1; $i <= $maxStars; $i++) {
            $isFilled = $i <= floor($rating);

            $html .=
                '
               <span class="star ' .
                ($isFilled ? 'filled' : '') .
                '">
                   <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                       <path
                           d="M7.68323 1.52997C7.71245 1.47094 7.75758 1.42126 7.81353 1.38652C7.86949 1.35178 7.93404 1.33337 7.9999 1.33337C8.06576 1.33337 8.13031 1.35178 8.18626 1.38652C8.24222 1.42126 8.28735 1.47094 8.31656 1.52997L9.85656 4.6493C9.95802 4.85461 10.1078 5.03224 10.293 5.16694C10.4782 5.30164 10.6933 5.38938 10.9199 5.42264L14.3639 5.92664C14.4292 5.93609 14.4905 5.96362 14.5409 6.0061C14.5913 6.04859 14.6289 6.10434 14.6492 6.16704C14.6696 6.22975 14.6721 6.29691 14.6563 6.36093C14.6405 6.42495 14.6071 6.48327 14.5599 6.5293L12.0692 8.95464C11.905 9.1147 11.7821 9.31229 11.7111 9.53039C11.6402 9.74849 11.6233 9.98056 11.6619 10.2066L12.2499 13.6333C12.2614 13.6985 12.2544 13.7657 12.2296 13.8271C12.2048 13.8885 12.1632 13.9417 12.1096 13.9806C12.056 14.0196 11.9926 14.0426 11.9265 14.0472C11.8604 14.0518 11.7944 14.0378 11.7359 14.0066L8.65723 12.388C8.45438 12.2815 8.22868 12.2258 7.99956 12.2258C7.77044 12.2258 7.54475 12.2815 7.3419 12.388L4.2639 14.0066C4.20545 14.0376 4.1395 14.0515 4.07353 14.0468C4.00757 14.0421 3.94424 14.019 3.89076 13.9801C3.83728 13.9412 3.79579 13.8881 3.771 13.8268C3.74622 13.7655 3.73914 13.6984 3.75056 13.6333L4.3379 10.2073C4.3767 9.98112 4.35989 9.7489 4.28892 9.53067C4.21796 9.31243 4.09497 9.11474 3.93056 8.95464L1.4399 6.52997C1.39229 6.48399 1.35856 6.42557 1.34254 6.36135C1.32652 6.29714 1.32886 6.22971 1.34928 6.16676C1.36971 6.10381 1.40741 6.04786 1.45808 6.00529C1.50876 5.96272 1.57037 5.93524 1.6359 5.92597L5.07923 5.42264C5.30607 5.38964 5.52149 5.30201 5.70695 5.16729C5.89242 5.03258 6.04237 4.85482 6.1439 4.6493L7.68323 1.52997Z"
                           fill="#FFCC00" />
                   </svg>
               </span>
           ';
        }

        $html .= '</div>';

        return $html;
    }
@endphp

<div class="ride-request-banner d-flex gap-2 justify-content-between align-items-center">
    <h5 class="modal-title cancel-title">
        {{ trans('Ride') . ' #' . $ride->uid }}
    </h5>

    <div class="ride-request__header justify-content-end mb-0 mt-2">
        @if (in_array($ride->status, [Status::RIDE_PENDING, Status::RIDE_ACTIVE, Status::RIDE_RUNNING, Status::RIDE_END]))
            <a href="{{ route('user.ride.process.step') }}" class="ovo--modal__button">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11 3H17V9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M17 3L10 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path
                        d="M14 11V15C14 16.1046 13.1046 17 12 17H5C3.89543 17 3 16.1046 3 15V8C3 6.89543 3.89543 6 5 6H9"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>@lang('Open Ride')
            </a>
        @endif

        @if ($ride->status !== Status::RIDE_CANCELED)
            <a href="{{ route('user.ride.receipt', $ride->id) }}" class="ovo--modal__button">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 12.5V2.5" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
                    <path
                        d="M17.5 12.5V15.8333C17.5 16.2754 17.3244 16.6993 17.0118 17.0118C16.6993 17.3244 16.2754 17.5 15.8333 17.5H4.16667C3.72464 17.5 3.30072 17.3244 2.98816 17.0118C2.67559 16.6993 2.5 16.2754 2.5 15.8333V12.5"
                        stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
                    <path d="M5.8335 8.33337L10.0002 12.5L14.1668 8.33337" stroke="currentColor" stroke-width="1.5"
                        stroke-linejoin="round" />
                </svg>@lang('Export PDF')
            </a>
        @endif

        <button class="ride-request__close-btn" data-bs-dismiss="modal" aria-label="Close">
            <i class="las la-times"></i>
        </button>
    </div>
</div>

<div class="modal-body">
    <div class="ride-review-main">
        <div class="ride-review-wrapper">
            <div class="ride-review__item">
                <div>
                    <h3 class="ride-review__title">{{ $ride->uid }}</h3>
                </div>
                <div class="ride-tag__wrap">
                    <p class="ride-review__tag">{{ $rideType }}</p>
                    <p class="ride-review__tag">{{ $service }}</p>
                    <p class="ride-review__tag">{{ showDateTime($ride->created_at, 'F d, Y') }}</p>
                    <p class="ride-review__tag">{{ $paymentType }}</p>
                </div>
            </div>

            <div class="ride-info">
                <div class="ride-info__item">
                    <div>
                        <h3 class="ride-info__item__title">
                            {{ gs('cur_sym') . showAmount($ride->amount, currencyFormat: false) }}</h3>
                        <p class="ride-info__item__desc">@lang('Total Fare')</p>
                    </div>
                </div>
                <div>
                    <div class="ride-info_line"></div>
                    <div class="ride-info__item">
                        <h3 class="ride-info__item__title">{{ showAmount($ride->distance, currencyFormat: false) }}</h3>
                        <p class="ride-info__item__desc">{{ $distanceUnit }}</p>
                    </div>
                </div>
                <div class="d-flex gap-12 align-items-center">
                    <div class="ride-info_line"></div>
                    <div class="ride-info__item">
                        <h3 class="ride-info__item__title">{{ $ride->duration }}</h3>
                        <p class="ride-info__item__desc">@lang('Duration')</p>
                    </div>
                </div>
                <div class="d-flex gap-12 align-items-center">
                    @if ($ride->status == Status::RIDE_COMPLETED)
                        <p class="ovo--modal__badge-success"> <span class="badge-dot"></span> @lang('Completed')</p>
                    @elseif($ride->status == Status::RIDE_PENDING)
                        <p class="ovo--modal__badge-warning"> <span class="badge-dot"></span> @lang('Pending')</p>
                    @elseif($ride->status == Status::RIDE_CANCELED)
                        <p class="ovo--modal__badge-danger"> <span class="badge-dot"></span> @lang('Canceled')</p>
                    @elseif($ride->status == Status::RIDE_RUNNING)
                        <p class="ovo--modal__badge-primary"> <span class="badge-dot"></span> @lang('Running')</p>
                    @elseif($ride->status == Status::RIDE_END)
                        <p class="ovo--modal__badge-success"> <span class="badge-dot"></span> @lang('End')</p>
                    @elseif($ride->status == Status::RIDE_ACTIVE)
                        <p class="ovo--modal__badge-primary"> <span class="badge-dot"></span> @lang('Active')</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="ride-bottom">
            <div class="ride-bottom__item">
                <div class="ride-bottom__left">
                    <span class="ride-bottom__subtitle">@lang('Pickup')</span>
                    <h4 class="ride-bottom__title">{{ __($ride->pickup_location) }}</h4>
                    @if ($ride->start_time)
                        <p class="ride-bottom__desc">{{ showDateTime($ride->start_time, 'F d, Y · h:i A') }}</p>
                    @else
                        <p class="ride-bottom__desc">@lang('Start time not available (ride canceled)')</p>
                    @endif
                </div>
            </div>

            <div class="ride-bottom-center">
                <span class="icon-car">

                    <img src="{{ getImage(getFilePath('service') . '/' . $ride->service?->image ?? '', getFileSize('service')) }}"
                        alt="">
                </span>
            </div>
            <div class="ride-bottom__item">
                <div class="ride-bottom__left">
                    <span class="ride-bottom__subtitle">@lang('Drop off')</span>
                    <h4 class="ride-bottom__title">{{ __($ride->destination) }}</h4>
                    @if ($ride->end_time)
                        <p class="ride-bottom__desc">{{ showDateTime($ride->end_time, 'F d, Y · h:i A') }}</p>
                    @else
                        <p class="ride-bottom__desc">@lang('End time not available (ride canceled)')</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="ride-main">
        <div class="ride-user">
            <div class="ride-user__left">
                <div class="ride-user__thumb">
                    <img src="{{ getImage(getFilePath('driver') . '/' . $driver?->image ?? '', getFileSize('driver'), true) }}"
                        alt="image">
                </div>

                <div class="ride-user__info">
                    @if ($ride->status == Status::RIDE_CANCELED)
                        <h3 class="ride-user__title">@lang('Not Available')</h3>
                        <p class="ride-user__desc">
                            @lang('Ride has been canceled')
                        </p>
                    @elseif ($ride->status == Status::RIDE_PENDING)
                        <h3 class="ride-user__title">@lang('Searching for a driver')</h3>
                        <p class="ride-user__desc">
                            @lang('Searching for a new driver for this ride.')
                        </p>
                    @else
                        <h3 class="ride-user__title">{{ __($driver?->fullname) }}</h3>
                        <p class="ride-user__desc">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                viewBox="0 0 16 16" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M7.1183 1.96964C7.5549 1.12129 8.77876 1.12129 9.21536 1.96964L10.6864 4.82826C10.7108 4.87569 10.7566 4.90871 10.8097 4.91708L14.0072 5.42141C14.9556 5.57099 15.3336 6.72191 14.6554 7.39537L12.3663 9.66837C12.3284 9.70604 12.3109 9.75931 12.3193 9.81184L12.8238 12.9842C12.9734 13.9244 11.9834 14.636 11.1272 14.204L8.24316 12.749C8.19523 12.7248 8.13843 12.7248 8.0905 12.749L5.20652 14.204C4.3502 14.636 3.36032 13.9244 3.50985 12.9842L4.01439 9.81184C4.02274 9.75931 4.00527 9.70604 3.96736 9.66837L1.67828 7.39537C1.00012 6.72191 1.37804 5.57099 2.32644 5.42141L5.52394 4.91708C5.57703 4.90871 5.62288 4.87569 5.64729 4.82826L7.1183 1.96964Z"
                                    fill="#F59E0B" />
                            </svg> <span class="fw-700">{{ formatRating($driver?->avg_rating ?? 0) }}</span>
                            {{ __($driver?->vehicle?->model?->name ?? 'N/A') }}
                        </p>
                    @endif
                </div>
            </div>
            @if ($ride->driverReview)
                <div class="ride-user__rating">
                    <div class="ride-user__rating-row">
                        <span class="ride-user__score">{{ $ride->driverReview?->rating ?? 0 }}</span>
                        @php echo generateStars($ride->driverReview->rating); @endphp
                    </div>
                    <span class="ride-user__hint">@lang('Out of 5 Stars')</span>
                </div>
            @else
                <div class="ride-user__rating">
                    <div class="ride-user__rating-row">
                        @php echo generateStars(0); @endphp
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>


