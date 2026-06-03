<div class="booking-location-box common-card mb-4">
    <h6 class="title">@lang('Where to Go') ?</h6>
    <div class="booking-location-box__form">
        <div class="booking-location-box__inner">
            <div class="input-group-custom">
                <span class="input-group-custom__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none">
                        <g clip-path="url(#clip0_2291_394)">
                            <path
                                d="M20.5137 12C20.5137 16.6944 16.7081 20.5 12.0137 20.5C7.31925 20.5 3.51367 16.6944 3.51367 12C3.51367 7.30558 7.31925 3.5 12.0137 3.5C16.7081 3.5 20.5137 7.30558 20.5137 12Z"
                                stroke="hsl(var(--base))" stroke-width="2" />
                            <path d="M22.5 12H20.5" stroke="hsl(var(--base))" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M3.5 12H1.5" stroke="hsl(var(--base))" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M12 1.5V3.5" stroke="hsl(var(--base))" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M12 20.5V22.5" stroke="hsl(var(--base))" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path opacity="0.4"
                                d="M15 12C15 13.6569 13.6568 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6568 9 15 10.3431 15 12Z"
                                stroke="hsl(var(--base))" stroke-width="2" />
                        </g>
                        <defs>
                            <clipPath id="clip0_2291_394">
                                <rect width="24" height="24" fill="white" />
                            </clipPath>
                        </defs>
                    </svg>
                </span>
                <div class="input-group-custom__wrapper">
                    <input type="text" class="input-group-custom__input form--control" value="{{ __($runningRide->pickup_location) }}" readonly> 
                </div>
            </div>
            <div class="input-group-custom">
                <span class="input-group-custom__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none">
                        <path
                            d="M14.5 9C14.5 10.3807 13.3807 11.5 12 11.5C10.6193 11.5 9.5 10.3807 9.5 9C9.5 7.61929 10.6193 6.5 12 6.5C13.3807 6.5 14.5 7.61929 14.5 9Z"
                            stroke="hsl(var(--base))" stroke-width="2" />
                        <path
                            d="M13.2574 17.4936C12.9201 17.8184 12.4693 18 12.0002 18C11.531 18 11.0802 17.8184 10.7429 17.4936C7.6543 14.5008 3.51519 11.1575 5.53371 6.30373C6.6251 3.67932 9.24494 2 12.0002 2C14.7554 2 17.3752 3.67933 18.4666 6.30373C20.4826 11.1514 16.3536 14.5111 13.2574 17.4936Z"
                            stroke="hsl(var(--base))" stroke-width="2" />
                        <path opacity="0.4" d="M18 20C18 21.1046 15.3137 22 12 22C8.68629 22 6 21.1046 6 20"
                            stroke="hsl(var(--base))" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </span>
                <div class="input-group-custom__wrapper">
                    <input type="text" class="input-group-custom__input form--control" value="{{ __($runningRide->destination) }}" readonly>
                </div>
            </div>
        </div>
    </div>
</div>
