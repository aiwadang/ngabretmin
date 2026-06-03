(function($, window) {
    'use strict';

    const appConfig = window.appConfig;

    if (!window.pusher || !appConfig) {
        return;
    }

    const {
        userId,
        csrfToken,
        initSelectTwo,
        formatRating,
        generalSetting: { allowPrecision, curSym, curText, distanceUnit },
        assets        : { cash : cashImage },
        routes        : { savePayment : paymentSave },
        loader        : { carRunningAnimation },
        modalAction   : { showNotifyModal },
        paymentMethods
    } = appConfig;

    const precision = Number(allowPrecision);

    if (!userId) {
        return;
    }

    const $bidsWrapper        = $('.bids_wrapper');
    const $runningRideWrapper = $('.running_ride_wrapper');
    const $bidPopupModal      = $('#bidPopupModal');
    const $rideEndWrapper     = $('.ride_end_wrapper');

    const allowedEvents = ['NEW_BID', 'PICK_UP', 'RIDE_END', 'CASH_PAYMENT_RECEIVED'];

    const fillBidModal = ({ bid, driver_image }) => {
        const { driver } = bid;

        $('#bidPopupModal .driver-avatar').attr('src', driver_image);
        $('#bidPopupModal .driver-name').text(`${driver.firstname} ${driver.lastname}`);
        $('#bidPopupModal .star-rating strong').text(`${formatRating(driver.avg_rating ?? 0)}`);
        $('#bidPopupModal .car-model').text(`${driver.vehicle?.model?.name ?? ''}`);
        $('#bidPopupModal .ride-fare').text(`${Number(bid.bid_amount).toFixed(precision)} ${curText}`);

        $('#bidPopupModal .bidConfirmBtn, #bidPopupModal .bidRejectBtn').attr('data-bid_id', bid.id);
    };

    const appendBidInfo = ({ bid, driver_image }) => {
        const { driver } = bid;
        const vehicle    = driver.vehicle ?? {};

        return `
            <div class="common-card mt-4" data-driver-id="${driver.id}" id="bid-card-${bid.id}">
                <div class="ride-confirmation-card">
                    <div class="ride-confirmation__header">
                        <div class="ride-confirmation__driver-info">
                            <img src="${driver_image}" alt="Driver Avatar" class="driver-avatar">
                            <div class="driver-details">
                                <div class="driver-name">${driver.firstname} ${driver.lastname}</div>
                                <div class="driver-car-rating">
                                    <span class="star-rating"><i class="fa-solid fa-star"></i>
                                        <strong class="rating__value">${formatRating(driver.avg_rating ?? 0)}</strong>
                                    </span>
                                    <span class="separator"> • </span>
                                    <span class="car-model">${vehicle?.model?.name ?? ''}</span>
                                </div>
                            </div>
                        </div>
                        <div class="ride-confirmation__fare">${bid.bid_amount} ${curText}</div>
                    </div>

                    <div class="ride-confirmation__actions">
                        <button class="btn btn-outline--danger flex-grow-1 bidRejectBtn" data-rep="side" data-bid_id="${bid.id}">Reject</button>
                        <button class="btn btn--base flex-grow-1 bidConfirmBtn" data-rep="side" data-bid_id="${bid.id}">Confirm</button>
                    </div>
                </div>
            </div>
        `;
    };

    const handleNewBid = (data) => {
        const showModal = () => {
            fillBidModal(data);
            $bidsWrapper.append(appendBidInfo(data));
            $bidPopupModal.modal('show');
        }

        if ($bidPopupModal.hasClass('show')) {
            $bidPopupModal.one('hidden.bs.modal', showModal);
            $bidPopupModal.modal('hide');
        } else {
            showModal();
        }
    };

    const rideStartingInfo = ({ ride, driver_image, driver_completed_ride }) => {
        return `
             <div class="common-card mt-4">
                <div class="progress_item">
                    <div class="progress">
                        <div class="progress_bar wow" style="--bar-width:100%">
                            <span class="value_text">
                                <svg width="40" height="14" viewBox="0 0 40 14" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M1.17482 4.36187C1.42508 3.97855 1.84218 3.74856 2.25927 3.67189C4.59501 3.36524 7.68152 1.60198 9.09964 1.29533C12.1861 0.60536 19.9441 -0.621254 22.8638 0.37537C27.6187 2.13863 28.7866 3.36524 29.6207 3.67189C32.6238 3.97855 35.2932 4.51519 37.7124 5.20516C38.0461 5.35849 38.2963 5.51181 38.5466 5.7418C39.7145 6.89176 40.1316 8.2717 39.9647 9.88163C39.7979 11.0316 38.7134 11.8749 37.4621 11.8749C37.629 11.4149 37.7124 10.9549 37.7124 10.4949C37.7124 7.88838 35.3767 5.7418 32.5404 5.7418C29.7042 5.7418 27.3684 7.88838 27.3684 10.4949C27.3684 10.9549 27.4518 11.4916 27.6187 11.8749H12.8535C13.0203 11.4149 13.1038 10.9549 13.1038 10.4949C13.1038 7.88838 10.768 5.7418 7.93177 5.7418C5.09552 5.7418 2.75979 7.88838 2.75979 10.4949C2.75979 10.9549 2.8432 11.3382 2.92662 11.7215C2.34269 11.5682 1.67534 11.4149 0.924566 11.0316C0.507471 10.8016 0.257212 10.4183 0.173793 9.95829C-0.243303 8.04171 0.0903743 6.12512 1.17482 4.36187ZM19.1099 3.97855L26.5342 4.20854L27.1182 4.05521C25.7 3.21191 24.1151 2.52194 22.2799 1.83197C22.1964 1.83197 22.113 1.75531 22.0296 1.75531C21.112 1.60198 20.111 1.60198 19.1099 1.60198V3.97855ZM10.2675 3.67189L17.4416 3.90188V1.67865C15.1058 1.75531 12.6032 2.13863 9.76699 2.75193L10.2675 3.67189Z"
                                        fill="hsl(var(--base))" />
                                    <path
                                        d="M35.9609 10.495C35.9609 8.73175 34.376 7.27515 32.4573 7.27515C30.5387 7.27515 28.9537 8.73175 28.9537 10.495C28.9537 12.2583 30.5387 13.7149 32.4573 13.7149C34.376 13.7149 35.9609 12.2583 35.9609 10.495Z"
                                        fill="hsl(var(--base))" />
                                    <path
                                        d="M4.26135 10.495C4.26135 12.2583 5.84631 13.7149 7.76495 13.7149C9.68359 13.7149 11.2686 12.2583 11.2686 10.495C11.2686 8.73175 9.68359 7.27515 7.76495 7.27515C5.84631 7.27515 4.26135 8.73175 4.26135 10.495Z"
                                        fill="hsl(var(--base))" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="ride-booking__calculation mt-3">
                    <div class="heading text-center">
                        <p class="subtitle fs-14">Your ride is now in progress</p>
                    </div>
                </div>

                <ul class="location-list mt-3">
                    <li class="location-item start-location">
                        <span class="icon-line-wrapper">
                            <span class="icon" role="img" aria-label="Starting location marker"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 20 20" fill="none">
                                    <g clip-path="url(#clip0_2340_2542)">
                                        <path
                                            d="M17.0948 10.0001C17.0948 13.9121 13.9234 17.0834 10.0114 17.0834C6.09939 17.0834 2.92807 13.9121 2.92807 10.0001C2.92807 6.08806 6.09939 2.91675 10.0114 2.91675C13.9234 2.91675 17.0948 6.08806 17.0948 10.0001Z"
                                            stroke="hsl(var(--base))" stroke-width="1.5" />
                                        <path d="M18.75 10H17.0833" stroke="hsl(var(--base))" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M2.91667 10H1.25" stroke="hsl(var(--base))" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M10 1.25V2.91667" stroke="hsl(var(--base))" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M10 17.0833V18.7499" stroke="hsl(var(--base))" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path opacity="0.4"
                                            d="M12.5 10C12.5 11.3807 11.3807 12.5 10 12.5C8.61926 12.5 7.49997 11.3807 7.49997 10C7.49997 8.61925 8.61926 7.5 10 7.5C11.3807 7.5 12.5 8.61925 12.5 10Z"
                                            stroke="hsl(var(--base))" stroke-width="1.5" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_2340_2542">
                                            <rect width="20" height="20" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg></span>
                            <span class="connector-line"></span>
                        </span>
                        <div class="location-name">${ride.pickup_location}</div>
                    </li>

                    <li class="location-item mid-location">
                        <span class="icon-line-wrapper">
                            <span class="icon" role="img" aria-label="Intermediate location marker">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 20 20" fill="none">
                                    <path
                                        d="M12.0833 7.50008C12.0833 8.65066 11.1506 9.58341 9.99999 9.58341C8.84941 9.58341 7.91666 8.65066 7.91666 7.50008C7.91666 6.34949 8.84941 5.41675 9.99999 5.41675C11.1506 5.41675 12.0833 6.34949 12.0833 7.50008Z"
                                        stroke="hsl(var(--base))" stroke-width="1.5" />
                                    <path
                                        d="M11.0478 14.5781C10.7667 14.8487 10.3911 15.0001 10.0002 15.0001C9.60916 15.0001 9.23349 14.8487 8.95241 14.5781C6.37857 12.0841 2.92931 9.298 4.61141 5.25319C5.52091 3.06618 7.70411 1.66675 10.0002 1.66675C12.2962 1.66675 14.4793 3.06619 15.3888 5.25319C17.0688 9.29291 13.628 12.0927 11.0478 14.5781Z"
                                        stroke="hsl(var(--base))" stroke-width="1.5" />
                                    <path opacity="0.4"
                                        d="M15 16.6667C15 17.5872 12.7614 18.3334 10 18.3334C7.23857 18.3334 5 17.5872 5 16.6667"
                                        stroke="hsl(var(--base))" stroke-width="1.5" stroke-linecap="round" />
                                </svg>
                            </span>
                        </span>
                        <div class="location-name">${ride.destination}</div>
                    </li>
                </ul>
                <div class="ride-booking__calculation">
                    <div class="ride-booking__calculation-container">
                        <div class="ride-booking__calculation-item">
                            <div class="ride-booking__value">${ride.distance} ${distanceUnit}</div>
                            <div class="ride-booking__label">Distance</div>
                        </div>
                        <div class="ride-booking__calculation-item">
                            <div class="ride-booking__value">${ride.duration}</div>
                            <div class="ride-booking__label">Estimated Time</div>
                        </div>
                        <div class="ride-booking__calculation-item">
                            <div class="ride-booking__value">${Number(ride.amount).toFixed(precision)} ${curText}</div>
                            <div class="ride-booking__label">Ride Fare</div>
                        </div>
                    </div>
                </div>
                <div class="ride-confirmation__header mt-4">
                    <div class="ride-confirmation__driver-info">
                        <div class="ride-confirmation__driver-info__thumb">
                            <span class="star-rating"><i class="fa-solid fa-star"></i>
                                <strong>${formatRating(Number(ride.driver?.avg_rating))}</strong></span>
                            <img src="${driver_image}" alt="Driver Avatar" class="driver-avatar">
                        </div>
                        <div class="driver-details">
                            <div class="driver-name">${ride.driver?.firstname} ${ride.driver?.lastname}</div>
                            <div class="driver-car-rating">
                                <span class="car-model">Ride Completed: ${driver_completed_ride}</span>
                            </div>
                        </div>
                    </div>
                    <div class="ride-confirmation__fare text-right">
                        <p class="fs-14"> ${ride.driver?.vehicle?.brand?.name ?? ''}</p>
                        <span class="fs-18 fw-500">(${ride.driver?.vehicle?.model?.name ?? ''})</span>
                    </div>
                </div>
            </div>
        `;
    };

    const rideEndInfo = ({ ride }) => {

        const paymentGatewaysOptions = paymentMethods.map(({ method_code, currency, min_amount, max_amount, image, name}) => `
            <option value="${method_code}"
                data-method_currency="${currency}"
                data-min_amount="${min_amount}"
                data-max_amount="${max_amount}"
                data-image="${image}">
                ${name}
            </option>
        `).join('');

        const rideAmount     = Number(ride.amount);
        const discountAmount = Number(ride.discount_amount);
        const tipsAmount     = Number(ride.tips_amount);
        const payableAmount  = rideAmount - discountAmount + tipsAmount;

        setTimeout(initSelectTwo, 30);

        return  `
            <div class="common-card mt-4">
                <div class="destination-info">
                    <div class="flex-wrap justify-content-between align-items-center">
                        <div class="destination-info__tip-left">
                            <strong class="strong">Add Coupon Code</strong>
                        </div>
                        <div class="destination-info__tip-right">
                            <button class="btn btn-outline--base btn--sm couponModalBtn" data-ride_id="${ride.id}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none">
                                    <g clip-path="url(#clip0_2340_2768)">
                                        <path
                                            d="M18.3333 10.0001C18.3333 5.39771 14.6023 1.66675 9.99996 1.66675C5.39758 1.66675 1.66663 5.39771 1.66663 10.0001C1.66663 14.6024 5.39758 18.3334 9.99996 18.3334C14.6023 18.3334 18.3333 14.6024 18.3333 10.0001Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path opacity="0.4" d="M9.99996 6.66675V13.3334M13.3333 10.0001H6.66663"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_2340_2768">
                                            <rect width="20" height="20" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                                Add
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST" action="${paymentSave}" class="common-card mt-4 ridePaymentForm">
                
                <input type="hidden" name="_token" value="${csrfToken}">

                <div class="destination-info">
                    <span class="destination-info__btn d-block text-center">
                        Ride Summery!
                    </span>

                    <div class="row g-2">

                        <div class="destination-info__tip flex-wrap justify-content-between align-items-center">
                            <div class="destination-info__tip-left"><strong>Ride Amount</strong></div>
                            <div class="destination-info__tip-right"><strong>${curSym} ${rideAmount.toFixed(precision)}</strong></div>
                        </div>

                        <div class="destination-info__tip flex-wrap justify-content-between align-items-center">
                            <div class="destination-info__tip-left"><strong>Discount</strong></div>
                            <div class="destination-info__tip-right"><strong>${curSym} ${discountAmount.toFixed(precision)}</strong></div>
                        </div>

                        <div class="destination-info__tip flex-wrap justify-content-between align-items-center">
                            <div class="destination-info__tip-left"><strong>Tips</strong></div>
                            <div class="destination-info__tip-right">
                                <button class="addTipsButton add-tips" data-ride_id="${ride.id}">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="destination-info__tip flex-wrap justify-content-between align-items-center">
                            <div class="destination-info__tip-left"><strong>Payable Amount</strong></div>
                            <div class="destination-info__tip-right">
                                <strong>${curSym} ${payableAmount.toFixed(precision)}</strong>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Hidden Inputs -->
                <input type="hidden" name="currency">
                <input type="hidden" name="payment_type">
                <input type="hidden" name="ride_id" value="${ride.id}">
                <input type="hidden" name="ride_amount" value="${payableAmount}">

                <div class="ride-confirmation__header mt-3">
                    <div class="payment-select-wrapper w-100 mt-2">
                        <select class="payment-select form-select form--control" name="method_code">
                            <option value="cash" data-image="${cashImage}">
                                Cash payment
                            </option>
                            ${paymentGatewaysOptions}
                        </select>
                    </div>
                </div>

                <button class="btn btn--base w-100" type="submit">Payment</button>
            </form>
        `;
    };

    const showReviewModal = ({ ride, driver_image, driver_completed_ride }) => {
        const { driver } = ride;
        const vehicle    = driver.vehicle ?? {};

        const $modal = $('#reviewModal');

        $modal.find('.driver-avatar').attr('src', driver_image);
        $modal.find('.driver-name').text(`${driver.firstname} ${driver.lastname}`);
        $modal.find('.star-rating strong').text(formatRating(Number(driver.avg_rating)) ?? 'N/A');
        $modal.find('.car-model').text(`Ride Completed: ${driver_completed_ride}`);
        $modal.find('.carModel').text(`${vehicle?.model?.name ?? ''}`);
        $modal.find('input[name="ride_id"]').val(ride.id);
        $modal.find('input[name="rating"]').val('');
        $modal.find('textarea[name="review"]').val('');

        $modal.modal('show');
    };

    const handleEvent = (type, data) => {
        switch (type) {
            case 'NEW_BID': 
                handleNewBid(data);
                break;

            case 'PICK_UP':
                $bidsWrapper.empty();
                $('.active_ride_wrapper').empty();
                $runningRideWrapper.html(rideStartingInfo(data));
                carRunningAnimation();
                break;

            case 'RIDE_END':
                $runningRideWrapper.empty();
                $rideEndWrapper.html(rideEndInfo(data));
                break;

            case 'CASH_PAYMENT_RECEIVED':
                showReviewModal(data);
                break;

            default:
                showNotifyModal('error', `Unhandled event type: ${type}`);
        }
    }

    window.pusher.connection.bind('connected', () => {
        const socketId    = window.pusher.connection.socket_id;
        const channelName = `private-rider-user-${userId}`;

        if (typeof makeAuthEndPointForPusher == 'function') {
            window.pusher.config.authEndpoint = makeAuthEndPointForPusher(socketId, channelName);
        }

        const channel = window.pusher.subscribe(channelName);

        channel.bind('pusher:subscription_succeeded', () => {
            allowedEvents.forEach(event => channel.bind(event, e => handleEvent(event, e.data)));
        });
    });

})(jQuery, window);