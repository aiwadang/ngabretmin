@extends($activeTemplate . 'layouts.auth')
@section('auth')
    <div class="driver-dashboard-content">
        <div class="mb-3">
            <h6 class="title mb-1">
                @lang('Payment History')
            </h6>
            <p class="subtitle">
                @lang('Track and review all your payment transactions easily.')
            </p>
        </div>
        <div class="payment-history-wrapper">
            <div class="accordion custom--accordion payment-history-card" id="accordionExample">
                <!-- Item 1 -->
                @forelse ($deposits as $deposit)
                    <div class="accordion-item p-0 mb-3">
                        <div class="accordion-header">
                            <div class="accordion-button payment-history__item border-0 @if (!$loop->first) collapsed @endif"
                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $loop->iteration }}"
                                aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                aria-controls="collapse{{ $loop->iteration }}">
                                <div class="payment-history__item-left">
                                    <h4 class="payment-history__item-amount mb-0">
                                        {{ gs('cur_sym') }}{{ showAmount($deposit->amount, currencyFormat: false) }}</h4>
                                    <span class="payment-history__item-code">{{ $deposit->ride?->uid }}</span>
                                </div>
                                <div class="payment-history__item-right">
                                    <button
                                        class="btn {{ $deposit->payment_type == Status::PAYMENT_TYPE_CASH ? 'btn-outline--success' : 'btn-outline--info' }} btn--sm"
                                        type="button">{{ $deposit->payment_type == Status::PAYMENT_TYPE_CASH ? trans('Cash Payment') : trans('Online Payment') }}</button>
                                    <p class="date">{{ showDateTime($deposit->created_at, 'd F Y, h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                        <div id="collapse{{ $loop->iteration }}"
                            class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="row gy-3">
                                    <div class="col-sm-6">
                                        <div class="trip-card__location trip-card__pickup">
                                            <div class="trip-card__location-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 20 20" fill="none">
                                                    <g clip-path="url(#clip0_2385_1023)">
                                                        <path
                                                            d="M17.0944 10.0001C17.0944 13.9121 13.9231 17.0834 10.0111 17.0834C6.09905 17.0834 2.92773 13.9121 2.92773 10.0001C2.92773 6.08806 6.09905 2.91675 10.0111 2.91675C13.9231 2.91675 17.0944 6.08806 17.0944 10.0001Z"
                                                            stroke="#F59E0B" stroke-width="1.5"></path>
                                                        <path d="M18.7507 10H17.084" stroke="#F59E0B" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M2.91667 10H1.25" stroke="#F59E0B" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M10 1.25V2.91667" stroke="#F59E0B" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M10 17.0833V18.7499" stroke="#F59E0B" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path opacity="0.4"
                                                            d="M12.5 10C12.5 11.3807 11.3807 12.5 10 12.5C8.61929 12.5 7.5 11.3807 7.5 10C7.5 8.61925 8.61929 7.5 10 7.5C11.3807 7.5 12.5 8.61925 12.5 10Z"
                                                            stroke="#F59E0B" stroke-width="1.5"></path>
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_2385_1023">
                                                            <rect width="20" height="20" fill="white"></rect>
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                            </div>
                                            <div class="trip-card__location-info">
                                                <h4 class="trip-card__location-title">@lang('Pickup Location')</h4>
                                                <p class="trip-card__address">{{ __($deposit->ride?->pickup_location) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="trip-card__location trip-card__destination">
                                            <div class="trip-card__location-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 20 20" fill="none">
                                                    <path
                                                        d="M12.0827 7.50008C12.0827 8.65066 11.1499 9.58341 9.99935 9.58341C8.84877 9.58341 7.91602 8.65066 7.91602 7.50008C7.91602 6.34949 8.84877 5.41675 9.99935 5.41675C11.1499 5.41675 12.0827 6.34949 12.0827 7.50008Z"
                                                        stroke="#3B82F6" stroke-width="1.5"></path>
                                                    <path
                                                        d="M11.0472 14.5781C10.7661 14.8487 10.3904 15.0001 9.99952 15.0001C9.60852 15.0001 9.23285 14.8487 8.95177 14.5781C6.37793 12.0841 2.92867 9.298 4.61077 5.25319C5.52027 3.06618 7.70347 1.66675 9.99952 1.66675C12.2955 1.66675 14.4787 3.06619 15.3882 5.25319C17.0682 9.29291 13.6273 12.0927 11.0472 14.5781Z"
                                                        stroke="#3B82F6" stroke-width="1.5"></path>
                                                    <path opacity="0.4"
                                                        d="M15 16.6667C15 17.5872 12.7614 18.3334 10 18.3334C7.23857 18.3334 5 17.5872 5 16.6667"
                                                        stroke="#3B82F6" stroke-width="1.5" stroke-linecap="round"></path>
                                                </svg>
                                            </div>
                                            <div class="trip-card__location-info">
                                                <h4 class="trip-card__location-title">@lang('Destination')</h4>
                                                <p class="trip-card__address">{{ __($deposit->ride?->destination) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="trip-card__location trip-card__pickup">
                                            <div class="trip-card__location-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20"
                                                    x="0" y="0" viewBox="0 0 512 512"
                                                    style="enable-background:new 0 0 512 512" xml:space="preserve"
                                                    class="">
                                                    <g>
                                                        <path
                                                            d="M343.91 113a107.64 107.64 0 0 0-107.53 107.53c0 57.14 98 199.58 102.13 205.62a6.58 6.58 0 0 0 10.81 0c4.17-6 102.1-148.48 102.1-205.62A107.63 107.63 0 0 0 343.91 113zm40 83.79a40 40 0 1 1-40-40 40 40 0 0 1 39.98 40.02zM286.8 439.63c-48 0-180-3.28-215.78-38.43-6.93-6.8-10.44-14.57-10.44-23.11 0-31 41.11-40.29 80.87-49.25 31.16-7 66.48-15 66.48-30.86 0-10.56-32.63-21.21-61.91-24.17a9.85 9.85 0 1 1 2-19.61c13.29 1.33 79.64 9.71 79.64 43.78 0 31.64-41.6 41-81.85 50.08-30.69 6.92-65.5 14.76-65.5 30 0 2 .48 5.05 4.54 9C115 416.76 252 420.8 302.25 419.79a9.86 9.86 0 0 1 .39 19.71c-2.64.05-8.19.13-15.84.13zM130.22 72.37a58.34 58.34 0 0 0-58.28 58.26c0 29.75 47.47 99.28 52.88 107.11a6.55 6.55 0 0 0 10.8 0c5.42-7.83 52.86-77.37 52.86-107.11a58.32 58.32 0 0 0-58.26-58.26zm0 68.8a22.68 22.68 0 1 1 22.67-22.69 22.69 22.69 0 0 1-22.67 22.69z"
                                                            fill="hsl(var(--base))" opacity="1"
                                                            data-original="hsl(var(--base))"></path>
                                                    </g>
                                                </svg>
                                            </div>
                                            <div class="trip-card__location-info">
                                                <h4 class="trip-card__location-title">@lang('Distance')</h4>
                                                <p class="trip-card__address">
                                                    {{ showAmount($deposit->ride?->distance, currencyFormat: false) }}
                                                    {{ gs('distance_unit') == Status::KM_UNIT ? trans('KM') : trans('Miles') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="trip-card__location trip-card__destination">
                                            <div class="trip-card__location-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="20"
                                                    height="20" x="0" y="0" viewBox="0 0 32 32"
                                                    style="enable-background:new 0 0 512 512" xml:space="preserve"
                                                    class="">
                                                    <g>
                                                        <path
                                                            d="M16 2C8.28 2 2 8.28 2 16s6.28 14 14 14 14-6.28 14-14S23.72 2 16 2zm0 26C9.38 28 4 22.62 4 16S9.38 4 16 4s12 5.38 12 12-5.38 12-12 12zm6-12c0 .55-.45 1-1 1h-5c-.55 0-1-.45-1-1V7c0-.55.45-1 1-1s1 .45 1 1v8h4c.55 0 1 .45 1 1z"
                                                            fill="hsl(var(--info))" opacity="1"
                                                            data-original="hsl(var(--info))" class=""></path>
                                                    </g>
                                                </svg>
                                            </div>
                                            <div class="trip-card__location-info">
                                                <h4 class="trip-card__location-title">@lang('Duration')</h4>
                                                <p class="trip-card__address">{{ $deposit->ride?->duration }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    @include($activeTemplate . 'partials.no_data')
                @endforelse

            </div>

            @if ($deposits->hasPages())
                {{ paginateLinks($deposits) }}
            @endif
        </div>

    </div>
@endsection
