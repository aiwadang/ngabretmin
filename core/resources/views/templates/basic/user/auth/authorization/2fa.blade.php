@extends($activeTemplate . 'layouts.app')
@section('app-content')
    @php
        $twoFactorVerificationContent = getContent('2fa_verification.content', true)?->data_values;
    @endphp
    <main class="page-wrapper">
        <section class="account">
            <div class="account-content bg-img"
                data-background-image="{{ frontendImage('2fa_verification', $twoFactorVerificationContent?->background_image, '970x990') }}">
                <div class="account-header">
                    <a class="account-logo" href="{{ route('home') }}">
                        <img src="{{ getImage(getFilePath('logoIcon') . '/' . 'logo.png') }}" alt="">
                    </a>
                    <div class="account-heading">
                        <p class="account-heading__title">{{ __($twoFactorVerificationContent?->heading ?? '') }}</p>
                        <p class="account-heading__subtitle">@lang('A 6-digit verification code has been sent to') {{ showEmailAddress(auth()->user()->email) }}
                        </p>
                    </div>
                </div>

                <div class="account-body w-100">
                    <form method="POST" action="{{ route('user.2fa.verify') }}" class="account-form submit-form">
                        @csrf

                        <div class="row gy-3">

                            <div class="col-12">
                                @include($activeTemplate . 'partials.verification_code')
                            </div>
                        </div>

                        <button class="w-100 btn btn--base mt-3"
                            type="submit">{{ __($twoFactorVerificationContent?->submit_button_text ?? '') }}</button>
                    </form>
                </div>
            </div>
            <div class="account-thumb bg-img"
                data-background-image="{{ frontendImage('2fa_verification', $twoFactorVerificationContent?->account_image, '950x990') }}">
            </div>
        </section>
    </main>
@endsection