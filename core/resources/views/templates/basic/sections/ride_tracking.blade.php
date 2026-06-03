@php
    $rideTrackingContent = getContent('ride_tracking.content', true);
    $rideTrackingElements = getContent('ride_tracking.element');
@endphp

<section class="track pb-120">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-heading">
                    <span class="section-heading__subtitle wow fadeInUp" data-wow-duration="0.4s"
                        data-wow-delay="0.4s">{{ __($rideTrackingContent?->data_values?->title ?? '') }}</span>
                    <h2 class="section-heading__title wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.5s">
                        {{ __($rideTrackingContent?->data_values?->subtitle ?? '') }}
                    </h2>
                </div>
            </div>
        </div>
        <div class="track__main">
            <div class="row align-items-center">
                @foreach ($rideTrackingElements as $rideTrackingElement)
                    <div class="col-lg-12 wow fadeInUp" data-wow-duration="{{ 0.5 + $loop->index * 0.1 }}s"
                        data-wow-delay="{{ 0.5 + $loop->index * 0.1 }}s">
                        <div class="trake__wrap">
                            <div
                                class="row align-items-center gy-4 {{ $loop->iteration % 2 == 0 ? '' : 'flex-row-reverse' }}">
                                <div class="col-lg-4">
                                    <div class="track__thumb text-start">
                                        <img src="{{ frontendImage('ride_tracking', $rideTrackingElement?->data_values?->image ?? '', '430x400') }}"
                                            alt="image">
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="track__item">

                                        <div class="track__heading">
                                            <h3 class="track__heading__title">
                                                {{ $loop->iteration < 10 ? '0' . $loop->iteration : $loop->iteration }}
                                            </h3>
                                            <div>
                                                <span
                                                    class="track__heading__tag">{{ $rideTrackingElement?->data_values?->heading ?? '' }}</span>
                                            </div>
                                        </div>

                                        <h3 class="track__item__title">
                                            {{ $rideTrackingElement?->data_values?->title ?? '' }}</h3>
                                        <p class="track__item__desc">
                                            {{ $rideTrackingElement?->data_values?->description ?? '' }}</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
