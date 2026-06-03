<div class="common-card mt-4">
    <div class="ride-booking__calculation">
        <div class="heading text-center">
            <h6 class="title mb-0">@lang('Thanks for ride complete')</h6>
            <p class="subtitle fs-14">@lang('Please wait for driver confirmation')</p>
        </div>
        <div class="ride-booking__calculation-container">
            <div class="ride-booking__calculation-item">
                <div class="ride-booking__value">{{ $runningRide->distance .' '. (gs('distance_unit') == Status::KM_UNIT ? trans('KM') : trans('Miles'))}}</div>
                <div class="ride-booking__label">@lang('Distance')</div>
            </div>
            <div class="ride-booking__calculation-item">
                <div class="ride-booking__value">{{ $runningRide->duration }}</div>
                <div class="ride-booking__label">@lang('Estimated Time')</div>
            </div>
            <div class="ride-booking__calculation-item">
                <div class="ride-booking__value">{{ showAmount($runningRide->amount, currencyFormat:false) .' '. gs('cur_text')}}</div>
                <div class="ride-booking__label">@lang('Ride Fare')</div>
            </div>
        </div>
    </div>

    <div class="ride-confirmation__header mt-4">
        <div class="ride-confirmation__driver-info">
            <div class="ride-confirmation__driver-info__thumb">
                <span class="star-rating"><i class="fa-solid fa-star"></i>
                    <strong>{{ formatRating($runningRide->driver?->avg_rating) }}</strong></span>
                <img src="{{ getImage(getFilePath('driver') .'/'. $runningRide->driver?->image, getFileSize('driver'), true) }}" alt="Driver Avatar" class="driver-avatar">
            </div>
            <div class="driver-details">
                <div class="driver-name">{{ __($runningRide->driver?->fullname) }}</div>
                <div class="driver-car-rating">
                    <span class="car-model">@lang('Ride Completed'): {{ $runningRide->driver?->ride()->completed()->count() }}</span>
                </div>
            </div>
        </div>
        <div class="ride-confirmation__fare text-right">
            <p class="fs-14"> {{ __($runningRide->driver?->vehicle?->brand?->name) }}</p>
            <strong class="fs-18">{{ __($runningRide->driver?->vehicle?->model?->name) }}</strong>
        </div>
    </div>

    <div class="ride-pickup__inner">
        <div class="pulse-container-pro">
            <div class="pulse-ring-pro pulse-ring-4"></div>
            <div class="pulse-ring-pro pulse-ring-3"></div>
            <div class="pulse-ring-pro pulse-ring-2"></div>
            <div class="pulse-ring-pro pulse-ring-1"></div>
            <div class="pulse-core-pro"></div>
        </div>
        <p class="text-center fs-15">@lang('Wait for driver response')</p>
    </div>
</div>