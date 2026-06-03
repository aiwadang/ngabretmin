@extends($activeTemplate . 'layouts.app')
@section('app-content')
    @if (gs('registration'))
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
                        <div class="account-heading mb-3">
                            <h4 class="account-heading__title">{{ __($authContent?->register_heading ?? '') }}</h4>
                            <p class="account-heading__subtitle">{{ __($authContent?->register_subheading ?? '') }}</p>
                        </div>
                        <div class="account-header__tab-wrapper">
                            <div class="account-header__tab">
                                <a class="account-header__tab-link" href="{{ route('user.login') }}">@lang('Sign In')</a>
                                <a class="account-header__tab-link active"
                                    href="{{ route('user.register') }}">@lang('Signup')</a>
                            </div>
                        </div>
                    </div>

                    <div class="account-body">
                        <form action="{{ route('user.register') }}" class="account-form verify-gcaptcha" method="POST">
                            @csrf
                            <div class="row gy-3">
                                <div class="col-12">
                                    <div class="input-group-custom">
                                        <span class="input-group-custom__icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24"
                                                x="0" y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 512 512"
                                                xml:space="preserve" class="">
                                                <g>
                                                    <path
                                                        d="M6.75 7.5A5.25 5.25 0 1 1 12 12.75 5.25 5.25 0 0 1 6.75 7.5zM21 18.24a6.91 6.91 0 0 0-6.61-5H9.62a6.92 6.92 0 0 0-6.61 5H3a2.75 2.75 0 0 0 2.64 3.51h12.7A2.75 2.75 0 0 0 21 18.24z"
                                                        fill="CurrentColor" opacity="1" data-original="CurrentColor"
                                                        class="">
                                                    </path>
                                                </g>
                                            </svg>
                                        </span>
                                        <div class="input-group-custom__wrapper">
                                            <label class="custom-form-label" for="first_name">@lang('First Name')</label>
                                            <input class="input-group-custom__input form--control" type="text"
                                                placeholder="@lang('Enter your first name')" name="firstname"
                                                value="{{ old('firstname') }}" id="first_name" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="input-group-custom">
                                        <span class="input-group-custom__icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24"
                                                x="0" y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 512 512"
                                                xml:space="preserve" class="">
                                                <g>
                                                    <path
                                                        d="M6.75 7.5A5.25 5.25 0 1 1 12 12.75 5.25 5.25 0 0 1 6.75 7.5zM21 18.24a6.91 6.91 0 0 0-6.61-5H9.62a6.92 6.92 0 0 0-6.61 5H3a2.75 2.75 0 0 0 2.64 3.51h12.7A2.75 2.75 0 0 0 21 18.24z"
                                                        fill="CurrentColor" opacity="1" data-original="CurrentColor"
                                                        class="">
                                                    </path>
                                                </g>
                                            </svg>
                                        </span>

                                        <div class="input-group-custom__wrapper">
                                            <label class="custom-form-label" for="last_name">@lang('Last Name')</label>
                                            <input class="input-group-custom__input form--control" type="text"
                                                placeholder="@lang('Enter your last name')" name="lastname"
                                                value="{{ old('lastname') }}" id="last_name" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="input-group-custom">
                                        <span class="input-group-custom__icon">
                                            <i class="fa-solid fa-envelope"></i>
                                        </span>

                                        <div class="input-group-custom__wrapper">
                                            <label class="custom-form-label" for="email">@lang('Email')</label>
                                            <input class="input-group-custom__input form--control checkUser" type="email"
                                                placeholder="@lang('Enter your rmail')" name="email" value="{{ old('email') }}"
                                                required id="email">

                                        </div>
                                    </div>
                                    <span class="exists-error fs-14 d-none"></span>
                                </div>

                                <div class="col-12">
                                    <div class="input-group-custom">
                                        <span class="input-group-custom__icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M12 1.5C10.6076 1.5 9.27225 2.05312 8.28769 3.03769C7.30312 4.02226 6.75 5.35761 6.75 6.75V9.75C5.95435 9.75 5.19129 10.0661 4.62868 10.6287C4.06607 11.1913 3.75 11.9544 3.75 12.75V19.5C3.75 20.2956 4.06607 21.0587 4.62868 21.6213C5.19129 22.1839 5.95435 22.5 6.75 22.5H17.25C18.0456 22.5 18.8087 22.1839 19.3713 21.6213C19.9339 21.0587 20.25 20.2956 20.25 19.5V12.75C20.25 11.9544 19.9339 11.1913 19.3713 10.6287C18.8087 10.0661 18.0456 9.75 17.25 9.75V6.75C17.25 3.85 14.9 1.5 12 1.5ZM15.75 9.75V6.75C15.75 5.75544 15.3549 4.80161 14.6517 4.09835C13.9484 3.39509 12.9946 3 12 3C11.0054 3 10.0516 3.39509 9.34835 4.09835C8.64509 4.80161 8.25 5.75544 8.25 6.75V9.75H15.75Z"
                                                    fill="CurrentColor" />
                                            </svg>
                                        </span>

                                        <div class="input-group-custom__wrapper">
                                            <label class="custom-form-label" for="password">@lang('Password')</label>
                                            <input class="input-group-custom__input form--control" id="password"
                                                type="password" placeholder="@lang('Enter your password')" name="password"
                                                value="{{ old('password') }}" required>
                                            <x-strong-password />
                                        </div>

                                        <span class="input-group-custom__icon">
                                            <span class="password-show-hide fas toggle-password fa-eye-slash border-0"
                                                data-target="#password"></span>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="input-group-custom">
                                        <span class="input-group-custom__icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M12 1.5C10.6076 1.5 9.27225 2.05312 8.28769 3.03769C7.30312 4.02226 6.75 5.35761 6.75 6.75V9.75C5.95435 9.75 5.19129 10.0661 4.62868 10.6287C4.06607 11.1913 3.75 11.9544 3.75 12.75V19.5C3.75 20.2956 4.06607 21.0587 4.62868 21.6213C5.19129 22.1839 5.95435 22.5 6.75 22.5H17.25C18.0456 22.5 18.8087 22.1839 19.3713 21.6213C19.9339 21.0587 20.25 20.2956 20.25 19.5V12.75C20.25 11.9544 19.9339 11.1913 19.3713 10.6287C18.8087 10.0661 18.0456 9.75 17.25 9.75V6.75C17.25 3.85 14.9 1.5 12 1.5ZM15.75 9.75V6.75C15.75 5.75544 15.3549 4.80161 14.6517 4.09835C13.9484 3.39509 12.9946 3 12 3C11.0054 3 10.0516 3.39509 9.34835 4.09835C8.64509 4.80161 8.25 5.75544 8.25 6.75V9.75H15.75Z"
                                                    fill="CurrentColor" />
                                            </svg>
                                        </span>

                                        <div class="input-group-custom__wrapper">
                                            <label class="custom-form-label"
                                                for="confirm_password">@lang('Confirm Password')</label>
                                            <input class="input-group-custom__input form--control" id="confirm_password"
                                                type="password" placeholder="@lang('Enter your confirm password')"
                                                name="password_confirmation" required>
                                        </div>

                                        <span class="input-group-custom__icon">
                                            <span class="password-show-hide fas toggle-password fa-eye-slash border-0"
                                                data-target="#confirm_password"></span>
                                        </span>
                                    </div>
                                </div>

                                <x-captcha :isUserAuth="true" />

                                @if (gs('agree'))
                                    @php
                                        $policyPages = getContent('policy_pages.element', false, orderById: true);
                                    @endphp

                                    <div class="col-sm-12">
                                        <div class="account-form__extra">
                                            <div class="form--check gradient">
                                                <input type="checkbox" id="agree" name="agree"
                                                    @checked(old('agree')) class="form-check-input" required>
                                                <label for="agree" class="form-check-label">
                                                    @lang('I agree with')
                                                    @foreach ($policyPages as $policy)
                                                        <a href="{{ route('policy.pages', $policy->slug) }}"
                                                            class="fs-14 ms-1"
                                                            target="_blank">{{ __($policy?->data_values?->title) }}</a>
                                                        @if (!$loop->last)
                                                            ,
                                                        @endif
                                                    @endforeach
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <button class="w-100 btn btn--base mt-3" type="submit">
                                    @lang('Submit')
                                </button>

                                @include('Template::partials.social_login')

                            </div>
                        </form>
                    </div>
                </div>
                <div class="account-thumb bg-img"
                    data-background-image="{{ frontendImage('auth', $authContent?->account_image, '950x990') }}">
                </div>
            </section>
        </main>
    @else
        @php
            $riderRegisterDisabledContent = getContent('rider_register_disable.content', true)?->data_values;
        @endphp
        <div class="register__disabled">
            <div class="register__disabled__card">
                <div class="register__disabled__thumb">
                    <img src="{{ frontendImage('rider_register_disable', $riderRegisterDisabledContent?->image, '500x500') }}"
                        alt="image">
                </div>
                <h3 class="register__disabled__title">
                    {{ __($riderRegisterDisabledContent?->heading) }}
                </h3>
                <p class="register__disabled__desc">
                    {{ __($riderRegisterDisabledContent?->subheading) }}
                </p>
                <a class="btn btn--base" href="{{ route('home') }}">@lang('Browse :site_name', ['site_name' => __(gs('site_name'))])</a>
            </div>
        </div>
    @endif
