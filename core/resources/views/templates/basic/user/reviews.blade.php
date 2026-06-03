@extends($activeTemplate . 'layouts.auth')
@section('auth')
    <div class="col-xl-12 col-lg-12">
        <div class="driver-dashboard-content">
            <div class="driver-review-wrapper">
                <div class="driver-review__profile">
                    <div class="driver-review__profile-left">
                        <div class="driver-review__profile-thumb">
                            <img class="fit-image" src="{{ $user->image_src }}" alt="image">
                        </div>
                        <div class="driver-review__profile-content">
                            <h6 class="name mb-0">{{ __($user->fullname) }}</h6>
                            <a href="mailto:topalslfsd@gmail.com" class="social-link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                                    fill="none">
                                    <path opacity="0.4"
                                        d="M4.66699 5.66675L6.62834 6.82635C7.77179 7.50241 8.22886 7.50241 9.37232 6.82635L11.3337 5.66675"
                                        stroke="#7C4DFF" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M1.34352 8.98367C1.3871 11.0273 1.40889 12.0492 2.16298 12.8061C2.91706 13.5631 3.96656 13.5895 6.06556 13.6422C7.35921 13.6747 8.64014 13.6747 9.93381 13.6422C12.0328 13.5895 13.0823 13.5631 13.8364 12.8061C14.5905 12.0492 14.6123 11.0273 14.6558 8.98367C14.6699 8.32654 14.6699 7.67327 14.6558 7.01621C14.6123 4.97249 14.5905 3.95065 13.8364 3.19369C13.0823 2.43674 12.0328 2.41037 9.93381 2.35763C8.64014 2.32513 7.35921 2.32513 6.06555 2.35763C3.96656 2.41036 2.91706 2.43673 2.16297 3.19369C1.40889 3.95064 1.3871 4.97249 1.34351 7.01614C1.3295 7.67327 1.32951 8.32654 1.34352 8.98367Z"
                                        stroke="#7C4DFF" stroke-width="1.5" stroke-linejoin="round" />
                                </svg> {{ $user->email }}</a>
                        </div>
                    </div>
                    <div class="driver-review__profile-right">
                        <ul class="driver-review__profile-rating">
                            @php echo generateRatingStar($user->avg_rating); @endphp
                        </ul>
                        <span class="driver-review__profile-rating">@lang('Avg rating') : <span
                                class="star-count">{{ formatRating($user->avg_rating) }}</span></span>
                    </div>
                </div>
            </div>
            <h6 class="title">@lang('My Review')</h6>
            <div class="driver-review-item__wrapper">
                @forelse ($reviews as $review)
                    <div class="driver-review-item">
                        <div class="driver-review-item__header">
                            <div class="driver-review-item__author">
                                <div class="driver-review-item__thumb">
                                    <img class="fit-image"
                                        src="{{ getImage(getFilePath('driver') . '/' . $review?->ride?->driver?->image, getFileSize('driver'), true) }}"
                                        alt="img">
                                </div>
                                <div class="driver-review-item__content">
                                    <h6 class="name mb-0">{{ __($review?->ride?->driver?->fullname ?? '') }}</h6>
                                    <ul class="driver-review__profile-rating justify-content-start">
                                        @php echo generateRatingStar($review?->rating); @endphp
                                    </ul>
                                </div>
                            </div>
                            <div class="driver-review-item__date">
                                <span class="date">{{ showDateTime($review?->created_at, 'd M Y') }}</span>
                            </div>
                        </div>
                        <div class="driver-review-item__body">
                            <p class="desc">{{ __($review?->review) }}</p>
                        </div>
                    </div>
                @empty
                    @include($activeTemplate . 'partials.no_data')
                @endforelse
            </div>

            @if ($reviews->hasPages())
                {{ paginateLinks($reviews) }}
            @endif
        </div>
    </div>
@endsection
