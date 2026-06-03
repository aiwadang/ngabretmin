@php
    $riderSetupStepsContent = getContent('rider_setup_steps.content', true);
    $riderSetupStepElements = getContent('rider_setup_steps.element');
@endphp

<section class="easy-steps pb-120">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-heading">
                    <span class="section-heading__subtitle wow fadeInUp" data-wow-duration="0.4s"
                        data-wow-delay="0.4s">{{ __($riderSetupStepsContent?->data_values?->heading ?? '') }}</span>
                    <h2 class="section-heading__title wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.5s">
                        {{ __($riderSetupStepsContent?->data_values?->title ?? '') }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="easy-steps__wrap">
                    @foreach ($riderSetupStepElements as $riderSetupStepElement)
                        <div class="easy-steps__item wow fadeInUp" data-wow-duration="{{ 0.5 + $loop->index * 0.1 }}s"
                            data-wow-delay="{{ 0.5 + $loop->index * 0.1 }}s">
                            <div class="easy-steps__item__heading">
                                <div class="easy-steps__item__icon">
                                    <img src="{{ getImage('assets/images/drive2-icon.png') }}" alt="image">
                                </div>
                                <h3 class="easy-steps__item__number">
                                    {{ $loop->iteration < 10 ? '0' . $loop->iteration : $loop->iteration }}</h3>
                            </div>

                            <div class="easy-steps__content">
                                <h3 class="easy-steps__title">
                                    {{ __($riderSetupStepElement?->data_values?->title ?? '') }}</h3>
                                <p class="easy-steps__desc">
                                    {{ __($riderSetupStepElement?->data_values?->description ?? '') }}</p>
                            </div>

                            <div class="easy-steps__thumb">
                                <img src="{{ frontendImage('rider_setup_steps', $riderSetupStepElement?->data_values?->image ?? '') }}"
                                    alt="image">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
