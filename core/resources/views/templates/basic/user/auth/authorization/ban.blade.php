@extends($activeTemplate . 'layouts.app')
@section('app-content')
    @php
        $authContent = getContent('auth.content', true)?->data_values;
    @endphp
    <main class="page-wrapper">
        <section class="account">
            <div class="account-content bg-img"
                data-background-image="{{ frontendImage('auth', $authContent?->background_image, '970x990') }}">
                <div class="account-header">
                    <a class="account-logo mb-4" href="{{ route('home') }}">
                        <img src="{{ getImage(getFilePath('logoIcon') . '/' . 'logo.png') }}" alt="">
                    </a>
                    <div class="account-heading mb-4">
                        <p class="account-heading__title text--danger mb-3">
                            <strong>@lang('YOUR ACCOUNT HAS BEEN BANNED')</strong>
                        </p>
                        <p class="account-heading__subtitle">
                            @lang('Your account has been suspended due to a violation of our policies. If you believe this action was taken in error, please contact support for further assistance.')
                        </p>
                    </div>
                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                        <a href="{{ route('contact') }}" class="btn btn--base">
                            @lang('Contact Support')
                        </a>
                        <a href="{{ route('user.logout') }}" class="btn btn-outline--base">
                            @lang('Logout')
                        </a>
                    </div>
                </div>
            </div>
            <div class="account-thumb bg-img"
                data-background-image="{{ frontendImage('auth', $authContent?->account_image, '950x990') }}">
            </div>
        </section>
    </main>
@endsection
