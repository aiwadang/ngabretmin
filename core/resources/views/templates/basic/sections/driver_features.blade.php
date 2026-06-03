@php
    $driverFeatureContent = getContent('driver_features.content', true);
    $driverFeatureElements = getContent('driver_features.element', orderById: false);
@endphp

<section class="driver-features__section pb-120">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-heading">
                    <h2 class="section-heading__title wow fadeInUp" data-wow-duration="0.4s" data-wow-delay="0.4s">
                        {{ __($driverFeatureContent?->data_values?->heading ?? '') }}
                    </h2>
                </div>
            </div>
        </div>
        <div class="row gy-4">
            @foreach ($driverFeatureElements as $element)
                <div class="col-xl-4 col-md-6 wow fadeInUp" data-wow-duration="{{ 0.5 + $loop->index * 0.1 }}s"
                    data-wow-delay="{{ 0.5 + $loop->index * 0.1 }}s">
                    <div class="driver-features @if ($loop->first) active @endif">
                        <div class="driver-features__icon">
                            @php echo $element?->data_values?->feature_icon; @endphp
                        </div>
                        <div class="driver-features__content">
                            <h3 class="driver-features__title">{{ __($element?->data_values?->title ?? '') }}</h3>
                            <p class="driver-features__desc">{{ __($element?->data_values?->description ?? '') }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
