@foreach ($runningRideBids as $bid)
    <div class="common-card mt-4" id="bid-card-{{ $bid->id }}">
        <div class="ride-confirmation-card">
            <div class="ride-confirmation__header">
                <div class="ride-confirmation__driver-info">
                    <img src="{{ getImage(getFilePath('driver') . '/' . $bid->driver->image, getFileSize('driver'), true) }}"
                        alt="Driver Avatar" class="driver-avatar">
                    <div class="driver-details">
                        <div class="driver-name">{{ __($bid->driver->fullname) }}</div>
                        <div class="driver-car-rating">
                            <span class="star-rating"><i class="fa-solid fa-star"></i>
                                <strong class="rating__value">{{ formatRating($bid->driver->avg_rating) }}</strong></span>
                            <span class="separator"> •</span>
                            <span class="car-model">{{ optional($bid->driver->vehicle->model)->name }}</span>
                        </div>
                    </div>
                </div>
                <div class="ride-confirmation__fare">
                    {{ showAmount($bid->bid_amount, currencyFormat: false) . ' ' . gs('cur_text') }}
                </div>
            </div>

            <div class="ride-confirmation__actions">
                <button class="btn btn-outline--danger flex-grow-1 bidRejectBtn"
                    data-rep="side" data-bid_id="{{ $bid->id }}">@lang('Reject')</button>
                <button class="btn btn--base flex-grow-1 bidConfirmBtn"
                    data-rep="side" data-bid_id="{{ $bid->id }}">@lang('Confirm')</button>
            </div>
        </div>
    </div>
@endforeach