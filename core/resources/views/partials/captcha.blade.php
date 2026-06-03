@php
    $customCaptcha = loadCustomCaptcha();
    $googleCaptcha = loadReCaptcha();
@endphp

@props(['isAdmin' => false, 'isUserAuth' => false])

@if ($googleCaptcha)
    <div class="{{ !$isUserAuth ? 'mb-3' : 'col-12' }}">
        @php echo $googleCaptcha @endphp
    </div>
@endif

@if ($customCaptcha)
    @if ($isAdmin)
        <div class="form-group custom-captcha">
            @php echo $customCaptcha @endphp
        </div>
        <div class="form-group">
            <label for="password" class="form--label">@lang('Captcha')</label>
            <div class="position-relative">
                <input id="captcha" name="captcha" required type="captcha" class="form--control h-48">
                <span class="password-show-hide fas toggle-password fa-eye-slash" id="#password"></span>
            </div>
        </div>
    @else
        @if ($isUserAuth)
            <div class="col-12">
                <div class="input-group-custom">
                    <span class="input-group-custom__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
                            color="currentColor" fill="none" stroke="#141B34" stroke-width="1.5">
                            <path
                                d="M2 6C2 5.05719 2 4.58579 2.29289 4.29289C2.58579 4 3.05719 4 4 4C4.94281 4 5.41421 4 5.70711 4.29289C6 4.58579 6 5.05719 6 6V8C6 8.94281 6 9.41421 5.70711 9.70711C5.41421 10 4.94281 10 4 10C3.05719 10 2.58579 10 2.29289 9.70711C2 9.41421 2 8.94281 2 8V6Z" />
                            <path
                                d="M6.5 16C6.5 15.0572 6.5 14.5858 6.79289 14.2929C7.08579 14 7.55719 14 8.5 14C9.44281 14 9.91421 14 10.2071 14.2929C10.5 14.5858 10.5 15.0572 10.5 16V18C10.5 18.9428 10.5 19.4142 10.2071 19.7071C9.91421 20 9.44281 20 8.5 20C7.55719 20 7.08579 20 6.79289 19.7071C6.5 19.4142 6.5 18.9428 6.5 18V16Z" />
                            <path
                                d="M13.5 6C13.5 5.05719 13.5 4.58579 13.7929 4.29289C14.0858 4 14.5572 4 15.5 4C16.4428 4 16.9142 4 17.2071 4.29289C17.5 4.58579 17.5 5.05719 17.5 6V8C17.5 8.94281 17.5 9.41421 17.2071 9.70711C16.9142 10 16.4428 10 15.5 10C14.5572 10 14.0858 10 13.7929 9.70711C13.5 9.41421 13.5 8.94281 13.5 8V6Z" />
                            <path
                                d="M13.5 16C13.5 15.0572 13.5 14.5858 13.7929 14.2929C14.0858 14 14.5572 14 15.5 14C16.4428 14 16.9142 14 17.2071 14.2929C17.5 14.5858 17.5 15.0572 17.5 16V18C17.5 18.9428 17.5 19.4142 17.2071 19.7071C16.9142 20 16.4428 20 15.5 20C14.5572 20 14.0858 20 13.7929 19.7071C13.5 19.4142 13.5 18.9428 13.5 18V16Z" />
                            <path d="M9 5L10.5 4V10" />
                            <path d="M2 15L3.5 14V20" />
                            <path d="M20.5 5L22 4V10" />
                            <path d="M20.5 15L22 14V20" />
                        </svg>
                    </span>
                    <div class="input-group-custom__wrapper">
                        @php echo $customCaptcha @endphp
                    </div>
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class="input-group-custom">
                    <span class="input-group-custom__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none">
                            <path d="M4 4V9H9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M20 20V15H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M20 9C19.5 7.5 18.5 6.2 17.2 5.3C15.9 4.4 14.3 4 12.7 4C10.8 4 9 4.7 7.6 6"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M4 15C4.5 16.5 5.5 17.8 6.8 18.7C8.1 19.6 9.7 20 11.3 20C13.2 20 15 19.3 16.4 18"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </span>
                    <div class="input-group-custom__wrapper">
                        <label class="custom-form-label" for="captcha">@lang('Captcha')</label>
                        <input class="input-group-custom__input form--control" type="text"
                            placeholder="@lang('Enter captcha')" name="captcha" id="captcha" required>
                    </div>
                </div>
            </div>
            </div>
        @else
            <div class="form-group mb-3">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="border border--base-two border-2  rounded">
                            @php echo $customCaptcha @endphp
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <input type="text" name="captcha" class="form--control  " required
                            placeholder="@lang('Enter captcha')">
                    </div>
                </div>
            </div>
        @endif

    @endif

@endif
@if ($googleCaptcha)
    @push('script')
        <script>
            (function($) {
                "use strict"
                $('.verify-gcaptcha').on('submit', function() {
                    var response = grecaptcha.getResponse();
                    if (response.length == 0) {
                        document.getElementById('g-recaptcha-error').innerHTML =
                            '<span class="text--danger">@lang('Captcha field is required.')</span>';
                        return false;
                    }
                    return true;
                });

                window.verifyCaptcha = () => {
                    document.getElementById('g-recaptcha-error').innerHTML = '';
                }
            })(jQuery);
        </script>
    @endpush
@endif
