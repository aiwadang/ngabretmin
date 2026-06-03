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
                        <h4 class="account-heading__title">{{ __($authContent?->login_heading ?? '') }}</h4>
                        <p class="account-heading__subtitle">{{ __($authContent?->login_subheading ?? '') }}</p>
                    </div>
                    <div class="account-header__tab-wrapper sign-links-div">
                        <div class="account-header__tab">
                            <a class="account-header__tab-link active"
                                href="{{ route('user.login') }}">@lang('Sign In')</a>
                            <a class="account-header__tab-link" href="{{ route('user.register') }}">@lang('Signup')</a>
                        </div>
                    </div>
                </div>
                <div class="account-body account-body-div">
                    <form class="account-form no-submit-loader" id="login-form">
                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="input-group-custom">
                                    <span class="input-group-custom__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M1.5 8.66992V17.2499C1.5 18.0456 1.81607 18.8086 2.37868 19.3712C2.94129 19.9339 3.70435 20.2499 4.5 20.2499H19.5C20.2956 20.2499 21.0587 19.9339 21.6213 19.3712C22.1839 18.8086 22.5 18.0456 22.5 17.2499V8.66992L13.572 14.1629C13.0992 14.4538 12.5551 14.6078 12 14.6078C11.4449 14.6078 10.9008 14.4538 10.428 14.1629L1.5 8.66992Z"
                                                fill="CurrentColor" />
                                            <path
                                                d="M22.5 6.908V6.75C22.5 5.95435 22.1839 5.19129 21.6213 4.62868C21.0587 4.06607 20.2956 3.75 19.5 3.75H4.5C3.70435 3.75 2.94129 4.06607 2.37868 4.62868C1.81607 5.19129 1.5 5.95435 1.5 6.75V6.908L11.214 12.886C11.4504 13.0314 11.7225 13.1084 12 13.1084C12.2775 13.1084 12.5496 13.0314 12.786 12.886L22.5 6.908Z"
                                                fill="CurrentColor" />
                                        </svg>
                                    </span>
                                    <div class="input-group-custom__wrapper">
                                        <label class="custom-form-label" for="email">@lang('Email address')</label>
                                        <input class="input-group-custom__input form--control email-field" type="text"
                                            placeholder="@lang('Username or Email')" name="username" value="{{ old('username') }}"
                                            required id="email" required>
                                    </div>

                                    <span class="input-group-custom__icon username-exist-icon d-none">
                                        <svg class="text--success" xmlns="http://www.w3.org/2000/svg" version="1.1"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" x="0"
                                            y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512"
                                            xml:space="preserve" class="">
                                            <g>
                                                <g fill-rule="evenodd" clip-rule="evenodd">
                                                    <path fill="currentColor"
                                                        d="M256 0C114.8 0 0 114.8 0 256s114.8 256 256 256 256-114.8 256-256S397.2 0 256 0z"
                                                        opacity="1" data-original="#4bae4f" class=""></path>
                                                    <path fill="#ffffff"
                                                        d="M379.8 169.7c6.2 6.2 6.2 16.4 0 22.6l-150 150c-3.1 3.1-7.2 4.7-11.3 4.7s-8.2-1.6-11.3-4.7l-75-75c-6.2-6.2-6.2-16.4 0-22.6s16.4-6.2 22.6 0l63.7 63.7 138.7-138.7c6.2-6.3 16.4-6.3 22.6 0z"
                                                        opacity="1" data-original="currentColor"></path>
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <div class="col-12">
                                <button class="w-100 btn btn--base" type="submit">@lang('Continue')</button>
                            </div>

                            @include('Template::partials.social_login')
                        </div>
                    </form>
                </div>
            </div>
            <div class="account-thumb bg-img"
                data-background-image="{{ frontendImage('auth', $authContent?->account_image, '950x990') }}"></div>
        </section>
    </main>
@endsection

@push('style')
    <style>
        .account-form input[name="password"] {
            padding-right: 56px !important;
        }

        .account-form input[name="password"]::-ms-reveal,
        .account-form input[name="password"]::-ms-clear {
            display: none;
        }

        .account-form .password-show-hide {
            z-index: 6;
            flex-shrink: 0;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            'use strict';

            const $accountBodyDiv = $('.account-body-div');

            const buttonLoader = (button, loading) => {

                if (loading) {
                    if (!button.data('original-html')) {
                        button.data('original-html', button.html());
                    }

                    button.addClass('disabled').attr("disabled", true).html(`
                        <div class="button-loader d-flex gap-2 flex-wrap align-items-center justify-content-center">
                            <div class="spinner-border"></div><span>Loading...</span>
                        </div>
                    `);
                } else {
                    button.removeClass('disabled').attr('disabled', false).html(button.data('original-html'));
                }

            };

            $('#login-form').on('submit', e => {
                e.preventDefault();

                const $form = $(e.currentTarget);
                const $button = $form.find('button[type="submit"]');
                const action = "{{ route('user.login.check.email') }}";
                const payload = {
                    username: $form.find('input[name="username"]').val()
                };

                if (!payload.username) {
                    return notify('error', "{{ __('Username is required') }}");
                }

                buttonLoader($button, true);

                $.get(action, payload, null, 'json')
                    .done(({
                        status,
                        message,
                        data = {}
                    }) => {

                        if (status === 'success') {
                            $('.account-divider, .sign-links-div').addClass('d-none');
                            $accountBodyDiv.html(data?.html);
                        } else {
                            notify('error', message);
                        }
                    }).fail(() => {
                        notify('error', "{{ __('An error occurred while processing your request') }}");
                    }).always(() => {
                        buttonLoader($button, false);
                    });
            });

        })(jQuery);
    </script>
@endpush