@endsection

@push('style')
    <style>
        .register__disabled {
            min-height: 100vh;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 32px 20px;
            background-color: hsl(var(--base) / 0.3);
        }

        .register__disabled__card {
            width: 100%;
            max-width: 800px;
            padding: 50px 45px;
            border-radius: 24px;
            background: hsl(var(--white));

        }

        .register__disabled__thumb {
            max-width: 150px;
            width: 100%;
            padding-bottom: 30px;
            margin: 0 auto;
        }

        @media (max-width:575px) {
            .register__disabled__thumb {
                max-width: 100px;
                padding-bottom: 16px
            }
        }

        .register__disabled__thumb img {
            width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .register__disabled__title {
            margin-bottom: 12px;
        }

        .register__disabled__desc {
            margin: 0 auto;
            padding-bottom: 24px;
        }

        .account-form input[name="password"],
        .account-form input[name="password_confirmation"] {
            padding-right: 56px !important;
        }

        .account-form input[name="password"]::-ms-reveal,
        .account-form input[name="password"]::-ms-clear,
        .account-form input[name="password_confirmation"]::-ms-reveal,
        .account-form input[name="password_confirmation"]::-ms-clear {
            display: none;
        }

        .account-form .password-show-hide {
            z-index: 6;
            flex-shrink: 0;
        }

        .register-disable {
            height: 100vh;
            width: 100%;
            background-color: hsl(var(--body-bg));
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-disable.banner-bg {
            padding-top: 0;
        }

        .register-disable-image {
            max-width: 300px;
            width: 100%;
            margin: 0 auto 32px;
        }

        .register-disable-title {
            color: hsl(var(--white));
            font-size: 42px;
            margin-bottom: 18px;
            text-align: center
        }

        @media (max-width:575px) {
            .register-disable-image {
                max-width: 200px;
            }

            .register-disable-title {
                font-size: 32px;
            }
        }

        .register-disable-icon {
            font-size: 16px;
            background: rgb(255, 15, 15, .07);
            color: rgb(255, 15, 15, .8);
            border-radius: 3px;
            padding: 6px;
            margin-right: 4px;
        }

        .register-disable-desc {
            color: hsl(var(--white)/.8);
            font-size: 18px;
            max-width: 565px;
            width: 100%;
            margin: 0 auto 32px;
            text-align: center;
        }

        .register-disable-footer-link {
            color: #fff;
            background-color: #5B28FF;
            padding: 13px 24px;
            border-radius: 6px;
            text-decoration: none
        }

        .register-disable-footer-link:hover {
            background-color: #440ef4;
            color: #fff;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            'use strict';

            $('.checkUser').on('focusout', function(e) {
                let url = "{{ route('user.checkUser') }}";
                let value = $(this).val();
                let token = '{{ csrf_token() }}';

                if (!value) return;

                let data = {
                    email: value,
                    _token: token
                }

                $.post(url, data, function(response) {
                    if (response.data == true) {
                        $(".exists-error").html(`
                            @lang('You’re already part of our community!')
                            <a class="ms-1" href="{{ route('user.login') }}">@lang('Login now')</a>
                        `).removeClass('d-none').addClass("text--danger mt-1 d-block");

                        $(`button[type=submit]`).attr('disabled', true).addClass('disabled');
                    } else {
                        $(".exists-error").empty().addClass('d-none').removeClass(
                            "text--danger mt-1 d-block");
                        $(`button[type=submit]`).attr('disabled', false).removeClass('disabled');
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
