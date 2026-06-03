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
                        <p class="account-heading__subtitle">@lang('A 6-digit verification code has been sent to')
                            {{ showMobileNumber(auth()->user()->mobileNumber) }}</p>
                    </div>
                </div>
                <div class="account-body">
                    <form method="POST" action="{{ route('user.verify.mobile') }}" class="account-form submit-form">
                        @csrf
                        <div class="row gy-3">
                            <div class="col-12">
                                @include($activeTemplate . 'partials.verification_code')
                            </div>
                            <div class="col-12">
                                @lang("Didn't get the code?")
                                <span class="countdown-wrapper">
                                    @lang('You can retry in') <span id="countdown" class="fw-bold">--</span> @lang('seconds.')
                                </span>
                                <a href="{{ route('user.send.verify.code', 'sms') }}" class="try-again-link d-none">
                                    @lang('Try again.')
                                </a>
                                <a href="{{ route('user.logout') }}">@lang('Logout')</a>
                            </div>
                        </div>
                        <button class="w-100 btn btn--base mt-3"
                            type="submit">{{ __($authContent?->submit_button_text ?? trans('Submit')) }}</button>
                    </form>
                </div>
            </div>
            <div class="account-thumb bg-img"
                data-background-image="{{ frontendImage('auth', $authContent?->account_image, '950x990') }}">
            </div>
        </section>
    </main>
@endsection

@push('script')
    <script>
        (function($) {
            'use strict';

            let distance = Number('{{ @$user->ver_code_send_at->addMinutes(2)->timestamp - time() }}');
            let x = setInterval(() => {
                distance--;
                document.getElementById('countdown').innerHTML = distance;

                if (distance <= 0) {
                    clearInterval(x);
                    document.querySelector('.countdown-wrapper').classList.add('d-none');
                    document.querySelector('.try-again-link').classList.remove('d-none');
                }
            }, 1000);
        })(jQuery);
    </script>
@endpush
