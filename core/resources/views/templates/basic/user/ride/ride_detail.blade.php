@if ($runningRide && $runningRide->status == Status::RIDE_PENDING)    
    <div class="common-card mt-4 rideDetailDiv">
        <div class="progress_item">
            <div class="progress">
                <div class="progress_bar wow" style="--bar-width:100%">
                    <span class="value_text">
                        <svg width="40" height="14" viewBox="0 0 40 14" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M1.17482 4.36187C1.42508 3.97855 1.84218 3.74856 2.25927 3.67189C4.59501 3.36524 7.68152 1.60198 9.09964 1.29533C12.1861 0.60536 19.9441 -0.621254 22.8638 0.37537C27.6187 2.13863 28.7866 3.36524 29.6207 3.67189C32.6238 3.97855 35.2932 4.51519 37.7124 5.20516C38.0461 5.35849 38.2963 5.51181 38.5466 5.7418C39.7145 6.89176 40.1316 8.2717 39.9647 9.88163C39.7979 11.0316 38.7134 11.8749 37.4621 11.8749C37.629 11.4149 37.7124 10.9549 37.7124 10.4949C37.7124 7.88838 35.3767 5.7418 32.5404 5.7418C29.7042 5.7418 27.3684 7.88838 27.3684 10.4949C27.3684 10.9549 27.4518 11.4916 27.6187 11.8749H12.8535C13.0203 11.4149 13.1038 10.9549 13.1038 10.4949C13.1038 7.88838 10.768 5.7418 7.93177 5.7418C5.09552 5.7418 2.75979 7.88838 2.75979 10.4949C2.75979 10.9549 2.8432 11.3382 2.92662 11.7215C2.34269 11.5682 1.67534 11.4149 0.924566 11.0316C0.507471 10.8016 0.257212 10.4183 0.173793 9.95829C-0.243303 8.04171 0.0903743 6.12512 1.17482 4.36187ZM19.1099 3.97855L26.5342 4.20854L27.1182 4.05521C25.7 3.21191 24.1151 2.52194 22.2799 1.83197C22.1964 1.83197 22.113 1.75531 22.0296 1.75531C21.112 1.60198 20.111 1.60198 19.1099 1.60198V3.97855ZM10.2675 3.67189L17.4416 3.90188V1.67865C15.1058 1.75531 12.6032 2.13863 9.76699 2.75193L10.2675 3.67189Z"
                                fill="hsl(var(--base))" />
                            <path
                                d="M35.9609 10.495C35.9609 8.73175 34.376 7.27515 32.4573 7.27515C30.5387 7.27515 28.9537 8.73175 28.9537 10.495C28.9537 12.2583 30.5387 13.7149 32.4573 13.7149C34.376 13.7149 35.9609 12.2583 35.9609 10.495Z"
                                fill="hsl(var(--base))" />
                            <path
                                d="M4.26135 10.495C4.26135 12.2583 5.84631 13.7149 7.76495 13.7149C9.68359 13.7149 11.2686 12.2583 11.2686 10.495C11.2686 8.73175 9.68359 7.27515 7.76495 7.27515C5.84631 7.27515 4.26135 8.73175 4.26135 10.495Z"
                                fill="hsl(var(--base))" />
                        </svg>
                    </span>
                </div>
            </div>
        </div>

        <div class="ride-booking__calculation mt-3">
            <div class="heading text-center">
                <h6 class="title mb-0">@lang('Searching for Driver')</h6>
                <p class="subtitle fs-14">@lang('It may take some times')</p>
            </div>
            <div class="ride-booking__calculation-container">
                <div class="ride-booking__calculation-item">
                    <div class="ride-booking__value"><span class="rideDistance">{{ $runningRide?->distance }}</span> {{ gs('distance_unit') == Status::KM_UNIT ? trans('KM') : trans('Miles') }}</div>
                    <div class="ride-booking__label">@lang('Distance')</div>
                </div>
                <div class="ride-booking__calculation-item">
                    <div class="ride-booking__value"><span class="rideDuration">{{ $runningRide?->duration }}</span></div>
                    <div class="ride-booking__label">@lang('Estimated Time')</div>
                </div>
                <div class="ride-booking__calculation-item">
                    <div class="ride-booking__value">
                        <span class="rideAmount">{{ showAmount($runningRide?->amount ?? 0, currencyFormat: false) }}</span> {{ gs('cur_text') }}</div>
                    <div class="ride-booking__label">@lang('Ride Fare')</div>
                </div>
            </div>
            <button class="btn btn--base mt-3 w-100 rideCancelBtn"
                data-ride_id="{{ $runningRide?->id }}">@lang('Cancel Ride')</button>
        </div>
    </div>
@endif