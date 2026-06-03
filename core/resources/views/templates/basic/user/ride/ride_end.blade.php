<div class="common-card mt-4">
    <div class="destination-info">
        <div class="flex-wrap justify-content-between align-items-center">
            @if ($runningRide->applied_coupon_id)
                <div class="destination-info__tip-left d-flex justify-content-between gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                        width="24" height="24" x="0" y="0" viewBox="0 0 32 32"
                        style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                        <g>
                            <g data-name="Layer 2">
                                <path
                                    d="M3 7h2v20.48A3.53 3.53 0 0 0 8.52 31h15A3.53 3.53 0 0 0 27 27.48V7h2a1 1 0 0 0 0-2H3a1 1 0 0 0 0 2zm22 0v20.48A1.52 1.52 0 0 1 23.48 29h-15A1.52 1.52 0 0 1 7 27.48V7zM12 3h8a1 1 0 0 0 0-2h-8a1 1 0 0 0 0 2z"
                                    fill="#000000" opacity="1" data-original="#000000" class=""></path>
                                <path
                                    d="M12.68 25a1 1 0 0 0 1-1V12a1 1 0 0 0-2 0v12a1 1 0 0 0 1 1zM19.32 25a1 1 0 0 0 1-1V12a1 1 0 0 0-2 0v12a1 1 0 0 0 1 1z"
                                    fill="#000000" opacity="1" data-original="#000000" class=""></path>
                            </g>
                        </g>
                    </svg>
                    <strong class="strong">@lang('Remove Coupon')</strong>
                </div>
                <div class="destination-info__tip-right">

                    <button class="btn btn-outline--warning btn--sm removeCouponBtn"
                        data-ride_id="{{ $runningRide->id }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                            fill="none">
                            <g clip-path="url(#clip0_2340_2768)">
                                <path
                                    d="M18.3333 10.0001C18.3333 5.39771 14.6023 1.66675 9.99996 1.66675C5.39758 1.66675 1.66663 5.39771 1.66663 10.0001C1.66663 14.6024 5.39758 18.3334 9.99996 18.3334C14.6023 18.3334 18.3333 14.6024 18.3333 10.0001Z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path opacity="0.4" d="M13.3333 10.0001H6.66663" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </g>
                            <defs>
                                <clipPath id="clip0_2340_2768">
                                    <rect width="20" height="20" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>

                        @lang('Remove')
                    </button>
                </div>
            @else
                <div class="destination-info__tip-left d-flex gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                        width="24" height="24" x="0" y="0" viewBox="0 0 512.003 512.003"
                        style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                        <g>
                            <path
                                d="M477.958 262.633a15.004 15.004 0 0 1 0-13.263l19.096-39.065c10.632-21.751 2.208-47.676-19.178-59.023l-38.41-20.38a15.005 15.005 0 0 1-7.796-10.729l-7.512-42.829c-4.183-23.846-26.241-39.87-50.208-36.479l-43.053 6.09a15.004 15.004 0 0 1-12.613-4.099l-31.251-30.232c-17.401-16.834-44.661-16.835-62.061 0L193.72 42.859a15.01 15.01 0 0 1-12.613 4.099l-43.053-6.09c-23.975-3.393-46.025 12.633-50.208 36.479l-7.512 42.827a15.008 15.008 0 0 1-7.795 10.73l-38.41 20.38c-21.386 11.346-29.81 37.273-19.178 59.024l19.095 39.064a15.004 15.004 0 0 1 0 13.263L14.95 301.699c-10.632 21.751-2.208 47.676 19.178 59.023l38.41 20.38a15.005 15.005 0 0 1 7.796 10.729l7.512 42.829c3.808 21.708 22.422 36.932 43.815 36.93 2.107 0 4.245-.148 6.394-.452l43.053-6.09a15 15 0 0 1 12.613 4.099l31.251 30.232c8.702 8.418 19.864 12.626 31.03 12.625 11.163-.001 22.332-4.209 31.03-12.625l31.252-30.232c3.372-3.261 7.968-4.751 12.613-4.099l43.053 6.09c23.978 3.392 46.025-12.633 50.208-36.479l7.513-42.827a15.008 15.008 0 0 1 7.795-10.73l38.41-20.38c21.386-11.346 29.81-37.273 19.178-59.024l-19.096-39.065zM196.941 123.116c29.852 0 54.139 24.287 54.139 54.139s-24.287 54.139-54.139 54.139-54.139-24.287-54.139-54.139 24.287-54.139 54.139-54.139zm-27.944 240.77c-2.883 2.883-6.662 4.325-10.44 4.325s-7.558-1.441-10.44-4.325c-5.766-5.766-5.766-15.115 0-20.881l194.889-194.889c5.765-5.766 15.115-5.766 20.881 0s5.766 15.115 0 20.881l-194.89 194.889zm146.064 25.002c-29.852 0-54.139-24.287-54.139-54.139s24.287-54.139 54.139-54.139c29.852 0 54.139 24.287 54.139 54.139s-24.287 54.139-54.139 54.139z"
                                fill="#000000" opacity="1" data-original="#000000" class=""></path>
                            <path
                                d="M315.061 310.141c-13.569 0-24.609 11.039-24.609 24.608s11.039 24.608 24.609 24.608c13.569 0 24.608-11.039 24.608-24.608s-11.039-24.608-24.608-24.608zM196.941 152.646c-13.569 0-24.608 11.039-24.608 24.608s11.039 24.609 24.608 24.609 24.609-11.039 24.609-24.609c-.001-13.568-11.04-24.608-24.609-24.608z"
                                fill="#000000" opacity="1" data-original="#000000" class=""></path>
                        </g>
                    </svg>
                    <strong class="strong">@lang('Add Coupon Code')</strong>
                </div>
                <div class="destination-info__tip-right">
                    <button class="btn btn-outline--base btn--sm couponModalBtn" data-ride_id="{{ $runningRide->id }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                            fill="none">
                            <g clip-path="url(#clip0_2340_2768)">
                                <path
                                    d="M18.3333 10.0001C18.3333 5.39771 14.6023 1.66675 9.99996 1.66675C5.39758 1.66675 1.66663 5.39771 1.66663 10.0001C1.66663 14.6024 5.39758 18.3334 9.99996 18.3334C14.6023 18.3334 18.3333 14.6024 18.3333 10.0001Z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path opacity="0.4" d="M9.99996 6.66675V13.3334M13.3333 10.0001H6.66663"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </g>
                            <defs>
                                <clipPath id="clip0_2340_2768">
                                    <rect width="20" height="20" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                        @lang('Add')
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>


