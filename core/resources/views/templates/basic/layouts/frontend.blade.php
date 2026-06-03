@extends($activeTemplate . 'layouts.app')
@section('app-content')
    @stack('fbComment')

    @include('Template::partials.header')

    @yield('content')
    @include('Template::partials.footer')
@endsection
