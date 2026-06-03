@php
    $driverPageCtaContent = getContent('driver_page_cta.content', true);
@endphp

<section class="drive-cta bg-img"
    data-background-image="{{ frontendImage('driver_page_cta', $driverPageCtaContent?->data_values?->background_image ?? '', '780x700') }}">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-7">
                <div class="drive-cta__content">
                    <div class="drive-cta__card">
                        <h2 class="drive-cta__title wow fadeInUp" data-wow-duration="0.4s" data-wow-delay="0.4s">
                            {{ __($driverPageCtaContent?->data_values?->heading ?? '') }}
                        </h2>
                        <p class="drive-cta__desc wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.5s">
                            {{ __($driverPageCtaContent?->data_values?->description ?? '') }}</p>
                    </div>
                    <div class="wow fadeInUp" data-wow-duration="0.6s" data-wow-delay="0.6s">
                        <a class="btn drive-cta__btn btn--base-two"
                            href="{{ $driverPageCtaContent?->data_values?->app_download_link }}"
                            target="_blank">@lang('Download Mobile App')</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-5">
                <div class="drive-cta__thumb wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.7s">
                    <img src="{{ frontendImage('driver_page_cta', $driverPageCtaContent?->data_values?->app_image ?? '', '780x700') }}"
                        alt="image">
                </div>
            </div>
        </div>
    </div>
</section>
