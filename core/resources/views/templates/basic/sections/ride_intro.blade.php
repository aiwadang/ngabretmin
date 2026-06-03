@php
    $rideIntroContent = getContent('ride_intro.content', true);
@endphp
<section class="driver-one-section pb-120 bg-img" data-background-image="{{ asset('assets/images/driver-gird-bg.png') }}">
    <div class="glow__bg">
        <svg width="428" height="521" viewBox="0 0 428 521" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g filter="url(#filter0_f_5_2989)">
                <circle cx="1.5" cy="94.5" r="226.5" fill="currentColor" />
            </g>
            <defs>
                <filter id="filter0_f_5_2989" x="-425" y="-332" width="853" height="853"
                    filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                    <feFlood flood-opacity="0" result="BackgroundImageFix" />
                    <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                    <feGaussianBlur stdDeviation="100" result="effect1_foregroundBlur_5_2989" />
                </filter>
            </defs>
        </svg>
    </div>
    <div class="container">
        <div class="driver-one">
            <div class="row gy-5 justify-content-center align-items-center">
                <div class="col-lg-6">
                    <div class="driver-one__content">
                        <h2 class="driver-one__title wow fadeInUp" data-wow-duration="0.4s" data-wow-delay="0.4s">
                            {{ __($rideIntroContent?->data_values?->heading) }}</h2>
                        <p class="driver-one__desc wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.5s">
                            {{ __($rideIntroContent?->data_values?->description) }}</p>
                        <div class="driver-one__button mb-0 wow fadeInUp" data-wow-duration="0.6s"
                            data-wow-delay="0.6s">
                            <a href="{{ $rideIntroContent?->data_values?->app_download_link }}"
                                class="btn btn--base radius--12 driver__base-btn" target="_blank">
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none">
                                        <path d="M12 17V3" stroke="CurrentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path d="M6 11L12 17L18 11" stroke="CurrentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M19 21H5" stroke="CurrentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                    </svg>
                                </span>
                                @lang('Download the App')</a>

                            <a class="btn  d-none driver__btn radius--12" href="{{ route('how.to.work') }}#ride-process">
                                @lang('Watch How it Works')</a>
                        </div>

                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="driver-one__thumb wow fadeInUp" data-wow-duration="0.6s" data-wow-delay="0.6s">
                        <img src="{{ frontendImage('ride_intro', $rideIntroContent?->data_values?->image, '555x690') }}"
                            alt="image">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
