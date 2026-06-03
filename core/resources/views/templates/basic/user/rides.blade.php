@extends($activeTemplate . 'layouts.auth')
@section('auth')
    <div class="driver-dashboard-content">
        <div class="d-flex justify-content-between align-items-center mb-3 gap-3 flex-wrap">
            <div>
                <h6 class="title mb-1">
                    @lang('Ride List')
                </h6>
                <p class="subtitle">
                    @lang('View and manage all your rides in one place.')
                </p>
            </div>
            <a href="{{ route('user.ride.process.step') }}" class="btn btn--base">
                @if ($runningRideExists)
                    <i class="las la-redo-alt"></i> @lang('View Running Ride')
                @else
                    <i class="las la-car"></i> @lang('Start New Ride')
                @endif
            </a>
        </div>
        <div class="trip-card__wrapper mt-4">
            @forelse ($rides as $ride)
                <div class="trip-card">
                    <div class="trip-card__header">
                        @if ($ride->status == Status::RIDE_PENDING)
                            <span class="trip-card__status badge badge-outline--primary">@lang('Pending')</span>
                        @elseif ($ride->status == Status::RIDE_COMPLETED)
                            <span class="trip-card__status badge badge-outline--success">@lang('Completed')</span>
                        @elseif ($ride->status == Status::RIDE_ACTIVE)
                            <span class="trip-card__status badge badge-outline--primary">@lang('Active')</span>
                        @elseif ($ride->status == Status::RIDE_RUNNING)
                            <span class="trip-card__status badge badge-outline--primary">@lang('Running')</span>
                        @elseif ($ride->status == Status::RIDE_END)
                            <span class="trip-card__status badge badge-outline--success">@lang('Pay Now')</span>
                        @elseif ($ride->status == Status::RIDE_CANCELED)
                            <span class="trip-card__status badge badge-outline--danger">@lang('Canceled')</span>
                        @endif

                        <span
                            class="trip-card__price">{{ gs('cur_sym') . showAmount($ride->amount, currencyFormat: false) }}</span>
                    </div>

                    <div class="trip-card__body">
                        <div class="trip-card__location-group">
                            <div class="trip-card__location trip-card__pickup">
                                <div class="trip-card__location-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 20 20" fill="none">
                                        <g clip-path="url(#clip0_2385_1023)">
                                            <path
                                                d="M17.0944 10.0001C17.0944 13.9121 13.9231 17.0834 10.0111 17.0834C6.09905 17.0834 2.92773 13.9121 2.92773 10.0001C2.92773 6.08806 6.09905 2.91675 10.0111 2.91675C13.9231 2.91675 17.0944 6.08806 17.0944 10.0001Z"
                                                stroke="#F59E0B" stroke-width="1.5" />
                                            <path d="M18.7507 10H17.084" stroke="#F59E0B" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M2.91667 10H1.25" stroke="#F59E0B" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M10 1.25V2.91667" stroke="#F59E0B" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M10 17.0833V18.7499" stroke="#F59E0B" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path opacity="0.4"
                                                d="M12.5 10C12.5 11.3807 11.3807 12.5 10 12.5C8.61929 12.5 7.5 11.3807 7.5 10C7.5 8.61925 8.61929 7.5 10 7.5C11.3807 7.5 12.5 8.61925 12.5 10Z"
                                                stroke="#F59E0B" stroke-width="1.5" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_2385_1023">
                                                <rect width="20" height="20" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </div>
                                <div class="trip-card__location-info">
                                    <h4 class="trip-card__location-title">@lang('Pickup Location')</h4>
                                    <p class="trip-card__address">{{ __($ride->pickup_location) }}
                                    </p>
                                    <span
                                        class="trip-card__datetime">{{ showDateTime($ride->start_time, 'Y-m-d H:i') }}</span>
                                </div>
                            </div>

                            <div class="trip-card__location trip-card__destination">
                                <div class="trip-card__location-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 20 20" fill="none">
                                        <path
                                            d="M12.0827 7.50008C12.0827 8.65066 11.1499 9.58341 9.99935 9.58341C8.84877 9.58341 7.91602 8.65066 7.91602 7.50008C7.91602 6.34949 8.84877 5.41675 9.99935 5.41675C11.1499 5.41675 12.0827 6.34949 12.0827 7.50008Z"
                                            stroke="#3B82F6" stroke-width="1.5" />
                                        <path
                                            d="M11.0472 14.5781C10.7661 14.8487 10.3904 15.0001 9.99952 15.0001C9.60852 15.0001 9.23285 14.8487 8.95177 14.5781C6.37793 12.0841 2.92867 9.298 4.61077 5.25319C5.52027 3.06618 7.70347 1.66675 9.99952 1.66675C12.2955 1.66675 14.4787 3.06619 15.3882 5.25319C17.0682 9.29291 13.6273 12.0927 11.0472 14.5781Z"
                                            stroke="#3B82F6" stroke-width="1.5" />
                                        <path opacity="0.4"
                                            d="M15 16.6667C15 17.5872 12.7614 18.3334 10 18.3334C7.23857 18.3334 5 17.5872 5 16.6667"
                                            stroke="#3B82F6" stroke-width="1.5" stroke-linecap="round" />
                                    </svg>
                                </div>
                                <div class="trip-card__location-info">
                                    <h4 class="trip-card__location-title">@lang('Destination')</h4>
                                    <p class="trip-card__address">{{ __($ride->destination) }}</p>
                                    <span
                                        class="trip-card__datetime">{{ showDateTime($ride->end_time, 'Y-m-d H:i') }}</span>
                                </div>
                            </div>

                            <span role="button" class="trip-card__receipt-btn ride-detail-btn"
                                data-ride_id="{{ $ride->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="30" viewBox="0 0 24 30"
                                    fill="none">
                                    <path opacity="0.4"
                                        d="M14.7136 7.10003e-07H9.11619C7.25446 -2.6524e-05 5.75288 -5.37304e-05 4.57173 0.160191C3.34437 0.3267 2.31164 0.683044 1.49239 1.50971C0.6744 2.3351 0.322944 3.37341 0.158518 4.60748C-3.81267e-05 5.79749 -2.45319e-05 7.31103 2.70215e-06 9.19112V23.7628C-5.17659e-05 24.7278 -0.000106345 25.5589 0.0883771 26.1949C0.179094 26.847 0.39826 27.589 1.09732 28.0394C2.13018 28.7045 3.2749 28.244 3.91774 27.9077C4.28588 27.7151 5.32452 27.0114 5.61151 26.8142C5.983 26.5799 6.18867 26.4551 6.34267 26.3828C6.37222 26.3511 6.46972 26.3089 6.62338 26.3935C6.7789 26.4714 6.97758 26.5953 7.32468 26.8142L9.96317 28.4781C10.2826 28.6796 10.5852 28.8705 10.8581 29.0052C11.1627 29.1555 11.5073 29.2766 11.9149 29.2766C12.3225 29.2766 12.6671 29.1555 12.9717 29.0052C13.2446 28.8705 13.547 28.6798 13.8665 28.4782L16.5052 26.8142C16.8767 26.5799 17.0823 26.4551 17.2363 26.3828C17.2705 26.3538 17.3744 26.3153 17.5169 26.3935C17.6726 26.4714 18.564 27.071 18.911 27.29C19.198 27.4872 19.544 27.7151 19.912 27.9077C20.5549 28.244 21.6997 28.7045 22.7325 28.0394C23.4315 27.589 23.6507 26.847 23.7414 26.1949C23.8299 25.5589 23.8298 24.7278 23.8298 23.7628V9.19119C23.8298 7.31107 23.8298 5.7975 23.6713 4.60748C23.5068 3.37341 23.1553 2.3351 22.3374 1.50971C21.5182 0.683044 20.4854 0.3267 19.258 0.160191C18.0769 -5.37304e-05 16.5753 -2.6524e-05 14.7136 7.10003e-07Z"
                                        fill="hsl(var(--base))" />
                                </svg>
                                @lang('Details')
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                @include($activeTemplate . 'partials.no_data')
            @endforelse
        </div>

        @if ($rides->hasPages())
            {{ paginateLinks($rides) }}
        @endif

    </div>

    <div class="modal fade custom--modal ovo--modal" id="rideDetailReasonModal" tabindex="-1"
        aria-labelledby="rideDetailReasonModalLabel" aria-hidden="false">
        <div class="modal-dialog modal-xl ride-cancel-reason-dialog modal-dialog-centered">
            <div class="modal-content">

            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        html {
            overflow-y: scroll;
        }

        body.modal-open,
        body.modal-open[style] {
            padding-right: 0 !important;
        }

        body.modal-open {
            overflow: hidden !important;
        }

        #rideDetailReasonModal {
            padding-right: 0 !important;
        }

        .modal-backdrop.show {
            opacity: 0.5;
        }

        #rideDetailReasonModal .modal-content {
            border-radius: 18px;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .cancel-title {
            font-weight: 600;
            font-size: 18px;
            letter-spacing: 0.3px;
        }


        #rideDetailReasonModal .modal-body {
            padding: 28px 30px 34px;
        }

        #rideDetailReasonModal .modal-dialog {
            max-height: calc(100vh - 8vh);
            margin: 32px auto 0;
            transform: translateY(-18px);
            transition: transform 0.28s ease, opacity 0.28s ease;
        }

        #rideDetailReasonModal .modal-content {
            max-height: calc(100vh - 8vh);
            overflow: hidden;
        }

        #rideDetailReasonModal .modal-body {
            max-height: calc(100vh - 220px);
            overflow-y: auto;
        }

        body.modal-always-open {
            overflow: hidden;
        }

        .cancel-reason-box {
            background: linear-gradient(145deg,
                    #f8f9fa,
                    #ffffff);
            padding: 22px 24px;
            border-radius: 14px;
            font-size: 14.5px;
            line-height: 1.8;
            transition: all 0.3s ease;
        }

        .cancel-reason-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
        }

        .cancel-reason-text {
            color: var(--bs-danger);
            font-weight: 500;
            letter-spacing: 0.2px;
        }

        #rideDetailReasonModal .modal-content::before {
            display: none !important;
        }

        #rideDetailReasonModal.show .modal-dialog {
            transform: translateY(0);
        }

        #rideDetailReasonModal .ride-cancel-reason-dialog {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100% - 1rem);
        }

        #rideDetailReasonModal.show .modal-dialog {
            transform: none;
            margin-top: auto;
            margin-bottom: auto;
        }

        /* ovo modal apon */
        .ovo--modal__button {
            font-size: 16px;
            font-weight: 500;
            padding: 8px 16px;
            border: 1px solid hsl(var(--border-color));
            color: hsl(var(--black));
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .ovo--modal__button:hover {
            background-color: hsl(var(--black));
            color: hsl(var(--white));
        }

        .ride-request__header {
            display: flex;
            gap: 12px;
        }

        .ride-request__close-btn {
            border: 1px solid hsl(var(--border-color));
            height: 40px;
            width: 40px;
            display: flex;
            justify-content: center;
            align-content: center;
            border-radius: 12px
        }

        .ride-request__close-btn i {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .ride-request-banner {
            border-bottom: 1px solid hsl(var(--border-color));
        }

        @media screen and (max-width: 991px) {
            .ride-request-banner {
                flex-wrap: wrap
            }
        }

        .ride-review__tag {
            font-size: 12px;
            padding: 0 10px;
            height: 24px;
            border: 1px solid hsl(var(--border-color));
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            border-radius: 50px;
            white-space: nowrap;
        }

        .ride-review__title {
            font-size: 20px;
            font-weight: 400;
            color: hsl(var(--black));
            margin-bottom: 0
        }

        .ride-tag__wrap p:last-child {
            color: hsl(var(--success));
            border: 1px solid hsl(var(--success));
            background: hsl(var(--success) / 0.08);
        }

        .ride-review__item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .ride-tag__wrap {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        @media (max-width: 575.98px) {
            .ride-review__title {
                font-size: 18px;
            }

            .ride-review__tag {
                font-size: 11px;
                padding: 3px 6px;
            }
        }

        .ride-info__item__title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 0;
            color: hsl(var(--base));
        }

        .ride-info__item__desc {
            font-size: 14px;
            font-weight: 400;
            color: hsl(var(--body-color));
        }

        .ride-info_line {
            height: 48px;
            width: 1px;
            background-color: hsl(var(--border-color));
        }

        .ride-info {
            display: flex;
            align-items: center;
            gap: 32px;
            justify-content: start;
        }

        @media screen and (max-width: 991px) {
            .ride-info {
                flex-wrap: wrap;
            }
        }

        @media screen and (max-width: 575px) {
            .ride-bottom {
                flex-direction: column
            }
        }

        @media screen and (max-width: 425px) {
            .ride-info__item__title {
                font-size: 16px
            }

            .ride-info {
                gap: 16px;
            }
        }

        .ride-info>div {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .ride-review-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
            padding-bottom: 24px;
            border-bottom: 1px solid hsl(var(--border-color));

        }

        .badge-dot {
            height: 6px;
            width: 6px;
            background-color: hsl(var(--success));
            border-radius: 50%;
        }

        .ovo--modal__badge-success {
            font-size: 12px;
            color: hsl(var(--success));
            border: 1px solid hsl(var(--success));
            padding: 8px;
            border-radius: 8px;
        }

        .ovo--modal__badge-success .badge-dot {
            background-color: hsl(var(--success));
        }

        .ovo--modal__badge-dark {
            font-size: 12px;
            color: hsl(var(--dark));
            border: 1px solid hsl(var(--dark));
            padding: 8px;
            border-radius: 8px;
        }

        .ovo--modal__badge-dark .badge-dot {
            background-color: hsl(var(--dark));
        }

        .ovo--modal__badge-info {
            font-size: 12px;
            color: hsl(var(--info));
            border: 1px solid hsl(var(--info));
            padding: 8px;
            border-radius: 8px;
        }

        .ovo--modal__badge-info .badge-dot {
            background-color: hsl(var(--info));
        }

        .ovo--modal__badge-danger {
            font-size: 12px;
            color: hsl(var(--danger));
            border: 1px solid hsl(var(--danger));
            padding: 8px;
            border-radius: 8px;
        }

        .ovo--modal__badge-danger .badge-dot {
            background-color: hsl(var(--danger));
        }

        .ovo--modal__badge-primary {
            font-size: 12px;
            color: hsl(var(--primary));
            border: 1px solid hsl(var(--primary));
            padding: 8px;
            border-radius: 8px;
        }

        .ovo--modal__badge-primary .badge-dot {
            background-color: hsl(var(--primary));
        }

        .ovo--modal__badge-warning {
            font-size: 12px;
            color: hsl(var(--warning));
            border: 1px solid hsl(var(--warning));
            padding: 8px;
            border-radius: 8px;
        }

        .ovo--modal__badge-warning .badge-dot {
            background-color: hsl(var(--warning));
        }



        .ride-bottom__subtitle {
            font-size: 12px;
            font-weight: 400;
            color: hsl(var(--body-color));
        }

        .ride-bottom__title {
            font-size: 16px;
            font-weight: 400;
            color: hsl(var(--black));
            margin-bottom: 0;
            max-width: 375px;
        }

        .ride-bottom__desc {
            font-size: 12px;
            font-weight: 400;
            color: hsl(var(--black) / 0.5);
        }

        .ride-bottom-center {
            border-bottom: 1px dashed hsl(var(--black)/0.4);
            position: relative;
            z-index: 1;
            flex-grow: 1;
            max-width: 480px;
            height: 0;

        }

        .ride-bottom-center .icon-car {
            left: 50%;
            top: 50%;
            position: absolute;
            transform: translate(-50%, -50%);
            background: #fff;
            padding: 0px 30px;
            width: 120px;
            z-index: 2;
            display: block;
        }

        .ride-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;

        }

        .ride-review-main {
            box-shadow: 0 0 4px 0 rgba(0, 0, 0, 0.04), 0 4px 8px 0 rgba(0, 0, 0, 0.06);
            padding: 24px;
            border-radius: 12px
        }

        .ride-main {
            margin-top: 18px;
            border-radius: 14px;
            box-shadow: 0 0 4px 0 rgba(0, 0, 0, 0.04), 0 4px 8px 0 rgba(0, 0, 0, 0.06);
            padding: 14px 18px;
        }

        .ride-user {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap
        }

        .ride-user__left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .ride-user__info {
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 2px;
        }

        .ride-user__thumb {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #eef1f6;
            flex: 0 0 auto;
        }

        .ride-user__thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .ride-user__title {
            font-size: 16px;
            font-weight: 600;
            color: hsl(var(--black));
            margin: 0 0 4px;
            line-height: 1.2;
        }

        .ride-user__desc {
            font-size: 12px;
            color: hsl(var(--body-color));
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin: 0;
            line-height: 1.25;
        }

        .ride-user__info .ride-user__title {
            margin-bottom: 0;
        }

        .ride-user__rating {
            text-align: right;
        }

        .ride-user__rating-row {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: flex-end;
        }

        .ride-user__score {
            font-size: 24px;
            font-weight: 700;
            color: #f5b400;
            line-height: 1;
        }

        .ride-user__stars {
            display: inline-flex;
            gap: 4px;
            font-size: 16px;
            line-height: 1;
        }

        .ride-user__stars .star {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            background: #f3f4f6;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .ride-user__stars .filled {
            background: #fff6d6;
        }

        .ride-user__stars .star svg {
            width: 16px;
            height: 16px;
        }

        .ride-user__stars .star svg path {
            fill: #d1d5db;
        }

        .ride-user__stars .filled svg path {
            fill: #f5b400;
        }

        .ride-user__hint {
            display: block;
            margin-top: 4px;
            font-size: 12px;
            color: #475467;
        }


        /* modal end */

        /******** Skeleton start *****/

        /* Skeleton Base */
        .skeleton {
            position: relative;
            overflow: hidden;
            background: #e9ecef;
            border-radius: 6px;
        }

        .skeleton::after {
            content: "";
            position: absolute;
            inset: 0;
            transform: translateX(-100%);
            background: linear-gradient(90deg,
                    transparent,
                    rgba(255, 255, 255, 0.6),
                    transparent);
            animation: shimmer 1.4s infinite;
        }

        @keyframes shimmer {
            100% {
                transform: translateX(100%);
            }
        }

        /* Sizes */
        .skeleton-text {
            height: 12px;
            width: 100%;
        }

        .skeleton-title {
            height: 18px;
            width: 140px;
        }

        .skeleton-big {
            height: 24px;
            width: 100px;
        }

        .skeleton-circle {
            width: 44px;
            height: 44px;
            border-radius: 50%;
        }

        .skeleton-tag {
            height: 22px;
            width: 70px;
            border-radius: 50px;
        }

        .skeleton-line {
            height: 1px;
            width: 100%;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            'use strict';

            const rideDetailsSkeleton = () => `
                <div class="ride-request-banner d-flex gap-2 justify-content-between align-items-center">
        
                    <div class="skeleton skeleton-title" style="width:180px;"></div>
        
                    <div class="ride-request__header justify-content-end">
                        <div class="skeleton" style="width:120px;height:36px;border-radius:12px;"></div>
                        <div class="skeleton" style="width:40px;height:40px;border-radius:12px;"></div>
                    </div>
        
                </div>
        
                <div class="modal-body">
        
                    <div class="ride-review-main">
                        <div class="ride-review-wrapper">
        
                            <div class="ride-review__item">
                                <div class="skeleton skeleton-title mb-2" style="width:140px;"></div>
        
                                <div class="d-flex gap-2 flex-wrap">
                                    ${[1,2,3,4].map(() => `
                                                                                                                <div class="skeleton skeleton-tag"></div>
                                                                                                            `).join('')}
                                </div>
                            </div>
        
                            <div class="ride-info">
                                ${[1,2,3].map(() => `
                                                                                                            <div>
                                                                                                                <div>
                                                                                                                    <div class="skeleton skeleton-big mb-2"></div>
                                                                                                                    <div class="skeleton skeleton-text" style="width:80px;"></div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        `).join('')}
        
                                <div>
                                    <div class="skeleton skeleton-tag" style="width:90px;"></div>
                                </div>
                            </div>
        
                        </div>
        
                        <div class="ride-bottom mt-3">
        
                            ${[1,2].map(() => `
                                                                                                        <div class="ride-bottom__item">
                                                                                                            <div>
                                                                                                                <div class="skeleton skeleton-text mb-2" style="width:60px;"></div>
                                                                                                                <div class="skeleton skeleton-title mb-2" style="width:220px;"></div>
                                                                                                                <div class="skeleton skeleton-text" style="width:140px;"></div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    `).join('')}
        
                        </div>
                    </div>
        
                    <div class="ride-main mt-3">
                        <div class="ride-user">
        
                            <div class="ride-user__left">
                                <div class="skeleton skeleton-circle"></div>
        
                                <div>
                                    <div class="skeleton skeleton-title mb-2" style="width:120px;"></div>
                                    <div class="skeleton skeleton-text" style="width:160px;"></div>
                                </div>
                            </div>
        
                            <div class="text-end">
                                <div class="skeleton skeleton-big mb-2" style="width:40px;"></div>
        
                                <div class="d-flex gap-1 justify-content-end mb-1">
                                    ${[1,2,3,4,5].map(() => `
                                                                                                                <div class="skeleton" style="width:20px;height:20px;border-radius:4px;"></div>
                                                                                                            `).join('')}
                                </div>
        
                                <div class="skeleton skeleton-text" style="width:100px;"></div>
                            </div>
        
                        </div>
                    </div>
        
                </div>
            `;

            const showRideDetailsSkeleton = () => {
                $('#rideDetailReasonModal .modal-content').html(rideDetailsSkeleton());
            };

            $('.ride-detail-btn').on('click', e => {
                const $btn = $(e.currentTarget);
                const payload = {
                    ride_id: $btn.data('ride_id')
                };
                const $modal = $('#rideDetailReasonModal');

                if (!payload.ride_id) {
                    return notify('error', "Ride id not provided");
                }

                showRideDetailsSkeleton();
                $modal.modal('show');

                $.get("{{ route('user.ride.details') }}", payload, null, 'json')
                    .done(({
                        status,
                        message,
                        data
                    } = {}) => {

                        if (status !== 'success') {
                            $modal.modal('hide');
                            return notify('error', message);
                        }

                        $modal.find('.modal-content').html(data?.html);
                    }).fail(() => {
                        notify('error', "Something went wrong");
                        $modal.modal('hide');
                    });
            });

        })(jQuery);
    </script>
@endpush
