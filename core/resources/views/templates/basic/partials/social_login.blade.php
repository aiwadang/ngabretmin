@php
    $socialCredentials = gs('socialite_credentials');
@endphp
@if (
    @$socialCredentials->linkedin->status == Status::ENABLE ||
        @$socialCredentials->facebook->status == Status::ENABLE ||
        @$socialCredentials->google->status == Status::ENABLE)
    <div class="account-divider">
        <span>@lang('Or Continue with')</span>
    </div>
    <div class="social-auth">
        @if (@$socialCredentials->google->status == Status::ENABLE)
            <a href="{{ route('user.social.login', 'google') }}" class="social-auth__btn google">
                <img src="{{ getImage('assets/images/social/google.png') }}" alt="img">
            </a>
        @endif
        @if (@$socialCredentials->facebook->status == Status::ENABLE)
            <a href="{{ route('user.social.login', 'facebook') }}" class="social-auth__btn facebook">
                <img src="{{ getImage('assets/images/social/facebook.png') }}" alt="img">
            </a>
        @endif
        @if (@$socialCredentials->linkedin->status == Status::ENABLE)
            <a href="{{ route('user.social.login', 'linkedin') }}" class="social-auth__btn linkedin">
                <img src="{{ getImage('assets/images/social/linkedin.png') }}" alt="img">
            </a>
        @endif
    </div>
@endif