<form method="POST" action="{{ route('user.ride.payment.save') }}" class="common-card mt-4 ridePaymentForm">
    @csrf

    <div class="destination-info">
        <span class="destination-info__btn d-block text-center">
            @lang('Ride Summery')!
        </span>
        <div class="row g-2">
            <div class="destination-info__tip flex-wrap justify-content-between align-items-center">
                <div class="destination-info__tip-left">
                    <strong class="strong">@lang('Ride Amount')</strong>
                </div>
                <div class="destination-info__tip-right">
                    <strong>{{ gs('cur_sym') . showAmount($runningRide->amount, currencyFormat: false) }}</strong>
                </div>
            </div>
            <div class="destination-info__tip flex-wrap justify-content-between align-items-center">
                <div class="destination-info__tip-left">
                    <strong class="strong">@lang('Discount')</strong>
                </div>
                <div class="destination-info__tip-right">
                    <strong>{{ gs('cur_sym') . showAmount($runningRide->discount_amount, currencyFormat: false) }}</strong>
                </div>
            </div>
            <div class="destination-info__tip flex-wrap justify-content-between align-items-center">
                @if ($runningRide->tips_amount > 0)
                    <div class="destination-info__tip-left">
                        <strong class="strong">@lang('Tips')</strong>
                    </div>
                    <div class="destination-info__tip-right">
                        <strong>{{ gs('cur_sym') . showAmount($runningRide->tips_amount, currencyFormat: false) }}</strong>
                        <button class="removeTipsButton add-tips" data-ride_id="{{ $runningRide->id }}">
                            <span class="text--warning"><i class="fa-solid fa-minus"></i></span>
                        </button>
                    </div>
                @else
                    <div class="destination-info__tip-left">
                        <strong class="strong">@lang('Tips')</strong>
                    </div>
                    <div class="destination-info__tip-right">
                        <button class="addTipsButton add-tips" data-ride_id="{{ $runningRide->id }}">
                            <span><i class="fa-solid fa-plus"></i></span>
                        </button>
                    </div>
                @endif
            </div>
            <div class="destination-info__tip flex-wrap justify-content-between align-items-center">
                <div class="destination-info__tip-left">
                    <strong class="strong">@lang('Payable Amount')</strong>
                </div>
                <div class="destination-info__tip-right">
                    <strong>{{ gs('cur_sym') . showAmount($runningRide->amount - $runningRide->discount_amount + $runningRide->tips_amount, currencyFormat: false) }}</strong>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="currency">
    <input type="hidden" name="payment_type">
    <input type="hidden" name="ride_id" value="{{ $runningRide->id }}">
    <input type="hidden" name="ride_amount"
        value="{{ $runningRide->amount - $runningRide->discount_amount + $runningRide->tips_amount }}">

    <div class="ride-confirmation__header mt-3">

        <div class="payment-select-wrapper w-100 pt-2">
            <select class="payment-select form-select form--control" name="method_code">
                <option value="cash" data-image="{{ getImage('assets/images/payment.png') }}">
                    @lang('Cash payment')
                </option>
                @foreach ($methods as $methodCurrency)
                    <option value="{{ $methodCurrency->method_code }}"
                        data-method_currency="{{ $methodCurrency->currency }}"
                        data-min_amount="{{ $methodCurrency->min_amount }}"
                        data-max_amount="{{ $methodCurrency->max_amount }}"
                        data-image="{{ getImage(getFilePath('gateway') . '/' . $methodCurrency?->method?->image, getFileSize('gateway')) }}">
                        {{ __($methodCurrency->name) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <button class="btn btn--base w-100" type="submit">@lang('Payment')</button>
</form>
