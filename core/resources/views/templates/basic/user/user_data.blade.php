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
                    <a class="account-logo" href="{{ route('home') }}">
                        <img src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="">
                    </a>
                    <div class="account-heading">
                        <h4 class="account-heading__title">@lang('Complete Your Profile ')</h4>
                        <p class="account-heading__subtitle">@lang('Add your details to proceed with account creation')</p>
                    </div>
                </div>
                <div class="account-body">
                    <form class="account-form" action="{{ route('user.data.submit') }}" method="POST">
                        @csrf
                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="input-group-custom">
                                    <span class="input-group-custom__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24" x="0"
                                            y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512"
                                            xml:space="preserve" class="">
                                            <g>
                                                <path
                                                    d="M256 0c-74.439 0-135 60.561-135 135s60.561 135 135 135 135-60.561 135-135S330.439 0 256 0zM423.966 358.195C387.006 320.667 338.009 300 286 300h-60c-52.008 0-101.006 20.667-137.966 58.195C51.255 395.539 31 444.833 31 497c0 8.284 6.716 15 15 15h420c8.284 0 15-6.716 15-15 0-52.167-20.255-101.461-57.034-138.805z"
                                                    fill="CurrentColor" opacity="1" data-original="CurrentColor"
                                                    class="">
                                                </path>
                                            </g>
                                        </svg>
                                    </span>
                                    <div class="input-group-custom__wrapper">
                                        <label class="custom-form-label" for="first_name">@lang('Username')</label>
                                        <input class="input-group-custom__input form--control checkUser" type="text"
                                            placeholder="@lang('Enter Your Username')" name="username" value="{{ old('username') }}"
                                            required minlength="6">
                                    </div>
                                </div>
                                <span class="username-exists-error d-none fs-14"></span>
                            </div>

                            <div class="col-12">
                                <div class="input-group-custom">
                                    <span class="input-group-custom__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24" x="0"
                                            y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512"
                                            xml:space="preserve" class="">
                                            <g>
                                                <path
                                                    d="M269.858 510.34c278.493-15.983 330.268-408.194 66.017-496.268C262.753-10.4 178.912.731 114.941 43.884h.018A254.731 254.731 0 0 0 4.069 218.322l-.016-.011C-21.01 375.487 110.874 520.734 269.858 510.34zm206.117-161.425a237.421 237.421 0 0 1-36.261 59.542c-1.055-6.293 1.857-13.582-2.482-19.455-4.708-9.179-9.069-18.543-13.975-27.613-1.824-3.373-1.321-5.311 1.367-7.739 28.792-25.263 3.125-11.831 5.953-47.938a4.9 4.9 0 0 0-2.6-4.85c-13.648-8.714-31.465-11.707-45.569-2.023-17.578 8.308-42.651 1.953-48.337-18.223-5.029-13.411-4.412-26.954-4.544-40.65.811-7.386 10.943-8.626 9.3-17.163.39-10.84-3.143-22.283 7.108-29.358a6.737 6.737 0 0 1 5.833-1.545 40.084 40.084 0 0 0 26.732-6.234c8.377-5.311 18.72-1.873 28-3.082 4.423-.315 7.7.984 10.423 4.427 5.354 6.76 13.729 8.745 20.765 12.851 2.367 1.381 3.638-.36 4.748-1.792 5.911-7.626 14.97-10.188 22.913-14.573 4.326-2.479 13 6.985 20.384 7.321 14.715 51.506 11.3 108.843-9.758 158.097zm-3.459-193.579c-8.818 1.16-2.888-7.217-7.439-11.6a170.463 170.463 0 0 1-13.022-12.946c-2.125-2.27-3.5-2.635-5.957-.21-6.6 6.53-6.229 5.837 1.467 13.64 8.986 9.878 15.759 9.713-.5 16.577-3.7 1.666-7.194 5.462-11.232-.023-2.444-3.319-5.771-5.549-5.376-10.662.607-7.884-5.408-13.6-8.191-20.407-.831-2.031-2.3-.6-3.083.4-5.855 7.528-14.7 10.438-22.712 14.62-13.839 12.1-10.68 10.782-29.044 10.256-1.7.073-2.2-1.977-2.938-3.175-9.247-15.138-7.454-18.828 2.126-33.119 3.524-2.114 19.094.326 22.037 3.4 1.323 1.38 2.409 2.988 4.318 5.394 1.467-5.86.724-10.35.876-14.773 2.482-18.149 17.732-9.97 28.558-13.912 5.117-3.911 7.217-5.858 6.132-7.777a237.687 237.687 0 0 1 43.98 64.317zM427.055 89.5c-7.389-4.811-23.805-9.883-24.242-21.771A236.705 236.705 0 0 1 427.055 89.5zM87.209 87.2A238.841 238.841 0 0 1 133.1 51.3c5.681 2.9 9.734 13.569 16.86 9.778 7.6-3.988 15.7-7.151 21.156-14.282 3.091-3.755 8.5-.881 12.537-2.02 15.872-2.149 20.163 15.923 26.1 26.674-.524.441-.8.864-1.028.838-13.772-1.554-26.4 2.868-38.881 7.674-4.1 1.579-7.26 1.713-9.825-2.235-3.17-4.882-7.243-5.023-12.3-2.917-4.684 3.311-21.513 3.883-20.35 10.528 1.142 6.243-3.787 16.884 7.419 14.757 5.251-1.827 9.765 13.69 12.974 4.769 6.886-27.9 35.916-3.553 58.3-21.38 8.235-4.886 15.008 11.208 18.208 16.877 2.432 5.319 4.191 11.581 12.742 9.726-5.362 5.364-10.761 10.692-16.063 16.114-1.538 1.573-2.936 1.069-4.663.469-6.666-2.314-13.423-4.371-20.066-6.75-3.169-1.135-4.689-1.246-4.617 3 5.424 29.2-19.451 1.038-36.726 22.936-21.556 21.664-4.874 39.844-34.5 62.619-1.771 1.626-3.242 1.822-4.993.536-12.87-9.136-41.786-14.1-51.4.834a3.1 3.1 0 0 0 .422 2.683c5.149 8.253 5.553 22.138 15.329 8.294 2.91-2.48 6.328-1.645 9.308-1.15 3.135.521 1.578 3.841 1.5 5.772.443 7.7 11.519 9.072 9.34 17.74-1.243 6.271 6.656 2.805 10.127 3.857 5.611.809 9.868-1.068 13.465-5.45 2.728-3.323 18.387-5.581 21.993-3.443 2.859 2.881 4.06 7.382 6.111 10.9 3.409 6.84 3.556 6.978 8.574 1.572 11.53-9.051 27.737-1.89 38.708 4.395 8.619 4.828 5.772 22.064 18.611 19.587 2.466-.582 3.8 1.519 5.152 3.042 9.326 11.409 24.384 10.044 29.9 25.689 3.554 6.525 3.174 12.244.154 18.7-2.146 12.126-8.109 21.061-20.7 24.457-9.984 3.586 6.86 19.876-15.993 26.372-5.861 1.276-6.62 5.765-5.861 11.029 2.424 13.558-.868 23.79-15.78 27.362-4.41 1.457-3.624 7.321-5.717 10.663-10.288 18.75-.936 32.429 12.166 45.56-11.5.679-23.963 1.849-27.938-10.532-4.966-14.423-9.46-28.971-16.824-42.455-2.473-4.528-.881-9.789-.945-14.7-1.795-20.093-22.313-35.132-18.459-56.541 1.494-10.723-13.8 4.59-29.635-14.925-5.278-6.261-17.559-21.393-15.438-29.236 4.335-10.806 4.792-23.841 14.8-31.341 4.263-3.314 4.651-13.648-3.3-11.644-20.424 3.417-16.06-11.93-28.022-9.016-8.1.862-13.947-1.793-20.242-7.46-18.886-17.37-24.3-4.5-35.121-17.378A238.052 238.052 0 0 1 87.209 87.2z"
                                                    fill="CurrentColor" opacity="1" data-original="CurrentColor"
                                                    class="">
                                                </path>
                                            </g>
                                        </svg>
                                        </svg>
                                    </span>
                                    <div class="input-group-custom__wrapper">
                                        <label class="custom-form-label custom__label"
                                            for="country">@lang('Country')</label>
                                        <select class="input-group-custom__input form-control form--control img-select2"
                                            name="country" required>
                                            @foreach ($countries as $key => $country)
                                                <option data-mobile_code="{{ $country->dial_code }}"
                                                    value="{{ $country->country }}" data-code="{{ $key }}"
                                                    data-src="{{ asset('assets/images/country/' . strtolower($key) . '.svg') }}">
                                                    {{ __($country->country) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-group-custom">
                                    <span class="input-group-custom__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24" x="0"
                                            y="0" viewBox="0 0 513.64 513.64" style="enable-background:new 0 0 512 512"
                                            xml:space="preserve" class="">
                                            <g>
                                                <path
                                                    d="m499.66 376.96-71.68-71.68c-25.6-25.6-69.12-15.359-79.36 17.92-7.68 23.041-33.28 35.841-56.32 30.72-51.2-12.8-120.32-79.36-133.12-133.12-7.68-23.041 7.68-48.641 30.72-56.32 33.28-10.24 43.52-53.76 17.92-79.36l-71.68-71.68c-20.48-17.92-51.2-17.92-69.12 0L18.38 62.08c-48.64 51.2 5.12 186.88 125.44 307.2s256 176.641 307.2 125.44l48.64-48.64c17.921-20.48 17.921-51.2 0-69.12z"
                                                    fill="CurrentColor" opacity="1" data-original="CurrentColor"></path>
                                            </g>
                                        </svg>
                                    </span>
                                    <div class="input-group-custom__wrapper">
                                        <label class="custom-form-label" for="mobile">@lang('Mobile')</label>
                                        <div class="input-group input-number">
                                            <span class="input-group-text mobile-code"></span>
                                            <input type="hidden" name="mobile_code">
                                            <input type="hidden" name="country_code">

                                            <input class="input-group-custom__input form--control checkUser" type="number"
                                                inputmode="numeric" pattern="[0-9]*" placeholder="@lang('Enter your mobile')"
                                                name="mobile" value="{{ old('mobile') }}" required id="mobile">
                                        </div>

                                    </div>

                                </div>
                                <span class="mobile-exists-error d-none fs-14"></span>
                            </div>
                            <div class="col-12">
                                <div class="input-group-custom">
                                    <span class="input-group-custom__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24"
                                            x="0" y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 512 512"
                                            xml:space="preserve" class="">
                                            <g>
                                                <path fill="CurrentColor" fill-rule="evenodd"
                                                    d="M12 .25A8.75 8.75 0 0 0 3.25 9c0 1.052.379 2.275.915 3.5.544 1.243 1.284 2.563 2.076 3.833 1.585 2.54 3.417 4.937 4.42 6.202a1.704 1.704 0 0 0 2.679 0c1.002-1.265 2.834-3.662 4.419-6.203.792-1.27 1.532-2.59 2.076-3.832.536-1.225.915-2.448.915-3.5A8.75 8.75 0 0 0 12 .25zm0 4a4.25 4.25 0 1 0 0 8.5 4.25 4.25 0 0 0 0-8.5z"
                                                    clip-rule="evenodd" opacity="1" data-original="CurrentColor">
                                                </path>
                                            </g>
                                        </svg>
                                    </span>
                                    <div class="input-group-custom__wrapper">
                                        <label class="custom-form-label" for="address">@lang('Address')</label>
                                        <input class="input-group-custom__input form--control" type="text"
                                            placeholder="@lang('Enter your address')" name="address" value="{{ old('address') }}"
                                            required id="address">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="input-group-custom">
                                    <span class="input-group-custom__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24"
                                            x="0" y="0" viewBox="0 0 68 68" style="enable-background:new 0 0 512 512"
                                            xml:space="preserve" class="">
                                            <g>
                                                <path
                                                    d="M9.605 24.975h11.828v2.783c0 .573-.47 1.044-1.043 1.044h-1.392v20.587h1.392c.573 0 1.043.46 1.043 1.043v2.784H9.605v-2.784c0-.583.47-1.043 1.044-1.043h1.391V28.802H10.65c-.573 0-1.044-.471-1.044-1.044zM28.084 24.975h11.829v2.783c0 .573-.471 1.044-1.044 1.044h-1.392v20.587h1.392c.573 0 1.044.46 1.044 1.043v2.784H28.084v-2.784c0-.583.47-1.043 1.044-1.043h1.391V28.802h-1.391c-.573 0-1.044-.471-1.044-1.044zM55.957 49.389h1.391c.573 0 1.044.46 1.044 1.043v2.784H46.563v-2.784c0-.583.471-1.043 1.044-1.043H49V28.802h-1.392c-.573 0-1.044-.471-1.044-1.044v-2.783h11.829v2.783c0 .573-.47 1.044-1.044 1.044h-1.391zM61.922 56.52v2.681H6.085v-2.68c0-.696.563-1.259 1.259-1.259h53.32c.695 0 1.258.563 1.258 1.259zM65.557 64.739v-2.235c0-.696-.565-1.26-1.261-1.26H3.704c-.696 0-1.26.564-1.26 1.26v2.235c0 .696.564 1.261 1.26 1.261h60.592c.696 0 1.26-.565 1.26-1.261zM32.754 2.32 5.695 17.097c-.403.22-.654.643-.654 1.102v3.474c0 .693.562 1.255 1.256 1.255H61.71c.694 0 1.256-.562 1.256-1.255v-3.474c0-.46-.251-.882-.654-1.102L35.262 2.32a2.617 2.617 0 0 0-2.508 0zm1.254 15.044a4.113 4.113 0 0 1-4.11-4.11 4.113 4.113 0 0 1 4.11-4.108c2.257 0 4.09 1.842 4.09 4.109 0 2.266-1.833 4.109-4.09 4.109z"
                                                    fill="CurrentColor" opacity="1" data-original="CurrentColor"
                                                    class=""></path>
                                            </g>
                                        </svg>
                                    </span>
                                    <div class="input-group-custom__wrapper">
                                        <label class="custom-form-label" for="state">@lang('State')</label>
                                        <input class="input-group-custom__input form--control" type="text"
                                            placeholder="@lang('Enter your state')" name="state" value="{{ old('state') }}"
                                            required id="state">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="input-group-custom">
                                    <span class="input-group-custom__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24"
                                            x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512"
                                            xml:space="preserve" class="">
                                            <g>
                                                <g data-name="39 Zip">
                                                    <path
                                                        d="m269.37 243.76-.38-.19-29.47 14.78a10.88 10.88 0 0 1-9.74 0l-29.45-14.77-22.66 11.36a10.85 10.85 0 0 1-15.67-9.7V203H18.69v211.43A22.56 22.56 0 0 0 41.21 437h318.24a97.31 97.31 0 0 1 68.9-178v-56H285.07v31.09a10.84 10.84 0 0 1-15.7 9.69zm-136.57 162a6 6 0 0 1 0 12H80.21a9.74 9.74 0 0 1-8.27-14.89l53.52-85.8H75.64a6 6 0 1 1 0-12h53.9A9.73 9.73 0 0 1 137.8 320l-53.52 85.8zm33.26 6a6 6 0 0 1-12 0V311.07a6 6 0 0 1 12 0zm82.49-72.65a33.71 33.71 0 0 1-33.67 33.66h-22v39a6 6 0 0 1-12 0v-100.7a6 6 0 0 1 6-6h28a33.71 33.71 0 0 1 33.67 33.66z"
                                                        fill="CurrentColor" opacity="1" data-original="CurrentColor"
                                                        class=""></path>
                                                    <path
                                                        d="M214.88 317.07h-22v43.7h22a21.68 21.68 0 0 0 21.67-21.66v-.38a21.68 21.68 0 0 0-21.67-21.66zM200.33 231.49a11.18 11.18 0 0 1 5 1.15l29.36 14.73L264 232.66a11.27 11.27 0 0 1 9.08-.41v-29.39H174v40.51l21.36-10.71a11.25 11.25 0 0 1 4.97-1.17zM61.37 89.32 21.49 191h141.07l11.98-116H82.33a22.4 22.4 0 0 0-20.96 14.32zM385.67 89.32a22.38 22.38 0 0 0-21-14.29H272.5l12 116h141.05zM411.42 269.43a85.27 85.27 0 1 0 85.27 85.27 85.36 85.36 0 0 0-85.27-85.27zm52.64 95.44a44.41 44.41 0 0 1-58.31 3.93c-.09.1-.16.22-.26.32l-41.38 41.37a6 6 0 0 1-8.48-8.49L397 360.63c.1-.1.22-.17.32-.26a44.41 44.41 0 1 1 66.74 4.5zM186.61 75.03l-11.99 115.95h97.8L260.44 75.03z"
                                                        fill="CurrentColor" opacity="1" data-original="CurrentColor"
                                                        class=""></path>
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                                    <div class="input-group-custom__wrapper">
                                        <label class="custom-form-label" for="zip">@lang('Zip Code')</label>
                                        <input class="input-group-custom__input form--control" type="text"
                                            placeholder="@lang('Enter your zip')" name="zip" value="{{ old('zip') }}"
                                            required id="zip">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="input-group-custom">
                                    <span class="input-group-custom__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24"
                                            x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512"
                                            xml:space="preserve" class="">
                                            <g>
                                                <g data-name="16-location">
                                                    <path
                                                        d="M320 100H192a12 12 0 0 0-12 12v204h48v-60a12 12 0 0 1 12-12h32a12 12 0 0 1 12 12v60h48V112a12 12 0 0 0-12-12ZM220 208a12 12 0 0 1-24 0v-16a12 12 0 0 1 24 0Zm0-64a12 12 0 0 1-24 0v-16a12 12 0 0 1 24 0Zm48 64a12 12 0 0 1-24 0v-16a12 12 0 0 1 24 0Zm0-64a12 12 0 0 1-24 0v-16a12 12 0 0 1 24 0Zm48 64a12 12 0 0 1-24 0v-16a12 12 0 0 1 24 0Zm0-64a12 12 0 0 1-24 0v-16a12 12 0 0 1 24 0Z"
                                                        fill="CurrentColor" opacity="1" data-original="CurrentColor"
                                                        class=""></path>
                                                    <path
                                                        d="M256 4C143.514 4 52 95.514 52 208c0 34.837 10.568 72.315 31.412 111.4 16.371 30.695 39.121 62.515 67.619 94.576C199.279 468.251 246.8 504.1 248.8 505.6a12 12 0 0 0 14.4 0c2-1.5 49.521-37.349 97.769-91.627 28.5-32.061 51.248-63.881 67.619-94.576C449.432 280.315 460 242.837 460 208 460 95.514 368.486 4 256 4Zm0 364a159.606 159.606 0 0 1-117.942-52H168V212H96.051q-.05-2-.051-4c0-88.224 71.776-160 160-160s160 71.776 160 160q0 2.005-.051 4H344v104h29.942A159.606 159.606 0 0 1 256 368Z"
                                                        fill="CurrentColor" opacity="1" data-original="CurrentColor"
                                                        class=""></path>
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                                    <div class="input-group-custom__wrapper">
                                        <label class="custom-form-label" for="city">@lang('City')</label>
                                        <input class="input-group-custom__input form--control" type="text"
                                            placeholder="@lang('Enter your city')" name="city" value="{{ old('city') }}"
                                            required id="city">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button class="w-100 btn btn--base mt-3" type="submit">
                            {{ __($userDataContent?->submit_button_text ?? trans('Submit')) }}
                        </button>
                    </form>

                </div>
            </div>
            <div class="account-thumb bg-img"
                data-background-image="{{ frontendImage('auth', $authContent?->account_image, '950x990') }}">
            </div>
        </section>
    </main>
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('style')
    <style>
        .account-form select[name="country"]+.select2-container {
            top: 0;
            width: 100% !important;
            display: block;
            position: relative;
        }

        .account-form select[name="country"]+.select2-container .selection {
            width: 100%;
        }

        .account-form select[name="country"]+.select2-container .select2-selection--single {
            background: transparent !important;
            border: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            height: auto !important;
            padding: 0 !important;
            min-height: 0 !important;
            display: flex !important;
            align-items: flex-end;
            position: relative;
        }

        .account-form select[name="country"]+.select2-container .select2-selection--single .select2-selection__rendered {
            color: hsl(var(--heading-color)) !important;
            line-height: 1.5 !important;
            min-height: 24px;
            padding: 0 28px 0 0 !important;
        }

        .account-form select[name="country"]+.select2-container .select2-selection--single:focus,
        .account-form select[name="country"]+.select2-container.select2-container--focus .select2-selection--single,
        .account-form select[name="country"]+.select2-container.select2-container--open .select2-selection--single {
            border: none !important;
            box-shadow: none !important;
            outline: none !important;
        }

        .account-form select[name="country"]+.select2-container .select2-selection--single .select2-selection__arrow {
            width: 24px !important;
            height: 100% !important;
            top: -10% !important;
            right: 0 !important;
            transform: translateY(-50%);
        }

        .account-form select[name="country"]+.select2-container .select2-selection--single .select2-selection__arrow b {
            display: none;
        }

        .account-form select[name="country"]+.select2-container .select2-selection--single .select2-selection__arrow::after {
            top: 50% !important;
            right: 0 !important;
            transform: translateY(-50%) !important;
            margin: 0 !important;
            line-height: 1 !important;
        }

        .account-form select[name="country"]+.select2-container.select2-container--open .select2-selection--single .select2-selection__arrow::after {
            transform: translateY(-50%) !important;
        }

        .account-form .input-number {
            flex-wrap: unset !important;
            align-items: center;
            gap: 8px;
        }

        .account-form .input-group .input-group-text {
            color: hsl(var(--black));
            padding: 0px !important;
            background: transparent !important;
        }

        .custom-form-label {
            padding-top: 4px;
        }

        .img-flag-inner img {
            max-width: 30px;
            border-radius: 5px;
        }
    </style>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush


@push('script')
    <script>
        "use strict";
        (function($) {


            @if ($mobileCode)
                $(`option[data-code={{ $mobileCode }}]`).attr('selected', '');
            @endif

            function formatState(state) {
                if (!state.id) {
                    return state.text;
                }
                var $state = $(
                    '<span class="img-flag-inner d-flex gap-2 flex-wrap"><img src="' + $(state.element).attr(
                        'data-src') +
                    '" class="img-flag" /> ' + state.text + '<span>'
                );
                return $state;
            };

            $('.img-select2').select2({
                templateResult: formatState,
            });

            $('select[name=country]').on('change', function() {
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
                var value = $('[name=mobile]').val();
                var name = 'mobile';
                checkUser(value, name);
            });

            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));


            $('.checkUser').on('focusout', function(e) {
                var value = $(this).val();
                var name = $(this).attr('name')
                checkUser(value, name);
            });



            function checkUser(value, name) {
                var url = '{{ route('user.checkUser') }}';
                var token = '{{ csrf_token() }}';

                if (name == 'mobile') {
                    var mobile = `${value}`;
                    var data = {
                        mobile: mobile,
                        mobile_code: $('.mobile-code').text().substr(1),
                        _token: token
                    }
                }
                if (name == 'username') {
                    var data = {
                        username: value,
                        _token: token
                    }
                }
                $.post(url, data, function(response) {
                    domModifyForExists(response, name);
                });
            }

            let usernameError = false;
            let mobileError = false;

            function domModifyForExists(response, name) {
                if (response.data == true) {
                    if (name == 'username') {
                        var message = `@lang('The username is not available.')`;
                        usernameError = true
                    } else {
                        var message = `@lang('The mobile number is already registered.')`;
                        mobileError = true;
                    }

                    $(`.${name}-exists-error`)
                        .html(`${message}`)
                        .removeClass('d-none')
                        .addClass("text--danger mt-1 d-block");
                } else {
                    $(`.${name}-exists-error`)
                        .empty()
                        .addClass('d-none')
                        .removeClass("text--danger mt-1 d-block");

                    if (name == 'username') {
                        usernameError = false;
                    } else {
                        mobileError = false;
                    }
                }

                if (!usernameError && !mobileError) {
                    $(`button[type=submit]`)
                        .attr('disabled', false)
                        .removeClass('disabled');
                } else {
                    $(`button[type=submit]`)
                        .attr('disabled', true)
                        .addClass('disabled');
                }
            }
        })(jQuery);
    </script>
@endpush
