<div class="ride-request-banner">
    <div class="ride-request__header justify-content-between">
        <h5 class="mb-0">@lang('Apply Coupon')</h5>
        <button class="ride-request__close-btn" data-bs-dismiss="modal" aria-label="Close">
            <i class="las la-times"></i>
        </button>
    </div>
</div>
<div class="modal-body pt-2 ">
    <form class="no-submit-loader couponApplyForm">
        <div class="ride-review-wrapper text-center">
            <input type="hidden" name="ride_id" value="{{ $ride->id }}">

            <div class="form-group">
                <input class="form--control" name="code" type="text" placeholder="@lang('Coupon code')" required
                    value="{{ old('code') }}">
            </div>
            <div class="ride-review__btn">
                <button type="submit" class="btn btn--base w-100">@lang('Apply')</button>
            </div>
        </div>
    </form>

        @foreach ($coupons as $coupon)
            <div class="ride-confirmation__header mt-3 ride-confirmation__review coupon-modal-card mb-0 border-0">
                <div class="ride-confirmation__driver-info gap-2">
                    <div class="ride-confirmation__driver-info__thumb">
                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24" x="0" y="0"
                            viewBox="0 0 512.003 512.003" style="enable-background:new 0 0 512 512" xml:space="preserve"
                            class="">
                            <g>
                                <path
                                    d="M477.958 262.633a15.004 15.004 0 0 1 0-13.263l19.096-39.065c10.632-21.751 2.208-47.676-19.178-59.023l-38.41-20.38a15.005 15.005 0 0 1-7.796-10.729l-7.512-42.829c-4.183-23.846-26.241-39.87-50.208-36.479l-43.053 6.09a15.004 15.004 0 0 1-12.613-4.099l-31.251-30.232c-17.401-16.834-44.661-16.835-62.061 0L193.72 42.859a15.01 15.01 0 0 1-12.613 4.099l-43.053-6.09c-23.975-3.393-46.025 12.633-50.208 36.479l-7.512 42.827a15.008 15.008 0 0 1-7.795 10.73l-38.41 20.38c-21.386 11.346-29.81 37.273-19.178 59.024l19.095 39.064a15.004 15.004 0 0 1 0 13.263L14.95 301.699c-10.632 21.751-2.208 47.676 19.178 59.023l38.41 20.38a15.005 15.005 0 0 1 7.796 10.729l7.512 42.829c3.808 21.708 22.422 36.932 43.815 36.93 2.107 0 4.245-.148 6.394-.452l43.053-6.09a15 15 0 0 1 12.613 4.099l31.251 30.232c8.702 8.418 19.864 12.626 31.03 12.625 11.163-.001 22.332-4.209 31.03-12.625l31.252-30.232c3.372-3.261 7.968-4.751 12.613-4.099l43.053 6.09c23.978 3.392 46.025-12.633 50.208-36.479l7.513-42.827a15.008 15.008 0 0 1 7.795-10.73l38.41-20.38c21.386-11.346 29.81-37.273 19.178-59.024l-19.096-39.065zM196.941 123.116c29.852 0 54.139 24.287 54.139 54.139s-24.287 54.139-54.139 54.139-54.139-24.287-54.139-54.139 24.287-54.139 54.139-54.139zm-27.944 240.77c-2.883 2.883-6.662 4.325-10.44 4.325s-7.558-1.441-10.44-4.325c-5.766-5.766-5.766-15.115 0-20.881l194.889-194.889c5.765-5.766 15.115-5.766 20.881 0s5.766 15.115 0 20.881l-194.89 194.889zm146.064 25.002c-29.852 0-54.139-24.287-54.139-54.139s24.287-54.139 54.139-54.139c29.852 0 54.139 24.287 54.139 54.139s-24.287 54.139-54.139 54.139z"
                                    fill="#000000" opacity="1" data-original="#000000" class=""></path>
                                <path
                                    d="M315.061 310.141c-13.569 0-24.609 11.039-24.609 24.608s11.039 24.608 24.609 24.608c13.569 0 24.608-11.039 24.608-24.608s-11.039-24.608-24.608-24.608zM196.941 152.646c-13.569 0-24.608 11.039-24.608 24.608s11.039 24.609 24.608 24.609 24.609-11.039 24.609-24.609c-.001-13.568-11.04-24.608-24.609-24.608z"
                                    fill="#000000" opacity="1" data-original="#000000" class=""></path>
                            </g>
                        </svg>
                    </div>
                    <div class="driver-details">
                        <div class="driver-name">@lang('Miss you! here\'s ') @lang('off')</div>
                        <div class="driver-car-rating">
                            <span class="text--base">@lang('Min Spend')
                                {{ gs('cur_sym') . showAmount($coupon->minimum_amount, currencyFormat: false) }}
                                @lang('Ex on')
                                {{ showDateTime($coupon->end_at, 'Y-m-d') }}</span>
                        </div>
                    </div>
                </div>
                <div class="ride-confirmation__fare text-right">
                    <button class="btn btn--success btn--sm"><strong class="couponApplyBtn" data-coupon_code="{{ $coupon->code }}"
                            data-ride_id="{{ $ride->id }}">@lang('Apply')</strong></button>
                </div>
            </div>
        @endforeach
</div>
