@php
    $driverCtaContent = getContent('driver_banner.content', true);
@endphp

<section class="driver-one-section pb-120">
    <div class="container">
        <div class="driver-one">
            <div class="row gy-5 justify-content-center">
                <div class="col-lg-6">
                    <div class="driver-one__content">
                        <h2 class="driver-one__title js-driver-title wow fadeInUp" data-highlight = "3"
                            data-wow-duration="0.4s" data-wow-delay="0.4s">
                            {{ __($driverCtaContent?->data_values?->title ?? '') }}</h2>
                        <p class="driver-one__desc wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.5s">
                            {{ __($driverCtaContent?->data_values?->description ?? '') }}</p>
                        <div class="driver-one__button wow fadeInUp" data-wow-duration="0.6s" data-wow-delay="0.6s">
                            <a href="{{ $driverCtaContent?->data_values?->app_download_link }}"
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
                                @lang('Download App')</a>

                            <a class="btn  d-none driver__btn radius--12"
                                href="{{ route('driver.join') }}#driver-join-step">
                                @lang('Watch How it Works')
                            </a>
                        </div>
                        <div class="driver-one__odometer wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.7s">
                            <div class="driver-one__odometer__content">
                                <h3 class="driver-one__odometer__title">
                                    <span
                                        class="driver-one__odometer__prefix">{{ $driverCtaContent?->data_values?->earning_currency_symbol ?? '' }}</span>
                                    <span class="odometer"
                                        data-odometer-final="{{ $driverCtaContent?->data_values?->earning_amount ?? 0 }}"></span>
                                    <span
                                        class="driver-one__odometer__suffix">{{ $driverCtaContent?->data_values?->earning_suffix ?? '' }}</span>
                                </h3>
                                <p class="driver-one__odometer__desc">
                                    {{ $driverCtaContent?->data_values?->earning_label ?? '' }}</p>
                            </div>
                            <div class="driver-one__line"></div>
                            <div class="driver-one__odometer__content">
                                <h3 class="driver-one__odometer__title">
                                    <span class="odometer"
                                        data-odometer-final="{{ $driverCtaContent?->data_values?->drivers_count ?? 0 }}"></span>
                                    <span
                                        class="driver-one__odometer__suffix">{{ $driverCtaContent?->data_values?->drivers_count_unit ?? '' }}</span>
                                </h3>
                                <p class="driver-one__odometer__desc">
                                    {{ $driverCtaContent?->data_values?->drivers_label ?? '' }}</p>
                            </div>
                            <div class="driver-one__line"></div>
                            <div class="driver-one__odometer__content">
                                <h3 class="driver-one__odometer__title">
                                    <span class="odometer"
                                        data-odometer-final="{{ $driverCtaContent?->data_values?->rating_value ?? 0 }}"></span>
                                </h3>
                                <p class="driver-one__odometer__desc">
                                    {{ $driverCtaContent?->data_values?->rating_label ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="driver-one__thumb wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.5s">
                        <img src="{{ frontendImage('driver_banner', $driverCtaContent?->data_values?->image ?? '', '555x690') }}"
                            alt="image">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
