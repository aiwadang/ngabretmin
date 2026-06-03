<div class="ride-request-banner">
    <div class="ride-request__header">
        <div class="ride-request__title-group">
            <span class="ride-request__icon" aria-hidden="true">
                <i class="las la-exclamation"></i>
            </span>
            <span class="ride-request__title">@lang('New Bid Request From Driver')</span>
        </div>
        <button class="ride-request__close-btn" data-bs-dismiss="modal" aria-label="Close">
            <i class="las la-times"></i>
        </button>
    </div>
    <div class="ride-request__content">
        <div class="driver-info">
            <img src="{{ getImage(getFilePath('driver') . '/' . $bid->driver?->image, getFileSize('driver')) }}"
                alt="Driver Avatar" class="driver-avatar">
            <div class="driver-details">
                <div class="driver-name">{{ __($bid->driver?->fullname) }}</div>
                <div class="driver-car-rating">
                    <span class="star-rating"><i class="fa-solid fa-star"></i>
                        <strong>{{ $bid->driver?->avg_rating }}</strong></span>
                    <span class="separator"> • </span>
                    <span class="car-model">{{ $bid->driver?->vehicle?->model?->name }}</span>
                </div>
            </div>
        </div>

        <div class="ride-request__actions-group ">
            <div class="ride-fare">{{ showAmount($bid->bid_amount, currencyFormat: false) . ' ' . gs('cur_text') }}</div>
            <div class="flex-wrap gap-3">
                <button class="btn btn--base bidConfirmBtn" data-bid_id="{{ $bid->id }}">@lang('Confirm')</button>
                <button class="btn btn--cancel bidRejectBtn"
                    data-bid_id="{{ $bid->id }}">@lang('Reject')</button>
            </div>
        </div>
    </div>
</div>
