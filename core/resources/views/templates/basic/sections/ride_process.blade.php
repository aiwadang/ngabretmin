@php
    $rideRequestProcessContent = getContent('ride_process.content', true);
    $setPickupContent = getContent('set_pickup.content', true);

    $biddingPriceContent = getContent('bidding_price.content', true);
    $biddingPriceElements = getContent('bidding_price.element', orderById: true);

    $chooseVehicleContent = getContent('choose_vehicle.content', true);
    $chooseVehicleElements = getContent('choose_vehicle.element');

    $findDriverContent = getContent('find_driver.content', true);

@endphp

<section class="service-steps bg-img" data-background-image="{{ getImage('assets/images/service-bg.png') }}" id="ride-process">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-heading">
                    <span class="section-heading__subtitle wow fadeInUp" data-wow-duration="0.4s"
                        data-wow-delay="0.4s">{{ __($rideRequestProcessContent?->data_values?->title ?? '') }}</span>
                    <h2 class="section-heading__title wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.5s">
                        {{ __($rideRequestProcessContent?->data_values?->heading ?? '') }}
                    </h2>
                </div>
            </div>
        </div>
        <div class="row gy-4">
            <div class="col-xl-4 col-md-6">
                <div class="service-steps__item wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.5s">
                    <div class="service-steps__heading">
                        <div class="service-steps__item__number">@lang('1')</div>
                        <h3 class="service-steps__item__title">{{ __($setPickupContent?->data_values?->title ?? '') }}
                        </h3>

                    </div>
                    <p class="service-steps__item__desc">{{ __($setPickupContent?->data_values?->subtitle ?? '') }}</p>

                    <p class="service-steps__item__tag"> <span class="service-steps__item__dot"></span>
                        {{ __($setPickupContent?->data_values?->demo_pickup_point ?? '') }} </p>
                    <p class="service-steps__item__tag"> <span class="service-steps__item__dot"></span>
                        {{ __($setPickupContent?->data_values?->demo_destination ?? '') }}</p>
                </div>

                <div class="service-steps__item wow fadeInUp" data-wow-duration="0.6s" data-wow-delay="0.6s">
                    <div class="service-steps__heading">
                        <div class="service-steps__item__number">@lang('2')</div>
                        <h3 class="service-steps__item__title">
                            {{ __($chooseVehicleContent?->data_values?->title ?? '') }}</h3>
                    </div>
                    <p class="service-steps__item__desc">
                        {{ __($chooseVehicleContent?->data_values?->subtitle ?? '') }}</p>
                    <div class="row gy-3">
                        @foreach ($chooseVehicleElements as $chooseVehicleElement)
                            <div class="col-lg-4">
                                <div class="service-steps__manu">
                                    <div class="service-steps__thumb">
                                        <img src="{{ frontendImage('choose_vehicle', $chooseVehicleElement?->data_values?->image ?? '') }}"
                                            alt="image">
                                    </div>
                                    <p class="service-steps__name">
                                        {{ __($chooseVehicleElement?->data_values?->name ?? '') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 d-none d-xl-block">
                <div class="service-steps__thumb__main wow fadeInUp" data-wow-duration="0.6s" data-wow-delay="0.7s">
                    <img src="{{ frontendImage('ride_process', $rideRequestProcessContent?->data_values?->image ?? '', '440x885') }}"
                        alt="image">
                </div>
            </div>

            <div class="col-xl-4 col-md-6 d-flex flex-column">
                <div class="service-steps__item mt-auto wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.5s">
                    <div class="service-steps__heading">
                        <div class="service-steps__item__number">@lang('3')</div>
                        <h3 class="service-steps__item__title">
                            {{ __($findDriverContent?->data_values?->title ?? '') }}</h3>

                    </div>
                    <p class="service-steps__item__desc mb-0">
                        {{ __($findDriverContent?->data_values?->subtitle ?? '') }}</p>
                </div>
                <div class="service-steps__item wow fadeInUp" data-wow-duration="0.6s" data-wow-delay="0.6s">
                    <div class="service-steps__heading">
                        <div class="service-steps__item__number">@lang('4')</div>
                        <h3 class="service-steps__item__title">
                            {{ __($biddingPriceContent?->data_values?->title ?? '') }}</h3>
                    </div>
                    <p class="service-steps__item__desc">{{ __($biddingPriceContent?->data_values?->subtitle ?? '') }}
                    </p>
                    <p class="service-steps__menu-title">
                        {{ __($biddingPriceContent?->data_values?->offer_text ?? '') }}
                    </p>
                    <div class="row gy-3">
                        @foreach ($biddingPriceElements as $element)
                            <div class="col-lg-4">
                                <div class="service-steps__manu">
                                    <p class="service-steps__name">{{ __($element?->data_values?->price ?? '') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
