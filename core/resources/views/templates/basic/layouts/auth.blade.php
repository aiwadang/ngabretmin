@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
        $user = auth()->user();
        $runningRide = App\Models\Ride::whereNotIn('status', [Status::RIDE_CANCELED, Status::RIDE_COMPLETED])
            ->where('user_id', $user->id)
            ->exists();
    @endphp
    <div class="driver-dashboard">
        <div class="driver-dashboard__header bg-img"
            data-background-image="{{ asset('assets/images/rider-dashboard-header-bg.png') }}">
            <div class="container">

                <div class="driver-dashboard__header-inner">
                    <div class="driver-dashboard__header-left">
                        <div class="driver-profile">
                            <div class="driver-profile__thumb">
                                <img src="{{ $user->image_src }}" alt="img">
                            </div>
                            <div class="driver-profile__info">
                                <h3 class="name">{{ __($user->fullname) }}</h3>
                                <a href="mailto:{{ $user->email }}" class="number">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <path d="M3 8L10.2 13.4C11.2667 14.2 12.7333 14.2 13.8 13.4L21 8"
                                            stroke="hsl(var(--base))" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M21 8V17C21 18.1046 20.1046 19 19 19H5C3.89543 19 3 18.1046 3 17V8C3 6.89543 3.89543 6 5 6H19C20.1046 6 21 6.89543 21 8Z"
                                            stroke="hsl(var(--base))" stroke-width="2" />
                                    </svg>
                                    <span>{{ $user->email }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="driver-dashboard__header-right">
                        <span class="rating-rate"><i class="fa-solid fa-star"></i>
                            {{ formatRating($user->avg_rating) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="driver-sidebar__inner">
            <div class="container">
                <div class="row">
                    <div class="driver__bar d-lg-none d-block">
                        <span class="driver__bar-icon"><i class="las la-bars"></i></span>
                    </div>
                    @if (!request()->routeIs('user.activity'))
                        @if (!$runningRide)
                            <div class="col-12 mb-4">
                                <div class="alert alert--success custom--alert">
                                    <div class="alert__desc">
                                        <h6 class="mb-1">
                                            <i class="las la-info-circle"></i>
                                            @lang('Start a New Ride')
                                        </h6>
                                        <p class="fs-18">
                                            @lang('Ready for your next journey? Start a new ride now and get connected with nearby drivers in just a few moments. Safe, fast, and convenient travel is only one tap away.')
                                            <a href="{{ route('user.ride.process.step') }}" class="alert__link">
                                                <i>@lang('Start New Ride')</i>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-12 mb-4">
                                <div class="alert alert--warning custom--alert">
                                    <div class="alert__desc">
                                        <h6 class="mb-1">
                                            <i class="las la-car-side"></i>
                                            @lang('Ongoing Ride')
                                        </h6>
                                        <p class="fs-18">
                                            @lang('Your ride is currently in progress. You can monitor the ride status, track your driver location, and stay updated in real time until you reach your destination safely.')
                                            <a href="{{ route('user.ride.process.step') }}" class="alert__link">
                                                <i>@lang('Monitor Your Ride')</i>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                    <div class="col-xl-3 col-lg-4">
                        <div class="driver-sidebar-menu">
                            <span class="driver-sidebar-menu__close d-lg-none d-block"><i class="las la-times"></i></span>
                            <ul class="driver-sidebar-menu-list">
                                <li class="driver-sidebar-menu-list__item @if (request()->routeIs('user.home')) active @endif">
                                    <a href="{{ route('user.home') }}" class="driver-sidebar-menu-list__link">
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 20 20" fill="none">
                                                <path opacity="0.4"
                                                    d="M1.04199 10C1.04199 5.052 5.052 1.042 10 1.042C14.948 1.042 18.958 5.052 18.958 10C18.958 14.948 14.948 18.958 10 18.958C5.052 18.958 1.04199 14.948 1.04199 10Z"
                                                    fill="hsl(var(--base))" />

                                                <path
                                                    d="M5.5 5.5H9V9H5.5V5.5ZM11 5.5H14.5V9H11V5.5ZM5.5 11H9V14.5H5.5V11ZM11 11H14.5V14.5H11V11Z"
                                                    fill="hsl(var(--base))" />
                                            </svg>
                                        </span>
                                        <span class="text">@lang('Dashboard')</span>
                                    </a>
                                </li>

                                <li class="driver-sidebar-menu-list__item @if (request()->routeIs('user.activity')) active @endif">
                                    <a href="{{ route('user.activity') }}" class="driver-sidebar-menu-list__link">
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 20 20" fill="none">
                                                <path opacity="0.4"
                                                    d="M12.9075 1.16937C11.9582 1.04172 10.7452 1.04173 9.21367 1.04175C7.68223 1.04173 6.37516 1.04172 5.42582 1.16937C4.4488 1.30072 3.65801 1.57748 3.03437 2.20112C2.41073 2.82476 2.13397 3.61555 2.00262 4.59257C1.87498 5.54191 1.87498 6.75493 1.875 8.28639V11.7137C1.87498 13.2452 1.87498 14.4582 2.00262 15.4076C2.13397 16.3846 2.41073 17.1754 3.03437 17.7991C3.65801 18.4227 4.4488 18.6994 5.42582 18.8308C6.37516 18.9584 7.68221 18.9584 9.21367 18.9584C9.68942 18.9584 10.1345 18.9584 10.5505 18.9546C9.20617 17.9689 8.33333 16.3781 8.33333 14.5834C8.33333 11.5918 10.7584 9.16675 13.75 9.16675C14.7366 9.16675 15.6616 9.4305 16.4583 9.89141V8.28641C16.4583 6.75496 16.4583 5.5419 16.3308 4.59257C16.1993 3.61555 15.9226 2.82476 15.299 2.20112C14.6753 1.57748 13.8845 1.30072 12.9075 1.16937Z"
                                                    fill="hsl(var(--base))" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M5.20801 5.83325C5.20801 5.48808 5.48783 5.20825 5.83301 5.20825H12.4997C12.8448 5.20825 13.1247 5.48808 13.1247 5.83325C13.1247 6.17843 12.8448 6.45825 12.4997 6.45825H5.83301C5.48783 6.45825 5.20801 6.17843 5.20801 5.83325ZM5.20801 9.16659C5.20801 8.82142 5.48783 8.54159 5.83301 8.54159H9.58301C9.92817 8.54159 10.208 8.82142 10.208 9.16659C10.208 9.51175 9.92817 9.79159 9.58301 9.79159H5.83301C5.48783 9.79159 5.20801 9.51175 5.20801 9.16659Z"
                                                    fill="hsl(var(--base))" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M13.75 10.2083C11.3338 10.2083 9.375 12.167 9.375 14.5833C9.375 16.9995 11.3338 18.9583 13.75 18.9583C16.1662 18.9583 18.125 16.9995 18.125 14.5833C18.125 12.167 16.1662 10.2083 13.75 10.2083ZM14.375 12.9166C14.375 12.5714 14.0952 12.2916 13.75 12.2916C13.4048 12.2916 13.125 12.5714 13.125 12.9166V14.9583C13.125 15.2204 13.2887 15.4548 13.5348 15.5451L14.7848 16.0034C15.1089 16.1223 15.468 15.9558 15.5868 15.6318C15.7057 15.3077 15.5392 14.9486 15.2152 14.8298L14.375 14.5218V12.9166Z"
                                                    fill="hsl(var(--base))" />
                                            </svg>
                                        </span>
                                        <span class="text"> @lang('Ride History')</span>
                                    </a>
                                </li>

                                <li class="driver-sidebar-menu-list__item @if (request()->routeIs('user.review.index')) active @endif">
                                    <a href="{{ route('user.review.index') }}" class="driver-sidebar-menu-list__link">
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 20 20" fill="none">
                                                <path opacity="0.4"
                                                    d="M1.04199 10.0001C1.04199 14.9477 5.05278 18.9584 10.0003 18.9584C14.9479 18.9584 18.9587 14.9477 18.9587 10.0001C18.9587 5.05253 14.9479 1.04175 10.0003 1.04175C5.05278 1.04175 1.04199 5.05253 1.04199 10.0001Z"
                                                    fill="hsl(var(--base))" />
                                                <path
                                                    d="M9.99851 5.20825C10.4662 5.20825 10.8345 5.56149 11.0698 6.03812L11.8551 7.62183C11.8789 7.67084 11.9353 7.73986 12.0202 7.803C12.105 7.86607 12.188 7.90087 12.2426 7.91004L13.6643 8.14819C14.1778 8.23449 14.6082 8.48617 14.7479 8.9245C14.8876 9.3625 14.683 9.81758 14.3138 10.1875L13.2089 11.3014C13.1652 11.3456 13.1161 11.4288 13.0853 11.5371C13.0548 11.6447 13.0521 11.7426 13.066 11.8059L13.0662 11.8068L13.3822 13.1843C13.5132 13.7577 13.4698 14.3263 13.0654 14.6234C12.6597 14.9217 12.1052 14.7895 11.6014 14.4895L10.2688 13.6941C10.2128 13.6607 10.1168 13.6336 10.0008 13.6336C9.88559 13.6336 9.78743 13.6603 9.72784 13.695L9.72701 13.6954L8.39701 14.4893C7.89384 14.7903 7.34005 14.9203 6.93426 14.6217C6.53018 14.3244 6.48456 13.7569 6.61603 13.1841L6.93194 11.8068L6.93213 11.8059C6.94598 11.7426 6.94328 11.6447 6.91273 11.5371C6.88198 11.4288 6.83294 11.3456 6.78917 11.3014L5.68393 10.1871C5.31703 9.81717 5.11314 9.36242 5.25164 8.92509C5.39055 8.4865 5.82013 8.23452 6.33399 8.14817L7.75446 7.91022C7.80654 7.90119 7.88878 7.8667 7.97337 7.80347C8.05811 7.74011 8.11471 7.67094 8.13857 7.62183L8.13977 7.61939L8.92409 6.0377C9.16151 5.56147 9.53134 5.20825 9.99851 5.20825Z"
                                                    fill="hsl(var(--base))" />
                                            </svg>
                                        </span>
                                        <span class="text">@lang('All Review')</span>
                                    </a>
                                </li>

                                <li class="driver-sidebar-menu-list__item @if (request()->routeIs('user.deposit.history')) active @endif">
                                    <a href="{{ route('user.deposit.history') }}" class="driver-sidebar-menu-list__link">
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 20 20" fill="none">

                                                <path opacity="0.4"
                                                    d="M1.042 10C1.042 5.052 5.052 1.042 10 1.042C14.948 1.042 18.958 5.052 18.958 10C18.958 14.948 14.948 18.958 10 18.958C5.052 18.958 1.042 14.948 1.042 10Z"
                                                    fill="hsl(var(--base))" />

                                                <path
                                                    d="M6.3 7H12.2L10.9 5.7C10.72 5.52 10.72 5.22 10.9 5.04C11.08 4.86 11.38 4.86 11.56 5.04L13.7 7.18C13.88 7.36 13.88 7.66 13.7 7.84L11.56 9.98C11.38 10.16 11.08 10.16 10.9 9.98C10.72 9.8 10.72 9.5 10.9 9.32L12.2 8H6.3C6.04 8 5.833 7.793 5.833 7.533C5.833 7.273 6.04 7 6.3 7ZM13.7 12.82C13.88 13.04 13.88 13.34 13.7 13.52L11.56 15.66C11.38 15.84 11.08 15.84 10.9 15.66C10.72 15.48 10.72 15.18 10.9 15L12.2 13.7H6.3C6.04 13.7 5.833 13.46 5.833 13.2C5.833 12.94 6.04 12.7 6.3 12.7H12.2L10.9 11.4C10.72 11.22 10.72 10.92 10.9 10.74C11.08 10.56 11.38 10.56 11.56 10.74L13.7 12.82Z"
                                                    fill="hsl(var(--base))" />
                                            </svg>
                                        </span>
                                        <span class="text">@lang('Payment History')</span>
                                    </a>
                                </li>

                                <li class="driver-sidebar-menu-list__item @if (request()->routeIs('user.transactions')) active @endif">
                                    <a href="{{ route('user.transactions') }}" class="driver-sidebar-menu-list__link">
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 20 20" fill="none">
                                                <path opacity="0.4"
                                                    d="M13.3874 2.28882C14.5232 2.28879 15.4392 2.28878 16.16 2.38566C16.9101 2.48651 17.5415 2.70275 18.0432 3.20435C18.5447 3.70594 18.761 4.33739 18.8618 5.08749C18.9587 5.80824 18.9587 6.72423 18.9587 7.86011V9.63417C18.9587 9.71009 18.9579 9.78492 18.9578 9.85884L18.3752 9.08167C17.854 8.28005 16.6099 8.17065 15.8996 8.60475C15.3485 8.97667 15.0688 9.50209 15.0133 9.998H13.9587C12.4629 9.998 11.2503 11.2105 11.2503 12.7063L11.252 15.2055H6.61328C5.4774 15.2055 4.56142 15.2055 3.84066 15.1087C3.13746 15.0141 2.53817 14.8185 2.05273 14.3811L1.95752 14.29C1.45593 13.7883 1.23968 13.1569 1.13883 12.4068C1.04195 11.6861 1.04197 10.7701 1.04199 9.63417V7.86011C1.04197 6.72423 1.04195 5.80824 1.13883 5.08749C1.23968 4.33739 1.45593 3.70594 1.95752 3.20435C2.45912 2.70275 3.09057 2.48651 3.84066 2.38566C4.56142 2.28878 5.4774 2.28879 6.61328 2.28882H13.3874Z"
                                                    fill="hsl(var(--base))" />
                                                <path
                                                    d="M12.5 8.74709C12.5 10.1278 11.3807 11.2471 10 11.2471C8.61925 11.2471 7.5 10.1278 7.5 8.74709C7.5 7.36636 8.61925 6.24707 10 6.24707C11.3807 6.24707 12.5 7.36636 12.5 8.74709Z"
                                                    fill="hsl(var(--base))" />
                                                <path
                                                    d="M17.292 14.3732C17.292 13.9129 17.6651 13.5398 18.1253 13.5398C18.5856 13.5398 18.9587 13.9129 18.9587 14.3732C18.9587 15.2937 18.2125 16.0398 17.292 16.0398H14.7912L15.0418 16.3735L15.0898 16.4442C15.3076 16.806 15.2201 17.2808 14.875 17.5397C14.5298 17.7984 14.0496 17.7492 13.7633 17.4387L13.7088 17.3728L12.4588 15.7062C12.2695 15.4537 12.2387 15.1161 12.3799 14.8337C12.5211 14.5514 12.8097 14.3732 13.1253 14.3732H17.292ZM12.292 12.7065C12.292 11.786 13.0382 11.0398 13.9587 11.0398H16.4595L16.2088 10.7062L16.1608 10.6353C15.9431 10.2737 16.0306 9.79875 16.3757 9.54C16.7208 9.28125 17.2011 9.33041 17.4873 9.64091L17.5418 9.70683L18.7918 11.3735L18.8553 11.4719C18.986 11.7096 18.9942 11.9989 18.8707 12.2459C18.7296 12.5282 18.441 12.7065 18.1253 12.7065H13.9587C13.9587 13.1667 13.5856 13.5398 13.1253 13.5398C12.6651 13.5398 12.292 13.1667 12.292 12.7065Z"
                                                    fill="hsl(var(--base))" />
                                                <path
                                                    d="M18.0433 3.18889C17.5417 2.68729 16.9102 2.47104 16.1601 2.3702C15.8306 2.3259 15.4603 2.30187 15.0469 2.28882C15.0469 4.4318 16.7998 6.16903 18.9428 6.16903C18.9296 5.76211 18.9056 5.3972 18.862 5.07202C18.7611 4.32194 18.5449 3.69048 18.0433 3.18889Z"
                                                    fill="hsl(var(--base))" />
                                                <path
                                                    d="M1.94154 3.18889C2.44313 2.68729 3.07458 2.47104 3.82468 2.3702C4.15418 2.3259 4.52449 2.30187 4.93796 2.28882C4.93796 4.4318 3.18498 6.16903 1.04199 6.16903C1.05516 5.76211 1.07914 5.3972 1.12285 5.07202C1.2237 4.32194 1.43994 3.69048 1.94154 3.18889Z"
                                                    fill="hsl(var(--base))" />
                                                <path
                                                    d="M1.94153 14.2728C2.44313 14.7745 3.07458 14.9906 3.82468 15.0916C4.15418 15.1358 4.52448 15.1599 4.93795 15.1729C4.93795 13.0299 3.18497 11.2927 1.04199 11.2927C1.05515 11.6996 1.07914 12.0646 1.12285 12.3897C1.22369 13.1398 1.43994 13.7712 1.94153 14.2728Z"
                                                    fill="hsl(var(--base))" />
                                            </svg>
                                        </span>
                                        <span class="text">@lang('Transaction History')</span>
                                    </a>
                                </li>

                                <li class="driver-sidebar-menu-list__item @if (request()->routeIs('user.profile.setting')) active @endif">
                                    <a href="{{ route('user.profile.setting') }}" class="driver-sidebar-menu-list__link">
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 20 20" fill="none">
                                                <path opacity="0.4"
                                                    d="M1.04199 10.0001C1.04199 5.05253 5.05278 1.04175 10.0003 1.04175C14.9479 1.04175 18.9587 5.05253 18.9587 10.0001C18.9587 14.9477 14.9479 18.9584 10.0003 18.9584C5.05278 18.9584 1.04199 14.9477 1.04199 10.0001Z"
                                                    fill="hsl(var(--base))" />
                                                <path
                                                    d="M9.99984 4.375C8.50409 4.375 7.2915 5.58756 7.2915 7.08333C7.2915 8.1559 7.91499 9.08283 8.81925 9.5215C6.77345 10.0398 5.25221 11.8742 5.2091 14.0711C5.20581 14.2389 5.27016 14.401 5.38767 14.5208C6.55908 15.7157 8.1933 16.4583 9.99984 16.4583C11.8068 16.4583 13.4414 15.7154 14.6129 14.5199C14.7304 14.4 14.7947 14.2379 14.7913 14.0701C14.7478 11.8735 13.2261 10.0398 11.1804 9.5215C12.0847 9.08283 12.7082 8.1559 12.7082 7.08333C12.7082 5.58756 11.4956 4.375 9.99984 4.375Z"
                                                    fill="hsl(var(--base))" />
                                            </svg>
                                        </span>
                                        <span class="text">@lang('My Profile')</span>
                                    </a>
                                </li>

                                <li class="driver-sidebar-menu-list__item @if (request()->routeIs('user.change.password')) active @endif">
                                    <a href="{{ route('user.change.password') }}" class="driver-sidebar-menu-list__link">
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 20 20" fill="none">
                                                <path opacity="0.4"
                                                    d="M6.27069 6.95823C7.46104 6.90352 8.66984 6.875 9.99967 6.875C11.3295 6.875 12.5383 6.90352 13.7287 6.95824C15.4437 7.03708 16.8363 8.36517 17.0626 10.046C17.186 10.963 17.2913 11.9265 17.2913 12.9167C17.2913 13.9068 17.186 14.8703 17.0626 15.7873C16.8363 17.4682 15.4437 18.7963 13.7287 18.8751C12.5383 18.9298 11.3295 18.9583 9.99967 18.9583C8.66984 18.9583 7.46104 18.9298 6.27069 18.8751C4.55564 18.7963 3.16308 17.4682 2.93677 15.7873C2.81331 14.8703 2.70801 13.9068 2.70801 12.9167C2.70801 11.9265 2.81331 10.963 2.93677 10.046C3.16308 8.36517 4.55564 7.03708 6.27069 6.95823Z"
                                                    fill="hsl(var(--base))" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M13.3333 12.0752C13.7936 12.0752 14.1667 12.4483 14.1667 12.9085V12.9169C14.1667 13.3771 13.7936 13.7502 13.3333 13.7502C12.8731 13.7502 12.5 13.3771 12.5 12.9169V12.9085C12.5 12.4483 12.8731 12.0752 13.3333 12.0752Z"
                                                    fill="hsl(var(--base))" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M10.0003 12.0752C10.4606 12.0752 10.8337 12.4483 10.8337 12.9085V12.9169C10.8337 13.3771 10.4606 13.7502 10.0003 13.7502C9.54008 13.7502 9.16699 13.3771 9.16699 12.9169V12.9085C9.16699 12.4483 9.54008 12.0752 10.0003 12.0752Z"
                                                    fill="hsl(var(--base))" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M6.66634 12.0752C7.12657 12.0752 7.49967 12.4483 7.49967 12.9085V12.9169C7.49967 13.3771 7.12657 13.7502 6.66634 13.7502C6.20611 13.7502 5.83301 13.3771 5.83301 12.9169V12.9085C5.83301 12.4483 6.20611 12.0752 6.66634 12.0752Z"
                                                    fill="hsl(var(--base))" />
                                                <path
                                                    d="M7.08366 5.62508C7.08366 4.01425 8.38949 2.70841 10.0003 2.70841C11.6112 2.70841 12.917 4.01425 12.917 5.62508V6.92521C13.1895 6.93489 13.4599 6.94593 13.7293 6.95831C14.0252 6.97191 14.3113 7.02268 14.5837 7.10601V5.62508C14.5837 3.09377 12.5317 1.04175 10.0003 1.04175C7.46902 1.04175 5.41699 3.09377 5.41699 5.62508V7.10601C5.68929 7.02268 5.97552 6.97191 6.27134 6.95831C6.54078 6.94593 6.81115 6.93489 7.08366 6.92521V5.62508Z"
                                                    fill="hsl(var(--base))" />
                                            </svg>
                                        </span>
                                        <span class="text">@lang('Change Password')</span>
                                    </a>
                                </li>

                                <li class="driver-sidebar-menu-list__item @if (request()->routeIs('ticket.*')) active @endif">
                                    <a href="{{ route('ticket.index') }}" class="driver-sidebar-menu-list__link">
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 20 20" fill="none">
                                                <path opacity="0.4"
                                                    d="M8.28933 2.29175C6.95039 2.29174 5.89308 2.29173 5.0512 2.38567C4.18811 2.48198 3.47915 2.68311 2.86922 3.13521C2.44994 3.44602 2.08701 3.83075 1.79583 4.27098C1.25816 5.08386 1.09953 6.06931 1.04299 7.37166C1.01598 7.99382 1.54013 8.41158 2.05447 8.41158C2.83105 8.41158 3.52045 9.08875 3.52045 10.0001C3.52045 10.9114 2.83105 11.5886 2.05447 11.5886C1.54013 11.5886 1.01598 12.0063 1.04299 12.6285C1.09953 13.9308 1.25816 14.9163 1.79583 15.7292C2.08701 16.1694 2.44993 16.5542 2.86922 16.8649C3.47915 17.3171 4.18811 17.5182 5.0512 17.6145C5.89307 17.7084 6.95038 17.7084 8.28929 17.7084H11.7114C13.0503 17.7084 14.1077 17.7084 14.9495 17.6145C15.8126 17.5182 16.5216 17.3171 17.1315 16.8649C17.5509 16.5542 17.9138 16.1694 18.205 15.7292C18.7427 14.9162 18.9013 13.9307 18.9578 12.6281C18.9582 12.619 18.9584 12.61 18.9584 12.601V7.39921C18.9584 7.39017 18.9582 7.38114 18.9578 7.37211C18.9013 6.06952 18.7427 5.08395 18.205 4.27098C17.9138 3.83075 17.5509 3.44602 17.1315 3.13521C16.5216 2.68311 15.8126 2.48198 14.9495 2.38567C14.1077 2.29173 13.0504 2.29174 11.7115 2.29175H8.28933Z"
                                                    fill="hsl(var(--base))" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M10 10.0001C10 10.4603 10.3731 10.8334 10.8333 10.8334H14.1667C14.6269 10.8334 15 10.4603 15 10.0001C15 9.53983 14.6269 9.16675 14.1667 9.16675H10.8333C10.3731 9.16675 10 9.53983 10 10.0001Z"
                                                    fill="hsl(var(--base))" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M6.66699 13.3333C6.66699 13.7936 7.04008 14.1667 7.50033 14.1667H14.167C14.6272 14.1667 15.0003 13.7936 15.0003 13.3333C15.0003 12.8731 14.6272 12.5 14.167 12.5H7.50033C7.04008 12.5 6.66699 12.8731 6.66699 13.3333Z"
                                                    fill="hsl(var(--base))" />
                                            </svg>
                                        </span>
                                        <span class="text"> @lang('Support Ticket')</span>
                                    </a>
                                </li>

                                <li class="driver-sidebar-menu-list__item deleteAccountBtn">
                                    <span role="button" class="driver-sidebar-menu-list__link">
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none">
                                                <circle cx="10" cy="7" r="4" stroke="hsl(var(--base))"
                                                    stroke-width="2" />
                                                <path d="M3 21C3 17.6863 6.13401 15 10 15" stroke="hsl(var(--base))"
                                                    stroke-width="2" stroke-linecap="round" />
                                                <rect x="14" y="14" width="8" height="8" rx="2"
                                                    stroke="hsl(var(--base))" stroke-width="2" />
                                                <path d="M16.5 16.5L19.5 19.5" stroke="hsl(var(--base))" stroke-width="2"
                                                    stroke-linecap="round" />
                                                <path d="M19.5 16.5L16.5 19.5" stroke="hsl(var(--base))" stroke-width="2"
                                                    stroke-linecap="round" />
                                            </svg>
                                        </span>
                                        <span class="text"> @lang('Delete Account')</span>
                                    </span>
                                </li>
                            </ul>

                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-8">
                        @yield('auth')
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div id="deleteAccountModalModal" class="modal fade" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('user.delete.account') }}">
                    @csrf
                    <div class="modal-body py-4 px-5">
                        <div class="text-center mb-4">
                            <h1 class="text--warning mb-0"><i class="la la-warning"></i></h1>
                            <h4 class="mb-2">@lang('Please Confirm!')</h4>
                            <p class="question">@lang('Are you sure to delete this account')?</p>
                        </div>
                        <div class="d-flex gap-3 flex-wrap pt-2 pb-3">
                            <div class="flex-fill">
                                <button type="button" class="btn w-100 btn--secondary btn-large"
                                    data-bs-dismiss="modal">
                                    <i class="fa-regular fa-circle-xmark"></i> @lang('No')
                                </button>
                            </div>
                            <div class="flex-fill">
                                <button type="submit" class="btn w-100 btn--primary btn-large">
                                    <i class="fa-regular fa-check-circle"></i> @lang('Yes')
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            'use strict';

            $('.deleteAccountBtn').on('click', () => $('#deleteAccountModalModal').modal('show'));

        })(jQuery);
    </script>
@endpush
