@php
    $loginContent = getContent('login.content', true)?->data_values;
@endphp
<form method="POST" action="{{ route('user.login') }}" class="account-form verify-gcaptcha" id="">
    @csrf

    <div class="row gy-3">
        <div class="col-12">
            <div class="input-group-custom">
                <span class="input-group-custom__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none">
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
                    <input class="input-group-custom__input form--control" type="text"
                        placeholder="@lang('Username or Email')" name="username" value="{{ $value }}" required
                        id="email" readonly>
                </div>

                <span class="input-group-custom__icon">
                    <svg class="text--success" xmlns="http://www.w3.org/2000/svg" version="1.1"
                        xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" x="0" y="0"
                        viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve"
                        class="">
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
            <div class="input-group-custom">
                <span class="input-group-custom__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M12 1.5C10.6076 1.5 9.27225 2.05312 8.28769 3.03769C7.30312 4.02226 6.75 5.35761 6.75 6.75V9.75C5.95435 9.75 5.19129 10.0661 4.62868 10.6287C4.06607 11.1913 3.75 11.9544 3.75 12.75V19.5C3.75 20.2956 4.06607 21.0587 4.62868 21.6213C5.19129 22.1839 5.95435 22.5 6.75 22.5H17.25C18.0456 22.5 18.8087 22.1839 19.3713 21.6213C19.9339 21.0587 20.25 20.2956 20.25 19.5V12.75C20.25 11.9544 19.9339 11.1913 19.3713 10.6287C18.8087 10.0661 18.0456 9.75 17.25 9.75V6.75C17.25 3.85 14.9 1.5 12 1.5ZM15.75 9.75V6.75C15.75 5.75544 15.3549 4.80161 14.6517 4.09835C13.9484 3.39509 12.9946 3 12 3C11.0054 3 10.0516 3.39509 9.34835 4.09835C8.64509 4.80161 8.25 5.75544 8.25 6.75V9.75H15.75Z"
                            fill="CurrentColor" />
                    </svg>
                </span>

                <div class="input-group-custom__wrapper">
                    <label class="custom-form-label" for="password">@lang('Password')</label>
                    <input class="input-group-custom__input form--control" id="password" type="password"
                        value="{{ old('password') }}" placeholder="@lang('Password')" name="password" required
                        id="password">
                </div>
                <span class="input-group-custom__icon">
                    <span class="password-show-hide fas toggle-password fa-eye-slash border-0"
                        data-target="#password"></span>
                </span>
            </div>
        </div>

        <x-captcha :isUserAuth="true" />

        <div class="col-sm-12">
            <div class="account-form__extra">
                <div class="form--check">
                    <input class="form-check-input" type="checkbox" id="remember-me" name="remember"
                        {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember-me">@lang('Remember Me')</label>
                </div>
            </div>
        </div>

        <div class="col-12">
            <button class="w-100 btn btn--base"
                type="submit">{{ __($loginContent?->submit_button_text ?? 'Log In') }}</button>
        </div>

    </div>
</form>

<div class="col-sm-12 mt-4 forgot-password-link">
    <div class="account-form__extra display-flex justify-content-center">
        <a href="{{ route('user.password.request') }}" class="account-form__forgot-link">@lang('Forgot Password')?</a>
    </div>
</div>
