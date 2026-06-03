    <div class="common-card findDriverDiv">
        <h6 class="title">@lang('Find a Driver')</h6>
        <form class="find-driver-wrapper findDriverForm no-submit-loader">
            <div class="payment-select-wrapper">
                <select class="payment-select form-select form--control" name="gateway_currency_id" required>
                    <option value="cash" data-image="{{ getImage('assets/images/payment.png') }}">
                        @lang('Cash payment')
                    </option>
                    @foreach ($paymentMethods as $paymentMethod)
                        <option value="{{ $paymentMethod->id }}"
                            data-image="{{ getImage(getFilePath('gateway') . '/' . $paymentMethod?->method?->image, getFileSize('gateway')) }}">
                            {{ __($paymentMethod->name) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex gap-2">
                <div class="form-group mb-2 flex-fill">
                    <input type="number" step="any" min="0" class="form--control offerAmount"
                        name="offer_amount" value="" placeholder="@lang('Fare Amount')" required>
                </div>
                <div class="form-group mb-2 flex-fill">
                    <input type="number" min="1" class="form--control totalPerson" name="number_of_passenger"
                        placeholder="@lang('Total Passenger')" required>
                </div>
            </div>

            <input type="hidden" id="service_id" name="service_id">

            <input type="hidden" id="pickup_latitude" name="pickup_latitude" value="{{ $pickup['latitude'] ?? '' }}">
            <input type="hidden" id="pickup_longitude" name="pickup_longitude"
                value="{{ $pickup['longitude'] ?? '' }}">
            <input type="hidden" id="pickup_location" name="pickup_location" value="{{ $pickup['location'] ?? '' }}">

            <input type="hidden" id="destination_latitude" name="destination_latitude"
                value="{{ $destination['latitude'] ?? '' }}">
            <input type="hidden" id="destination_longitude" name="destination_longitude"
                value="{{ $destination['longitude'] ?? '' }}">
            <input type="hidden" id="destination_location" name="destination_location"
                value="{{ $destination['location'] ?? '' }}">

            <input type="hidden" name="service_id">
            <input type="hidden" name="payment_type">

            <div class="input-group__wrapper">
                <div class="input-group__inner">
                    <button class="btn btn--base w-100 findDriverBtn" type="submit">@lang('Find a Driver')</button>
                    <button type="button" title="@lang('Note')" class="note-btn"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none">
                            <path opacity="0.4"
                                d="M3.5 10C3.5 6.70017 3.5 5.05025 4.52513 4.02513C5.55025 3 7.20017 3 10.5 3H13.5C16.7998 3 18.4497 3 19.4749 4.02513C20.5 5.05025 20.5 6.70017 20.5 10V15C20.5 18.2998 20.5 19.9497 19.4749 20.9749C18.4497 22 16.7998 22 13.5 22H10.5C7.20017 22 5.55025 22 4.52513 20.9749C3.5 19.9497 3.5 18.2998 3.5 15V10Z"
                                fill="hsl(var(--base))" />
                            <path d="M17 2V4M12 2V4M7 2V4" stroke="hsl(var(--base))" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M3.5 10C3.5 6.70017 3.5 5.05025 4.52513 4.02513C5.55025 3 7.20017 3 10.5 3H13.5C16.7998 3 18.4497 3 19.4749 4.02513C20.5 5.05025 20.5 6.70017 20.5 10V15C20.5 18.2998 20.5 19.9497 19.4749 20.9749C18.4497 22 16.7998 22 13.5 22H10.5C7.20017 22 5.55025 22 4.52513 20.9749C3.5 19.9497 3.5 18.2998 3.5 15V10Z"
                                stroke="hsl(var(--base))" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M8 15H12M8 10H16" stroke="hsl(var(--base))" stroke-width="1.5"
                                stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
                <input type="text" class="note-input form--control" name="note" placeholder="@lang('Enter your Note')">
            </div>
        </form>
    </div>
