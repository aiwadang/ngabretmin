@extends($activeTemplate . 'layouts.auth')
@section('auth')
    <div class="driver-dashboard-content">
        <div class="driver-dashboard__profile">
            <form action="" method="POST" class="driver-dashboard__profile-form  style-two">
                @csrf
                <div class="driver-form-heading">
                    <h6 class="title mb-2">
                        @lang('Change Password')
                    </h6>
                    <p class="subtitle">
                        @lang('Update your password to keep your account secure.')
                    </p>
                </div>
                <div class="input-group-custom mb-3">
                    <span class="input-group-custom__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none">
                            <path opacity="0.4" d="M12 16.5V14.5" stroke="hsl(var(--base))" stroke-width="1.5"
                                stroke-linecap="round" />
                            <path
                                d="M4.26781 18.8447C4.49269 20.515 5.87613 21.8235 7.55966 21.9009C8.97628 21.966 10.4153 22 12 22C13.5847 22 15.0237 21.966 16.4403 21.9009C18.1239 21.8235 19.5073 20.515 19.7322 18.8447C19.8789 17.7547 20 16.6376 20 15.5C20 14.3624 19.8789 13.2453 19.7322 12.1553C19.5073 10.485 18.1239 9.17649 16.4403 9.09909C15.0237 9.03397 13.5847 9 12 9C10.4153 9 8.97628 9.03397 7.55966 9.09909C5.87613 9.17649 4.49269 10.485 4.26781 12.1553C4.12105 13.2453 4 14.3624 4 15.5C4 16.6376 4.12105 17.7547 4.26781 18.8447Z"
                                stroke="hsl(var(--base))" stroke-width="1.5" />
                            <path opacity="0.4" d="M7.5 9V6.5C7.5 4.01472 9.51472 2 12 2C14.4853 2 16.5 4.01472 16.5 6.5V9"
                                stroke="hsl(var(--base))" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </span>

                    <div class="input-group-custom__wrapper">
                        <input class="input-group-custom__input form--control" type="password"
                            placeholder="@lang('Current Password')" name="current_password" autocomplete="current-password">
                    </div>

                    <span class="form-check-icon text-nowrap text--base fw-700">
                        @lang('show')
                    </span>
                </div>
                <div class="input-group-custom mb-3">
                    <span class="input-group-custom__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none">
                            <path opacity="0.4" d="M12 16.5V14.5" stroke="hsl(var(--base))" stroke-width="1.5"
                                stroke-linecap="round" />
                            <path
                                d="M4.26781 18.8447C4.49269 20.515 5.87613 21.8235 7.55966 21.9009C8.97628 21.966 10.4153 22 12 22C13.5847 22 15.0237 21.966 16.4403 21.9009C18.1239 21.8235 19.5073 20.515 19.7322 18.8447C19.8789 17.7547 20 16.6376 20 15.5C20 14.3624 19.8789 13.2453 19.7322 12.1553C19.5073 10.485 18.1239 9.17649 16.4403 9.09909C15.0237 9.03397 13.5847 9 12 9C10.4153 9 8.97628 9.03397 7.55966 9.09909C5.87613 9.17649 4.49269 10.485 4.26781 12.1553C4.12105 13.2453 4 14.3624 4 15.5C4 16.6376 4.12105 17.7547 4.26781 18.8447Z"
                                stroke="hsl(var(--base))" stroke-width="1.5" />
                            <path opacity="0.4" d="M7.5 9V6.5C7.5 4.01472 9.51472 2 12 2C14.4853 2 16.5 4.01472 16.5 6.5V9"
                                stroke="hsl(var(--base))" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </span>

                    <div class="input-group-custom__wrapper">
                        <input
                            class="input-group-custom__input form--control @gs('secure_password')
secure-password
@endgs"
                            type="password" placeholder="@lang('New Password')" name="password" required
                            autocomplete="current-password">
                    </div>

                    <span class="form-check-icon text-nowrap text--base fw-700">
                        @lang('show')
                    </span>
                </div>
                <div class="input-group-custom mb-3">
                    <span class="input-group-custom__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none">
                            <path opacity="0.4" d="M12 16.5V14.5" stroke="hsl(var(--base))" stroke-width="1.5"
                                stroke-linecap="round" />
                            <path
                                d="M4.26781 18.8447C4.49269 20.515 5.87613 21.8235 7.55966 21.9009C8.97628 21.966 10.4153 22 12 22C13.5847 22 15.0237 21.966 16.4403 21.9009C18.1239 21.8235 19.5073 20.515 19.7322 18.8447C19.8789 17.7547 20 16.6376 20 15.5C20 14.3624 19.8789 13.2453 19.7322 12.1553C19.5073 10.485 18.1239 9.17649 16.4403 9.09909C15.0237 9.03397 13.5847 9 12 9C10.4153 9 8.97628 9.03397 7.55966 9.09909C5.87613 9.17649 4.49269 10.485 4.26781 12.1553C4.12105 13.2453 4 14.3624 4 15.5C4 16.6376 4.12105 17.7547 4.26781 18.8447Z"
                                stroke="hsl(var(--base))" stroke-width="1.5" />
                            <path opacity="0.4" d="M7.5 9V6.5C7.5 4.01472 9.51472 2 12 2C14.4853 2 16.5 4.01472 16.5 6.5V9"
                                stroke="hsl(var(--base))" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </span>

                    <div class="input-group-custom__wrapper">
                        <input class="input-group-custom__input form--control" type="password"
                            placeholder="@lang('Confirm Password')" name="password_confirmation" required
                            autocomplete="current-password">
                    </div>

                    <span class="form-check-icon text-nowrap text--base fw-700">
                        @lang('show')
                    </span>
                </div>
                <button
                    class="btn btn--base w-100">{{ __($passwordChangeContent?->submit_button_text ?? 'Submit') }}</button>

            </form>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .driver-dashboard__profile-form input[name="current_password"],
        .driver-dashboard__profile-form input[name="password"],
        .driver-dashboard__profile-form input[name="password_confirmation"] {
            padding-right: 56px !important;
        }

        .driver-dashboard__profile-form input[name="current_password"]::-ms-reveal,
        .driver-dashboard__profile-form input[name="current_password"]::-ms-clear,
        .driver-dashboard__profile-form input[name="password"]::-ms-reveal,
        .driver-dashboard__profile-form input[name="password"]::-ms-clear,
        .driver-dashboard__profile-form input[name="password_confirmation"]::-ms-reveal,
        .driver-dashboard__profile-form input[name="password_confirmation"]::-ms-clear {
            display: none;
        }

        .input-group-custom::before {
            background-color: transparent
        }
    </style>
@endpush

@gs('secure_password')
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endgs
