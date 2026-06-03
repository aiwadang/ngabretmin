<!doctype html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="P-A-ID" content="{{ config('app.PUSHER_APP_KEY') }}">
    <meta name="P-CLUSTER" content="{{ config('app.PUSHER_APP_CLUSTER') }}">
    <meta name="APP-DOMAIN" content="{{ route('home') }}">

    <title> {{ gs()->siteName(__($pageTitle)) }}</title>

    @include('partials.seo')

    <link href="{{ asset('assets/global/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/global/css/all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/animate.css') }}">
    @stack('style-lib')

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/main.css') }}">

    @stack('style')

    <link rel="stylesheet"
        href="{{ asset($activeTemplateTrue . 'css/color.php') }}?color={{ gs('base_color') }}&secondColor={{ gs('secondary_color') }}">
</head>

@php echo loadExtension('google-analytics') @endphp

<body>

    <div class="preloader">
        <img src="{{ getImage(getFilePath('preloader') . '/' . gs('preloader_image')) }}" alt="">
    </div>
    <div class="body-overlay"></div>
    <div class="sidebar-overlay"></div>

    @yield('app-content')

    @include('Template::partials.cookie')

    <script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset($activeTemplateTrue . 'js/viewport.jquery.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/wow.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/main.js') }}"></script>

    @stack('script-lib')

    <script src="{{ asset('assets/global/js/global.js') }}"></script>
    @php echo loadExtension('tawk-chat') @endphp

    @include('partials.notify')

    @stack('script')

    <script>
        (function($) {
            "use strict";

            //plicy
            $('.policy').on('click', function() {
                $.get('{{ route('cookie.accept') }}', function(response) {
                    $('.cookies-card').addClass('d-none');
                });
            });
            // event when change lang
            $(".langSel").on("click", function() {
                const code = $(this).data('value');
                window.location.href = "{{ route('home') }}/change/" + code;
            });

            //show cookie card
            setTimeout(function() {
                $('.cookies-card').removeClass('hide');
            }, 2000);


            // ________________ Scroll Position __________________________________
            $(window).on('beforeunload', () => {
                sessionStorage.setItem('scrollPosition', $(window).scrollTop());
            });

            $(window).on('load', () => {
                const isReload = performance.getEntriesByType('navigation')[0]?.type === 'reload';

                if (!isReload) {
                    sessionStorage.removeItem('scrollPosition');
                    return;
                }

                const scrollPosition = sessionStorage.getItem('scrollPosition');

                if (scrollPosition !== null) {
                    $(window).scrollTop(parseInt(scrollPosition));
                    sessionStorage.removeItem('scrollPosition');
                }

            });

        })(jQuery);
    </script>
</body>

</html>
