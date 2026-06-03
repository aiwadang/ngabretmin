@extends($activeTemplate . 'layouts.app')
@section('app-content')
    @php
        $authContent = getContent('auth.content', true)?->data_values;
    @endphp
    <main class="page-wrapper">
        <section class="account">
            <div class="account-content bg-img"
                data-background-image="{{ frontendImage('auth', $authContent?->background_image, '970x990') }}">
                <div class="account-header mx-420">
                    <a class="account-logo mb-4" href="{{ route('home') }}">
                        <img src="{{ getImage(getFilePath('logoIcon') . '/' . 'logo.png') }}" alt="">
                    </a>
                    <div class="account-heading mb-2 text-center">
                        <h5 class="mb-2">{{ __($pageTitle) }}</h5>
                        <p class="account-heading__subtitle">@lang('A 6-digit verification code has been sent to') {{ showEmailAddress($email) }}</p>
                    </div>
                </div>
                <div class="account-body">
                    <form method="POST" action="{{ route('user.password.verify.code') }}" class="account-form submit-form">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <div class="row gy-3">
                            <div class="col-12">
                                @include($activeTemplate . 'partials.verification_code')
                            </div>

                            <div class="col-12">
                                @lang('Check your Spam folder. If not found,')
                                <a href="{{ route('user.password.request') }}">@lang('send again')</a>
                            </div>
                        </div>
                        <button class="w-100 btn btn--base mt-3" type="submit">@lang('Submit')</button>
                    </form>
                </div>
            </div>
            <div class="account-thumb bg-img"
                data-background-image="{{ frontendImage('auth', $authContent?->account_image, '950x990') }}">
            </div>
        </section>
    </main>
@endsection
