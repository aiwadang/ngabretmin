<div class="common-card mt-4">
    <div class="ride-booking__calculation">
        <div class="security-code-container">
            <label for="securityCode" class="security-code-label">@lang('OTP Code')</label>

            <div class="security-code-inputs">
                @foreach (str_split($ride->otp ?? '') ?? [] as $otpDigit)
                    <input type="text" maxlength="1" value="{{ $otpDigit }}" disabled class="code-digit" />
                @endforeach
            </div>
        </div>

        <div class="ride-booking__calculation-container">
            <div class="ride-booking__calculation-item">
                <div class="ride-booking__value">{{ $ride->distance .' '. (gs('distance_unit') == Status::KM_UNIT ? trans('KM') : trans('Miles'))}}</div>
                <div class="ride-booking__label">@lang('Distance')</div>
            </div>
            <div class="ride-booking__calculation-item">
                <div class="ride-booking__value">{{ $ride->duration }}</div>
                <div class="ride-booking__label">@lang('Estimated Time')</div>
            </div>
            <div class="ride-booking__calculation-item">
                <div class="ride-booking__value">{{ showAmount($ride->amount, currencyFormat:false) .' '. gs('cur_text')}}</div>
                <div class="ride-booking__label">@lang('Ride Fare')</div>
            </div>
        </div>
    </div>

    <div class="ride-confirmation__header mt-4">
        <div class="ride-confirmation__driver-info">
            <div class="ride-confirmation__driver-info__thumb">
                <span class="star-rating"><i class="fa-solid fa-star"></i>
                    <strong>{{ formatRating($ride->driver?->avg_rating) }}</strong></span>
                <img src="{{ getImage(getFilePath('driver') .'/'. $ride->driver?->image, getFileSize('driver'), true) }}" alt="Driver Avatar" class="driver-avatar">
            </div>
            <div class="driver-details">
                <div class="driver-name">{{ __($ride->driver?->fullname) }}</div>
                <div class="driver-car-rating">
                    <span class="car-model">@lang('Ride Completed'): {{ $ride->driver?->ride()->completed()->count() }}</span>
                </div>
            </div>
        </div>
        <div class="ride-confirmation__fare text-right">
            <p class="fs-14"> {{ __($ride->driver?->vehicle?->brand?->name) }}</p>
            <strong class="fs-18">{{ __($ride->driver?->vehicle?->model?->name) }}</strong>
        </div>
    </div>

    <div class="ride-confirmation__header mt-4 w-100">
        <a href="tel:+{{ $ride->driver->mobileNumber }}" class="call__btn w-100 d-flex align-items-center justify-content-center gap-2 py-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 384 384" fill="none">
                <path d="M353.188 252.052c-23.51 0-46.594-3.677-68.469-10.906-10.719-3.656-23.896-.302-30.438 6.417l-43.177 32.594c-50.073-26.729-80.917-57.563-107.281-107.26l31.635-42.052c8.219-8.208 11.167-20.198 7.635-31.448-7.26-21.99-10.948-45.063-10.948-68.583C132.146 13.823 118.323 0 101.333 0h-70.52C13.823 0 0 13.823 0 30.813 0 225.563 158.438 384 353.188 384c16.99 0 30.813-13.823 30.813-30.813v-70.323c-.001-16.989-13.824-30.812-30.813-30.812z" fill="currentColor"/>
            </svg>
            <strong>@lang('Call Driver')</strong>
        </a>
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