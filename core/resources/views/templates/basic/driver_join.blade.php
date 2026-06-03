@extends($activeTemplate . 'layouts.frontend')
@section('content')

    @if (@$sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif

@endsection

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/odometer.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/odometer.css') }}">
@endpush


@push('script')
    <script>
        "use strict";
        (function($) {


            const initOdometer = () => {
                $('.odometer').each(function() {

                    const el = this;
                    const value = $(el).attr('data-odometer-final') || 0;

                    if ($(el).data('done')) return;

                    $(el).data('done', true);

                    el.innerHTML = 0;

                    setTimeout(() => {
                        el.innerHTML = value;
                    }, 200);
                });
            };

            initOdometer();

            $(window).on('scroll', initOdometer);


            $('.driver-steps__item.is-active .driver-steps__body').each(function() {
                var $body = $(this);
                $body.css('max-height', $body.prop('scrollHeight') + 'px');
            });

            $('.driver-steps__header').on('click', function() {
                var $item = $(this).closest('.driver-steps__item');
                var $body = $item.find('.driver-steps__body');
                var isOpen = $item.hasClass('is-active');

                $('.driver-steps__item').removeClass('is-active');
                $('.driver-steps__header').attr('aria-expanded', 'false');
                $('.driver-steps__body').attr('hidden', true).css('max-height', 0);

                if (!isOpen) {
                    $item.addClass('is-active');
                    $(this).attr('aria-expanded', 'true');
                    $body.attr('hidden', false);
                    // Smooth open: let the browser apply the new state, then animate height
                    requestAnimationFrame(function() {
                        $body.css('max-height', $body.prop('scrollHeight') + 'px');
                    });
                }
            });
        })(jQuery);
    </script>
@endpush
