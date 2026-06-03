<h6 class="title">@lang('Select a service')</h6>
<div class="booking-ride__list serviceDiv">
    @php
        if ($rideType == Status::CITY_RIDE) {
            $minColumnName       = 'city_min_fare';
            $maxColumnName       = 'city_max_fare';
            $recommendColumnName = 'city_recommend_fare';
        } else {
            $minColumnName       = 'intercity_min_fare';
            $maxColumnName       = 'intercity_max_fare';
            $recommendColumnName = 'intercity_recommend_fare';
        }

        $distance = isset($distance) ? $distance : 1;
    @endphp

    @forelse ($services as $service)
        @php
            $service = (object) $service;
            $id      = @$service->id;
            $name    = @$service->name;

            $subtitle        = $service->subtitle;
            $image           = $service->image;
            $minAmount       = $service->{$minColumnName};
            $maxAmount       = $service->{$maxColumnName};
            $recommendAmount = $service->{$recommendColumnName};
        @endphp

        <div class="booking-ride__list-item">
            <div class="form-check form--radio">
                <label class="form-check-label" for="exampleRadios{{ $loop->iteration }}">
                    <input class="form-check-input serviceOption" hidden type="radio" name="service"
                        id="exampleRadios{{ $loop->iteration }}" value="{{ $id }}">
                    <div class="ride-content">
                        <div class="ride-content__left">
                            <div class="ride-content__left-thumb">
                                <img src="{{ imageGet('service', $image) }}" alt="img">
                            </div>

                            <div class="ride-content__left-content">
                                <div class="ride-content__top">
                                    <h6 class="title mb-0">{{ __($name) }}</h6>
                                    <span class="subtitle">{{ __($subtitle) }}</span>
                                </div>
                                <div class="ride-content__right">
                                    <h5 class="amount mb-0 text-end" title="@lang('Recommend Amount')" data-bs-toggle="tooltip">
                                        {{ gs('cur_sym') . showAmount($recommendAmount * $distance, currencyFormat: false) }}
                                    </h5>
                                    <span class="fs-12">
                                        <span
                                            title="@lang('Minimum Amount')">{{ gs('cur_sym') . showAmount($minAmount * $distance, currencyFormat: false) }}</span>
                                        -
                                        <span
                                            title="@lang('Maximum Amount')">{{ gs('cur_sym') . showAmount($maxAmount * $distance, currencyFormat: false) }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
        </div>

    @empty
        <x-no-data message="There are no available service at the moment." />
    @endforelse
</div>

@push('style')
    <style>
        .ride-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .ride-content__left {
            width: 100%;
        }


        .ride-content__right span {
            white-space: nowrap;
        }

        .ride-content__left-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            gap: 5px;
        }

        @media (max-width: 1199px) {
            .ride-content__left-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .ride-content__right {
                display: flex;
                align-items: center;
                gap: 5px;
                justify-content: space-between;
                width: 100%;
            }
        }


        @media(max-width: 991px) {
            .ride-content__left-content {
                flex-direction: unset;
                align-items: unset;
            }

            .ride-content__right {
                display: unset;
                align-items: unset;
                gap: 5px;
                justify-content: unset;
                width: unset;
            }
        }

        @media(max-width: 425px) {
            .ride-content__left-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .ride-content__right {
                display: flex;
                align-items: center;
                gap: 5px;
                justify-content: space-between;
                width: 100%;
            }
        }

        .ride-content__top .subtitle {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;

        }
    </style>
@endpush
