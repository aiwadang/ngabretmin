@extends($activeTemplate . 'layouts.app')
@section('app-content')
    @php
        $authContent = getContent('auth.content', true)?->data_values;
    @endphp
    <main class="page-wrapper">
        <section class="account">
            <div class="account-content bg-img"
                data-background-image="{{ frontendImage('auth', $authContent?->background_image, '970x990') }}">
                <div class="account-header mb-4 text-center">
                    <a class="account-logo mb-4" href="{{ route('home') }}">
                        <img src="{{ getImage(getFilePath('logoIcon') . '/' . 'logo.png') }}" alt="">
                    </a>
                    <div class="account-heading mb-2">
                        <h5 class="mb-2">{{ __($pageTitle) }}</h5>
                        <p class="account-heading__subtitle">@lang('Create a new password to complete the reset process.')</p>
                    </div>
                </div>
                <div class="account-body">
                    <form method="POST" action="{{ route('user.password.update') }}" class="account-form">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="row gy-3">

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
                                        <input
                                            class="input-group-custom__input form--control @gs('secure_password')
secure-password
@endgs"
                                            type="password" placeholder="@lang('Enter new password')" name="password" required
                                            id="password">
                                    </div>
                                    <span class="input-group-custom__icon">
                                        <span class="password-show-hide fas toggle-password fa-eye-slash border-0"
                                            data-target="#password">
                                        </span>
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
                                        <label class="custom-form-label" for="confirm_password">@lang('Confirm Password')</label>
                                        <input class="input-group-custom__input form--control" type="password"
                                            placeholder="@lang('Confirm password')" name="password_confirmation" required
                                            id="confirm_password">
                                    </div>
                                    <span class="input-group-custom__icon">
                                        <span class="password-show-hide fas toggle-password fa-eye-slash border-0"
                                            data-target="#confirm_password">
                                        </span>
                                    </span>
                                </div>
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

@push('style')
    <style>
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
    </style>
@endpush

@gs('secure_password')
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endgs
