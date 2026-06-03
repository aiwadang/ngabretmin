@php
    $languages = App\Models\Language::get();
    $selectLang = $languages->where('code', config('app.locale'))->first();
    $homeUrl = request()->routeIs('home') ? '' : route('home');
    $user = auth()->user();
@endphp

<header class="header @if (!$homeUrl) home-header @endif" id="header">
    <div class="locationPermissionAlertBox"></div>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand logo" href="{{ route('home') }}">
                <img src="{{ siteLogo('dark') }}" alt="logo">
            </a>
            <button class="navbar-toggler header-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span id="hiddenNav"><i class="las la-bars"></i></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-menu me-auto align-items-lg-center">
                    @gs('multi_language')
                        <li class="nav-item d-inline-block d-lg-none">
                            <div class="custom__lang-profile d-flex gap-3 justify-content-between">
                                <div class="user-info header-author-dropdown">
                                    @auth
                                        <button class="user-info__button btn--white d-flex align-items-center" tabindex="-1">
                                            <span class="thumb">
                                                <img src="{{ $user->image_src }}" alt="img">
                                            </span>
                                            <span class="content">
                                                <span class="name d-block">{{ $user->username }}</span>
                                            </span>
                                        </button>
                                        <div class="user-info-dropdown">
                                            <div class="user-info-dropdown__header">
                                                <div class="user-info-dropdown__author">
                                                    <h6 class="title">{{ __($user->fullname) }}</h6>
                                                    <a href="tel:01986892364" class="phone-number"><span><svg
                                                                xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                                viewBox="0 0 20 20" fill="none">
                                                                <path
                                                                    d="M3.95032 11.9738L5.57792 10.818C6.03704 10.492 6.50254 10.2735 7.03362 10.1321C7.64334 9.96967 7.91667 9.63701 7.91667 8.92584C7.91667 7.12191 12.0833 6.92996 12.0833 8.92584C12.0833 9.63701 12.3567 9.96967 12.9664 10.1321C13.5017 10.2746 13.9666 10.4945 14.4221 10.818L16.0497 11.9738C16.7862 12.4968 17.1289 12.7496 17.3202 13.1516C17.5 13.5298 17.5 13.9733 17.5 14.8603C17.5 16.4551 17.5 17.2525 17.0535 17.7637C16.5127 18.3828 15.1067 18.3296 14.2431 18.3296H5.75694C4.89331 18.3296 3.51591 18.4155 2.9465 17.7637C2.5 17.2525 2.5 16.4551 2.5 14.8603C2.5 13.9733 2.5 13.5298 2.67987 13.1516C2.87105 12.7496 3.21385 12.4968 3.95032 11.9738Z"
                                                                    stroke="#7C4DFF" stroke-width="1.5" />
                                                                <path opacity="0.4"
                                                                    d="M11.6668 14.1667C11.6668 15.0872 10.9207 15.8333 10.0002 15.8333C9.07966 15.8333 8.3335 15.0872 8.3335 14.1667C8.3335 13.2462 9.07966 12.5 10.0002 12.5C10.9207 12.5 11.6668 13.2462 11.6668 14.1667Z"
                                                                    stroke="#7C4DFF" stroke-width="1.5" />
                                                                <path opacity="0.4"
                                                                    d="M5.79996 3.08143C4.70126 3.39512 3.91137 3.78426 3.18855 4.25379C2.04416 4.99716 1.55353 6.3367 1.68823 7.62927C1.74516 8.17556 2.18371 8.43675 2.69225 8.29546C3.0786 8.18809 3.46557 8.08358 3.84951 7.9697C4.97239 7.63664 5.2365 7.23176 5.39254 6.08237L5.79996 3.08143ZM5.79996 3.08143C8.51534 2.30619 11.4843 2.30619 14.1998 3.08143M14.1998 3.08143C15.2984 3.39512 16.0883 3.78426 16.8111 4.25379C17.9555 4.99716 18.4462 6.3367 18.3114 7.62927C18.2545 8.17556 17.816 8.43675 17.3074 8.29546C16.9211 8.18809 16.5341 8.08358 16.1502 7.9697C15.0273 7.63664 14.7632 7.23176 14.6072 6.08237L14.1998 3.08143Z"
                                                                    stroke="#7C4DFF" stroke-width="1.5"
                                                                    stroke-linejoin="round" />
                                                            </svg></span>
                                                        <span>+{{ $user->dial_code . $user->mobile }}</span></a>
                                                </div>
                                                <div class="user-info-dropdown__author-thumb">
                                                    <img src="{{ $user->image_src }}" alt="">
                                                </div>
                                            </div>
                                            <div class="user-info-dropdown__body">
                                                <div class="user-info-dropdown__menu">
                                                    <a href="{{ route('user.home') }}" class="user-info-dropdown__menu-item">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                            width="24" height="24" color="currentColor" fill="none"
                                                            stroke="#141B34" stroke-width="1.5" stroke-linejoin="round">
                                                            <path
                                                                d="M9.75 3H5.75C5.05222 3 4.70333 3 4.41943 3.08612C3.78023 3.28002 3.28002 3.78023 3.08612 4.41943C3 4.70333 3 5.05222 3 5.75C3 6.44778 3 6.79667 3.08612 7.08057C3.28002 7.71977 3.78023 8.21998 4.41943 8.41388C4.70333 8.5 5.05222 8.5 5.75 8.5H9.75C10.4478 8.5 10.7967 8.5 11.0806 8.41388C11.7198 8.21998 12.22 7.71977 12.4139 7.08057C12.5 6.79667 12.5 6.44778 12.5 5.75C12.5 5.05222 12.5 4.70333 12.4139 4.41943C12.22 3.78023 11.7198 3.28002 11.0806 3.08612C10.7967 3 10.4478 3 9.75 3Z" />
                                                            <path
                                                                d="M21 9.75V5.75C21 5.05222 21 4.70333 20.9139 4.41943C20.72 3.78023 20.2198 3.28002 19.5806 3.08612C19.2967 3 18.9478 3 18.25 3C17.5522 3 17.2033 3 16.9194 3.08612C16.2802 3.28002 15.78 3.78023 15.5861 4.41943C15.5 4.70333 15.5 5.05222 15.5 5.75V9.75C15.5 10.4478 15.5 10.7967 15.5861 11.0806C15.78 11.7198 16.2802 12.22 16.9194 12.4139C17.2033 12.5 17.5522 12.5 18.25 12.5C18.9478 12.5 19.2967 12.5 19.5806 12.4139C20.2198 12.22 20.72 11.7198 20.9139 11.0806C21 10.7967 21 10.4478 21 9.75Z" />
                                                            <path
                                                                d="M16.9194 20.9139C17.2033 21 17.5522 21 18.25 21C18.9478 21 19.2967 21 19.5806 20.9139C20.2198 20.72 20.72 20.2198 20.9139 19.5806C21 19.2967 21 18.9478 21 18.25C21 17.5522 21 17.2033 20.9139 16.9194C20.72 16.2802 20.2198 15.78 19.5806 15.5861C19.2967 15.5 18.9478 15.5 18.25 15.5C17.5522 15.5 17.2033 15.5 16.9194 15.5861C16.2802 15.78 15.78 16.2802 15.5861 16.9194C15.5 17.2033 15.5 17.5522 15.5 18.25C15.5 18.9478 15.5 19.2967 15.5861 19.5806C15.78 20.2198 16.2802 20.72 16.9194 20.9139Z" />
                                                            <path
                                                                d="M8.5 11.5H7C5.11438 11.5 4.17157 11.5 3.58579 12.0858C3 12.6716 3 13.6144 3 15.5V17C3 18.8856 3 19.8284 3.58579 20.4142C4.17157 21 5.11438 21 7 21H8.5C10.3856 21 11.3284 21 11.9142 20.4142C12.5 19.8284 12.5 18.8856 12.5 17V15.5C12.5 13.6144 12.5 12.6716 11.9142 12.0858C11.3284 11.5 10.3856 11.5 8.5 11.5Z" />
                                                        </svg>
                                                        <span class="menu-name">@lang('Dashboard')</span>
                                                    </a>
                                                    <a href="{{ route('user.deposit.history') }}"
                                                        class="user-info-dropdown__menu-item">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                            viewBox="0 0 32 32" fill="none">
                                                            <g clip-path="url(#clip0_transaction)">
                                                                <mask id="mask0_transaction" style="mask-type:luminance"
                                                                    maskUnits="userSpaceOnUse" x="0" y="0" width="32"
                                                                    height="32">
                                                                    <rect width="32" height="32" fill="white" />
                                                                </mask>

                                                                <g mask="url(#mask0_transaction)">

                                                                    <!-- Outer circle -->
                                                                    <path opacity="0.4"
                                                                        d="M16 29.333C9.556 29.333 4.333 24.111 4.333 17.667C4.333 11.222 9.556 6 16 6C22.444 6 27.667 11.222 27.667 17.667C27.667 24.111 22.444 29.333 16 29.333Z"
                                                                        fill="#475569" />

                                                                    <!-- Top arrow -->
                                                                    <path opacity="0.8"
                                                                        d="M10.5 12.333H19.667L17.833 10.5C17.444 10.111 17.444 9.444 17.833 9.056C18.222 8.667 18.889 8.667 19.278 9.056L22.333 12.111C22.722 12.5 22.722 13.167 22.333 13.556L19.278 16.611C18.889 17 18.222 17 17.833 16.611C17.444 16.222 17.444 15.556 17.833 15.167L19.667 13.333H10.5C9.944 13.333 9.5 12.889 9.5 12.333C9.5 11.778 9.944 12.333 10.5 12.333Z"
                                                                        fill="#475569" />

                                                                    <!-- Bottom arrow -->
                                                                    <path opacity="0.6"
                                                                        d="M22.333 20.389C22.722 20.833 22.722 21.5 22.333 21.944L19.278 25C18.889 25.389 18.222 25.389 17.833 25C17.444 24.611 17.444 23.944 17.833 23.556L19.667 21.667H10.5C9.944 21.667 9.5 21.222 9.5 20.667C9.5 20.111 9.944 19.667 10.5 19.667H19.667L17.833 17.833C17.444 17.444 17.444 16.778 17.833 16.389C18.222 16 18.889 16 19.278 16.389L22.333 19.444Z"
                                                                        fill="#475569" />
                                                                </g>
                                                            </g>

                                                            <defs>
                                                                <clipPath id="clip0_transaction">
                                                                    <rect width="32" height="32" fill="white" />
                                                                </clipPath>
                                                            </defs>
                                                        </svg>

                                                        <span class="menu-name">@lang('Payments')</span>

                                                    </a>
                                                    <a href="{{ route('user.activity') }}"
                                                        class="user-info-dropdown__menu-item">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                            viewBox="0 0 32 32" fill="none">
                                                            <path opacity="0.4"
                                                                d="M20.652 1.87094C19.1331 1.66671 17.1923 1.66672 14.7419 1.66675C12.2916 1.66672 10.2003 1.66671 8.68131 1.87094C7.11808 2.0811 5.85281 2.52392 4.85499 3.52174C3.85717 4.51956 3.41435 5.78483 3.20419 7.34806C2.99996 8.867 2.99997 10.8078 3 13.2582V18.7419C2.99997 21.1923 2.99996 23.1331 3.20419 24.6521C3.41435 26.2153 3.85717 27.4806 4.85499 28.4785C5.85281 29.4762 7.11808 29.919 8.68131 30.1293C10.2003 30.3334 12.2915 30.3334 14.7419 30.3334C15.5031 30.3334 16.2152 30.3334 16.8808 30.3273C14.7299 28.7502 13.3333 26.2049 13.3333 23.3334C13.3333 18.5469 17.2135 14.6667 22 14.6667C23.5785 14.6667 25.0585 15.0887 26.3333 15.8262V13.2582C26.3333 10.8079 26.3333 8.86699 26.1292 7.34806C25.9189 5.78483 25.4761 4.51956 24.4784 3.52174C23.4805 2.52392 22.2152 2.0811 20.652 1.87094Z"
                                                                fill="#475569" />
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M8.3335 9.33325C8.3335 8.78097 8.78122 8.33325 9.3335 8.33325H20.0002C20.5524 8.33325 21.0002 8.78097 21.0002 9.33325C21.0002 9.88553 20.5524 10.3333 20.0002 10.3333H9.3335C8.78122 10.3333 8.3335 9.88553 8.3335 9.33325ZM8.3335 14.6666C8.3335 14.1143 8.78122 13.6666 9.3335 13.6666H15.3335C15.8858 13.6666 16.3335 14.1143 16.3335 14.6666C16.3335 15.2189 15.8858 15.6666 15.3335 15.6666H9.3335C8.78122 15.6666 8.3335 15.2189 8.3335 14.6666Z"
                                                                fill="#475569" />
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M22 16.3333C18.134 16.3333 15 19.4673 15 23.3333C15 27.1993 18.134 30.3333 22 30.3333C25.866 30.3333 29 27.1993 29 23.3333C29 19.4673 25.866 16.3333 22 16.3333ZM23 20.6666C23 20.1143 22.5523 19.6666 22 19.6666C21.4477 19.6666 21 20.1143 21 20.6666V23.9333C21 24.3527 21.2619 24.7277 21.6557 24.8722L23.6557 25.6055C24.1743 25.7957 24.7488 25.5294 24.9389 25.0109C25.1291 24.4923 24.8628 23.9178 24.3443 23.7277L23 23.2349V20.6666Z"
                                                                fill="#475569" />
                                                        </svg>
                                                        <span class="menu-name">@lang('Activity')</span>
                                                    </a>
                                                </div>
                                                <a href="{{ route('user.logout') }}"
                                                    class="btn logout-btn w-100 mt-4">@lang('Logout')</a>
                                            </div>
                                        </div>
                                    @endauth
                                </div>
                                <div>
                                    <div class="custom--dropdown">
                                        <div class="custom--dropdown__selected">
                                            <span class="thumb">
                                                <img class="flag" src="{{ $selectLang->image_src }}" alt="lang">
                                            </span>
                                            <span class="text">{{ strtoupper($selectLang->code) }}</span>
                                        </div>
                                        <ul class="dropdown-list">
                                            @foreach ($languages as $language)
                                                <li class="dropdown-list__item langSel"
                                                    data-value="{{ $selectLang->code }}">
                                                    <span class="thumb">
                                                        <img class="flag" src="{{ $language->image_src }}"
                                                            alt="lang">
                                                    </span>
                                                    <span class="text">{{ strtoupper($language->code) }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endgs
                    <li class="nav-item {{ menuActive('home') }}">
                        <a class="nav-link" href="{{ route('home') }}">@lang('Home')</a>
                    </li>

                    @foreach ($pages as $page)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pages', $page->slug) }}">
                                {{ __($page->name) }}
                            </a>
                        </li>
                    @endforeach

                    <li class="nav-item {{ menuActive('blog') }}">
                        <a class="nav-link" href="{{ route('blog') }}">@lang('Blog')</a>
                    </li>
                    <li class="nav-item {{ menuActive('contact') }}">
                        <a class="nav-link" href="{{ route('contact') }}">@lang('Contact')</a>
                    </li>

                </ul>
                <div class="header-right">
                    <div class="header-right__lang d-lg-block d-none">
                        @gs('multi_language')
                            <div class="nav-item d-lg-block d-none">
                                <div class="custom--dropdown">
                                    <div class="custom--dropdown__selected">
                                        <span class="thumb">
                                            <img class="flag"
                                                src="{{ getImage(getFilePath('language') . '/' . $selectLang->image) }}"
                                                alt="lang">
                                        </span>
                                        <span class="text">{{ strtoupper($selectLang->code) }}</span>
                                    </div>
                                    <ul class="dropdown-list">
                                        @foreach ($languages as $language)
                                            <li class="dropdown-list__item langSel" data-value="{{ $language->code }}">
                                                <span class="thumb">
                                                    <img class="flag" src="{{ $language->image_src }}" alt="lang">
                                                </span>
                                                <span class="text">{{ strtoupper($language->code) }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endgs
                    </div>
                    @auth
                        <div class="user-info header-author-dropdown d-lg-block">
                            <button class="user-info__button btn--white d-flex align-items-center" tabindex="-1">
                                <span class="thumb">
                                    <img src="{{ $user->image_src }}" alt="img">
                                </span>
                                <span class="content">
                                    <span class="name d-block">{{ $user->username }}</span>
                                </span>
                            </button>
                            <div class="user-info-dropdown d-none d-lg-block">
                                <div class="user-info-dropdown__header">
                                    <div class="user-info-dropdown__author">
                                        <h6 class="title">{{ __($user->fullname) }}</h6>
                                        <a href="tel:{{ $user->mobileNumber }}" class="phone-number"><span><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 20 20" fill="none">
                                                    <path
                                                        d="M3.95032 11.9738L5.57792 10.818C6.03704 10.492 6.50254 10.2735 7.03362 10.1321C7.64334 9.96967 7.91667 9.63701 7.91667 8.92584C7.91667 7.12191 12.0833 6.92996 12.0833 8.92584C12.0833 9.63701 12.3567 9.96967 12.9664 10.1321C13.5017 10.2746 13.9666 10.4945 14.4221 10.818L16.0497 11.9738C16.7862 12.4968 17.1289 12.7496 17.3202 13.1516C17.5 13.5298 17.5 13.9733 17.5 14.8603C17.5 16.4551 17.5 17.2525 17.0535 17.7637C16.5127 18.3828 15.1067 18.3296 14.2431 18.3296H5.75694C4.89331 18.3296 3.51591 18.4155 2.9465 17.7637C2.5 17.2525 2.5 16.4551 2.5 14.8603C2.5 13.9733 2.5 13.5298 2.67987 13.1516C2.87105 12.7496 3.21385 12.4968 3.95032 11.9738Z"
                                                        stroke="#7C4DFF" stroke-width="1.5" />
                                                    <path opacity="0.4"
                                                        d="M11.6668 14.1667C11.6668 15.0872 10.9207 15.8333 10.0002 15.8333C9.07966 15.8333 8.3335 15.0872 8.3335 14.1667C8.3335 13.2462 9.07966 12.5 10.0002 12.5C10.9207 12.5 11.6668 13.2462 11.6668 14.1667Z"
                                                        stroke="#7C4DFF" stroke-width="1.5" />
                                                    <path opacity="0.4"
                                                        d="M5.79996 3.08143C4.70126 3.39512 3.91137 3.78426 3.18855 4.25379C2.04416 4.99716 1.55353 6.3367 1.68823 7.62927C1.74516 8.17556 2.18371 8.43675 2.69225 8.29546C3.0786 8.18809 3.46557 8.08358 3.84951 7.9697C4.97239 7.63664 5.2365 7.23176 5.39254 6.08237L5.79996 3.08143ZM5.79996 3.08143C8.51534 2.30619 11.4843 2.30619 14.1998 3.08143M14.1998 3.08143C15.2984 3.39512 16.0883 3.78426 16.8111 4.25379C17.9555 4.99716 18.4462 6.3367 18.3114 7.62927C18.2545 8.17556 17.816 8.43675 17.3074 8.29546C16.9211 8.18809 16.5341 8.08358 16.1502 7.9697C15.0273 7.63664 14.7632 7.23176 14.6072 6.08237L14.1998 3.08143Z"
                                                        stroke="#7C4DFF" stroke-width="1.5" stroke-linejoin="round" />
                                                </svg></span> <span>{{ $user->mobile }}</span></a>
                                    </div>
                                    <div class="user-info-dropdown__author-thumb">
                                        <img src="{{ getImage(getImage('user') . '/' . $user->image, getFileSize('user'), true) }}"
                                            alt="">
                                    </div>
                                </div>
                                <div class="user-info-dropdown__body">
                                    <div class="user-info-dropdown__menu">
                                        <a href="{{ route('user.home') }}" class="user-info-dropdown__menu-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
                                                height="24" color="currentColor" fill="none" stroke="#141B34"
                                                stroke-width="1.5" stroke-linejoin="round">
                                                <path
                                                    d="M9.75 3H5.75C5.05222 3 4.70333 3 4.41943 3.08612C3.78023 3.28002 3.28002 3.78023 3.08612 4.41943C3 4.70333 3 5.05222 3 5.75C3 6.44778 3 6.79667 3.08612 7.08057C3.28002 7.71977 3.78023 8.21998 4.41943 8.41388C4.70333 8.5 5.05222 8.5 5.75 8.5H9.75C10.4478 8.5 10.7967 8.5 11.0806 8.41388C11.7198 8.21998 12.22 7.71977 12.4139 7.08057C12.5 6.79667 12.5 6.44778 12.5 5.75C12.5 5.05222 12.5 4.70333 12.4139 4.41943C12.22 3.78023 11.7198 3.28002 11.0806 3.08612C10.7967 3 10.4478 3 9.75 3Z" />
                                                <path
                                                    d="M21 9.75V5.75C21 5.05222 21 4.70333 20.9139 4.41943C20.72 3.78023 20.2198 3.28002 19.5806 3.08612C19.2967 3 18.9478 3 18.25 3C17.5522 3 17.2033 3 16.9194 3.08612C16.2802 3.28002 15.78 3.78023 15.5861 4.41943C15.5 4.70333 15.5 5.05222 15.5 5.75V9.75C15.5 10.4478 15.5 10.7967 15.5861 11.0806C15.78 11.7198 16.2802 12.22 16.9194 12.4139C17.2033 12.5 17.5522 12.5 18.25 12.5C18.9478 12.5 19.2967 12.5 19.5806 12.4139C20.2198 12.22 20.72 11.7198 20.9139 11.0806C21 10.7967 21 10.4478 21 9.75Z" />
                                                <path
                                                    d="M16.9194 20.9139C17.2033 21 17.5522 21 18.25 21C18.9478 21 19.2967 21 19.5806 20.9139C20.2198 20.72 20.72 20.2198 20.9139 19.5806C21 19.2967 21 18.9478 21 18.25C21 17.5522 21 17.2033 20.9139 16.9194C20.72 16.2802 20.2198 15.78 19.5806 15.5861C19.2967 15.5 18.9478 15.5 18.25 15.5C17.5522 15.5 17.2033 15.5 16.9194 15.5861C16.2802 15.78 15.78 16.2802 15.5861 16.9194C15.5 17.2033 15.5 17.5522 15.5 18.25C15.5 18.9478 15.5 19.2967 15.5861 19.5806C15.78 20.2198 16.2802 20.72 16.9194 20.9139Z" />
                                                <path
                                                    d="M8.5 11.5H7C5.11438 11.5 4.17157 11.5 3.58579 12.0858C3 12.6716 3 13.6144 3 15.5V17C3 18.8856 3 19.8284 3.58579 20.4142C4.17157 21 5.11438 21 7 21H8.5C10.3856 21 11.3284 21 11.9142 20.4142C12.5 19.8284 12.5 18.8856 12.5 17V15.5C12.5 13.6144 12.5 12.6716 11.9142 12.0858C11.3284 11.5 10.3856 11.5 8.5 11.5Z" />
                                            </svg>
                                            <span class="menu-name">@lang('Dashboard')</span>

                                        </a>
                                        <a href="{{ route('user.deposit.history') }}"
                                            class="user-info-dropdown__menu-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                viewBox="0 0 32 32" fill="none">
                                                <g clip-path="url(#clip0_transaction)">
                                                    <mask id="mask0_transaction" style="mask-type:luminance"
                                                        maskUnits="userSpaceOnUse" x="0" y="0" width="32"
                                                        height="32">
                                                        <rect width="32" height="32" fill="white" />
                                                    </mask>

                                                    <g mask="url(#mask0_transaction)">

                                                        <!-- Outer circle -->
                                                        <path opacity="0.4"
                                                            d="M16 29.333C9.556 29.333 4.333 24.111 4.333 17.667C4.333 11.222 9.556 6 16 6C22.444 6 27.667 11.222 27.667 17.667C27.667 24.111 22.444 29.333 16 29.333Z"
                                                            fill="#475569" />

                                                        <!-- Top arrow -->
                                                        <path opacity="0.8"
                                                            d="M10.5 12.333H19.667L17.833 10.5C17.444 10.111 17.444 9.444 17.833 9.056C18.222 8.667 18.889 8.667 19.278 9.056L22.333 12.111C22.722 12.5 22.722 13.167 22.333 13.556L19.278 16.611C18.889 17 18.222 17 17.833 16.611C17.444 16.222 17.444 15.556 17.833 15.167L19.667 13.333H10.5C9.944 13.333 9.5 12.889 9.5 12.333C9.5 11.778 9.944 12.333 10.5 12.333Z"
                                                            fill="#475569" />

                                                        <!-- Bottom arrow -->
                                                        <path opacity="0.6"
                                                            d="M22.333 20.389C22.722 20.833 22.722 21.5 22.333 21.944L19.278 25C18.889 25.389 18.222 25.389 17.833 25C17.444 24.611 17.444 23.944 17.833 23.556L19.667 21.667H10.5C9.944 21.667 9.5 21.222 9.5 20.667C9.5 20.111 9.944 19.667 10.5 19.667H19.667L17.833 17.833C17.444 17.444 17.444 16.778 17.833 16.389C18.222 16 18.889 16 19.278 16.389L22.333 19.444Z"
                                                            fill="#475569" />
                                                    </g>
                                                </g>

                                                <defs>
                                                    <clipPath id="clip0_transaction">
                                                        <rect width="32" height="32" fill="white" />
                                                    </clipPath>
                                                </defs>
                                            </svg>

                                            <span class="menu-name">@lang('Payments')</span>

                                        </a>
                                        <a href="{{ route('user.activity') }}" class="user-info-dropdown__menu-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                viewBox="0 0 32 32" fill="none">
                                                <path opacity="0.4"
                                                    d="M20.652 1.87094C19.1331 1.66671 17.1923 1.66672 14.7419 1.66675C12.2916 1.66672 10.2003 1.66671 8.68131 1.87094C7.11808 2.0811 5.85281 2.52392 4.85499 3.52174C3.85717 4.51956 3.41435 5.78483 3.20419 7.34806C2.99996 8.867 2.99997 10.8078 3 13.2582V18.7419C2.99997 21.1923 2.99996 23.1331 3.20419 24.6521C3.41435 26.2153 3.85717 27.4806 4.85499 28.4785C5.85281 29.4762 7.11808 29.919 8.68131 30.1293C10.2003 30.3334 12.2915 30.3334 14.7419 30.3334C15.5031 30.3334 16.2152 30.3334 16.8808 30.3273C14.7299 28.7502 13.3333 26.2049 13.3333 23.3334C13.3333 18.5469 17.2135 14.6667 22 14.6667C23.5785 14.6667 25.0585 15.0887 26.3333 15.8262V13.2582C26.3333 10.8079 26.3333 8.86699 26.1292 7.34806C25.9189 5.78483 25.4761 4.51956 24.4784 3.52174C23.4805 2.52392 22.2152 2.0811 20.652 1.87094Z"
                                                    fill="#475569" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M8.3335 9.33325C8.3335 8.78097 8.78122 8.33325 9.3335 8.33325H20.0002C20.5524 8.33325 21.0002 8.78097 21.0002 9.33325C21.0002 9.88553 20.5524 10.3333 20.0002 10.3333H9.3335C8.78122 10.3333 8.3335 9.88553 8.3335 9.33325ZM8.3335 14.6666C8.3335 14.1143 8.78122 13.6666 9.3335 13.6666H15.3335C15.8858 13.6666 16.3335 14.1143 16.3335 14.6666C16.3335 15.2189 15.8858 15.6666 15.3335 15.6666H9.3335C8.78122 15.6666 8.3335 15.2189 8.3335 14.6666Z"
                                                    fill="#475569" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M22 16.3333C18.134 16.3333 15 19.4673 15 23.3333C15 27.1993 18.134 30.3333 22 30.3333C25.866 30.3333 29 27.1993 29 23.3333C29 19.4673 25.866 16.3333 22 16.3333ZM23 20.6666C23 20.1143 22.5523 19.6666 22 19.6666C21.4477 19.6666 21 20.1143 21 20.6666V23.9333C21 24.3527 21.2619 24.7277 21.6557 24.8722L23.6557 25.6055C24.1743 25.7957 24.7488 25.5294 24.9389 25.0109C25.1291 24.4923 24.8628 23.9178 24.3443 23.7277L23 23.2349V20.6666Z"
                                                    fill="#475569" />
                                            </svg>
                                            <span class="menu-name">@lang('Activity')</span>
                                        </a>
                                    </div>
                                    <a href="{{ route('user.logout') }}"
                                        class="btn logout-btn w-100 mt-4">@lang('Logout')</a>
                                </div>
                            </div>
                        </div>
                    @else
                        <a class="btn btn--white d-none" href="{{ route('user.login') }}">
                            <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none">
                                    <g clip-path="url(#clip0_2331_378)">
                                        <path
                                            d="M12.0002 0.240234C2.94734 0.240234 -2.71068 10.0402 1.81574 17.8802C6.34216 25.7202 17.6582 25.7202 22.1847 17.8802C23.2168 16.0925 23.7602 14.0645 23.7602 12.0002C23.7533 5.50821 18.4922 0.247097 12.0002 0.240234ZM5.90309 19.8591C8.74921 15.4079 15.2512 15.4079 18.0973 19.8591C14.511 22.6482 9.48937 22.6482 5.90309 19.8591ZM8.38174 11.0956C8.38174 8.31012 11.3971 6.56919 13.8094 7.96194C16.2217 9.35468 16.2217 12.8366 13.8094 14.2293C13.2594 14.5469 12.6353 14.7141 12.0002 14.7141C10.0017 14.7142 8.38174 13.0941 8.38174 11.0956ZM19.4361 18.6051C18.4272 17.1431 17.0086 16.012 15.3586 15.3541C18.641 12.7688 17.8938 7.59973 14.0137 6.04975C10.1336 4.49978 6.0306 7.73138 6.62833 11.8667C6.82747 13.2443 7.54829 14.4928 8.64181 15.3541C6.99181 16.012 5.57317 17.1431 4.56426 18.6051C-0.526009 12.8809 2.48918 3.79287 9.99161 2.24665C17.494 0.700444 23.8569 7.85568 21.4447 15.1261C21.0187 16.4102 20.3352 17.594 19.4361 18.6051Z"
                                            fill="currentColor" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_2331_378">
                                            <rect width="24" height="24" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg></span>

                            @lang('Login')
                        </a>
                    @endauth

                    <a href="{{ request()->routeIs('home') ? '#download-section' : route('home') . '#download-section' }}"
                        class="btn btn--base app-download-btn">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                viewBox="0 0 24 24" fill="none">
                                <path d="M12 17V3" stroke="CurrentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M6 11L12 17L18 11" stroke="CurrentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M19 21H5" stroke="CurrentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </span>
                        @lang('Download App')</a>
                </div>
            </div>
        </nav>
    </div>
</header>

@push('style')
    <style>
        @media screen and (min-width: 992px) {
            .header .navbar {
                min-height: 96px;
            }

            .header .navbar-brand,
            .header .navbar-collapse,
            .header .nav-menu,
            .header .header-right {
                min-height: 96px;
                display: flex;
                align-items: center;
            }

            .header .nav-menu .nav-item .nav-link {
                min-height: 96px;
                display: inline-flex;
                align-items: center;
                padding-top: 0;
                padding-bottom: 0;
            }
        }

        .user-info-dropdown.show {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
        }
    </style>
@endpush
