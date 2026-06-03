@php
    $driverStepsContent = getContent('driver_steps.content', true);
    $driverStepsElements = getContent('driver_steps.element', orderById: false);
@endphp

<section class="get-started pb-120" id="driver-join-step">
    <div class="container">
        <div class="row gy-4 ">
            <div class="col-lg-4">
                <div class="get-started__thumb wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.5s">
                    <img src="{{ frontendImage('driver_steps', $driverStepsContent?->data_values?->image ?? '', '425x745') }}"
                        alt="image">
                </div>
            </div>
            <div class="col-lg-8">
                <div class="driver-steps__content">

                    <div class="section-heading driver-steps__heading">
                        <h2 class="section-heading__title wow fadeInUp text-start" data-wow-duration="0.4s"
                            data-wow-delay="0.4s">
                            {{ __($driverStepsContent?->data_values?->heading ?? '') }}
                        </h2>
                    </div>
                    <div class="driver-steps">
                        @foreach ($driverStepsElements as $driverStep)
                            <div class="driver-steps__item @if ($loop->iteration === 1) is-active @endif wow fadeInUp"
                                data-wow-duration="{{ 0.4 + $loop->index * 0.1 }}s"
                                data-wow-delay="{{ 0.4 + $loop->index * 0.1 }}s">
                                <button class="driver-steps__header" type="button" aria-expanded="true">
                                    <span
                                        class="driver-steps__index">{{ $loop->iteration < 10 ? '0' . $loop->iteration : $loop->iteration }}</span>
                                    <span
                                        class="driver-steps__title">{{ $driverStep?->data_values?->title ?? '' }}</span>

                                </button>
                                <div class="driver-steps__body">
                                    <p class="driver-steps__body__desc">
                                        {{ $driverStep?->data_values?->description ?? '' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
