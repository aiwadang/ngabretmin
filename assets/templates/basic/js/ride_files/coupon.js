(function ($, window) {
    "use strict";

    const appConfig = window.appConfig;
    if (!appConfig) {
        return;
    }

    const { 
        routes     : { couponIndex, couponApply, couponRemove },
        modalAction: { showNotifyModal},
        initSelectTwo  
    } = appConfig;

    const couponModalSkeleton = () => `
        <div class="ride-request-banner">
            <div class="ride-request__header justify-content-between">
                <h5 class="mb-0 skeleton-text w-50"></h5>
                <div class="skeleton-circle"></div>
            </div>
        </div>
        <div class="modal-body pt-2">
            <form class="no-submit-loader">
                <div class="ride-review-wrapper text-center">
                    <div class="form-group">
                        <div class="skeleton-input"></div>
                    </div>
                    <div class="ride-review__btn">
                        <div class="skeleton-btn w-100"></div>
                    </div>
                </div>
            </form>
            <div class="coupon-list mt-3">
                <div class="coupon-modal-card skeleton-card mb-2"></div>
                <div class="coupon-modal-card skeleton-card mb-2"></div>
                <div class="coupon-modal-card skeleton-card mb-2"></div>
            </div>
        </div>`;

    const toggleButton = ($btn, isLoading, loadingText = 'Processing...', defaultText = 'Apply') => {
        if (!$btn?.length) {
            return;
        } 

        $btn.prop('disabled', isLoading).text(isLoading ? loadingText : defaultText);
    };

    const applyCoupon = (code, rideId, $button) => {
        if (!code) {
            return showNotifyModal('error', 'Coupon code not provided');
        }

        if (!rideId) {
            return showNotifyModal('error', 'Ride not found');
        } 

        toggleButton($button, true, 'Applying...', 'Apply');

        const payload = {
            code   : code,
            ride_id: rideId
        };

        $.get(couponApply, payload, null, 'json')
            .done(({ status, message, data } = {}) => {
                
                if (status === 'success') {
                    $('#couponModal').modal('hide');
                    $('.ride_end_wrapper').html(data?.html ?? '');
                    initSelectTwo();
                    showNotifyModal('success', message);
                } else {
                    showNotifyModal('error', message);
                    toggleButton($button, false, '', 'Apply');
                }

            }).fail(() => {
                showNotifyModal('error', 'Something went wrong while applying coupon');
                toggleButton($button, false, '', 'Apply');
            });
    };
    

    $(document).on('click', '.couponModalBtn', e => {
        const $btn   = $(e.currentTarget);
        const rideId = $btn.data('ride_id');
        const $modal = $('#couponModal');

        if (!rideId) {
            showNotifyModal('error', 'Ride not found');
        }

        $modal.find('.couponModalContent').html(couponModalSkeleton());
        $modal.modal('show');

        const payload  = { ride_id : rideId };
        const fallback = () => $modal.modal('hide');

        $.get(couponIndex, payload, null, 'json')
            .done(({ status, message, data } = {}) => {

                if (status == 'success') {
                    $modal.find('.couponModalContent').html(data?.html ?? '');
                    initSelectTwo();
                } else {
                    fallback();
                    showNotifyModal('error', message ?? 'Failed to load coupon');
                }

            }).fail(() => {
                fallback();
                showNotifyModal('error', 'Something went wrong while showing coupon');
            });
    });

    $(document).on('submit', '.couponApplyForm', e => {
        e.preventDefault();

        const $form   = $(e.currentTarget);
        const code    = $form.find('[name=code]').val();
        const rideId  = $form.find('[name=ride_id]').val();
        const $button = $form.find('button');

        applyCoupon(code, rideId, $button);
    });

    $(document).on('click', '.couponApplyBtn', e => {

        const $button = $(e.currentTarget);
        const code    = $button.data('coupon_code');
        const rideId  = $button.data('ride_id');

        applyCoupon(code, rideId, $button);
    });


    $(document).on('click', '.removeCouponBtn', e => {

        const $button = $(e.currentTarget);
        const rideId  = $button.data('ride_id');

        if (!rideId) {
            return showNotifyModal('error', 'Ride not found');
        } 

        toggleButton($button, true, 'Removing...', 'Remove');

        const payload = {
            ride_id : rideId
        };

        $.get(couponRemove, payload, null, 'json')
            .done(({ status, message, data } = {}) => {

                if (status == 'success') {
                    $('.ride_end_wrapper').html(data.html);
                    initSelectTwo();
                    showNotifyModal('success', message);
                } else {
                    showNotifyModal('error', message);
                    toggleButton($button, false, '', 'Remove');
                }

            }).fail(() => {
                showNotifyModal('error', 'Something went wrong while removing coupon');
                toggleButton($button, false, '', 'Remove');
            });
    });

})(jQuery, window);
