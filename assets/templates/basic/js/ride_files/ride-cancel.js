(function($, window) {
    'use strict';

    const appConfig = window.appConfig;

    if (!appConfig) {
        return;
    }

    const {
        routes     : { cancelRide },
        loader     : { buttonLoader },
        modalAction: { showNotifyModal},
        csrfToken
    } = appConfig

    $(document).on('click', '.rideCancelBtn', e => {

        const $btn   = $(e.currentTarget);
        const rideId = $btn.data('ride_id');
        const $modal = $('#rideCancelModal');

        if (!rideId) {
            return showNotifyModal('error', 'Ride not found');
        }

        $modal.find('[name="ride_id"]').val(rideId);
        $modal.modal('show');
    });

    $(document).on('submit', '.rideCancelForm', e => {
        e.preventDefault();

        const $form        = $(e.currentTarget);
        const rideId       = $form.find('[name="ride_id"]').val();
        const cancelReason = $form.find('[name="cancel_reason"]').val();
        const $button      = $form.find('button[type="submit"]');

        if (!rideId) {
            return showNotifyModal('error', 'Ride not found');
        }

        if (!cancelReason) {
            return showNotifyModal('error', 'Cancel reason not provided');
        }

        const payload = {
            ride_id      : rideId,
            cancel_reason: cancelReason,
            _token       : csrfToken
        };

        buttonLoader($button, true);

        $.post(cancelRide, payload, null, 'json')
            .done(({ status, message } = {}) => {

                if (status === 'success') {
                    $('.rideDetailDiv').remove();
                    $('.bids_wrapper').empty();
                    $('#rideCancelModal').modal('hide');

                    showNotifyModal('success', message);
                    window.location.reload();
                } else {
                    showNotifyModal('error', message);
                }

            })
            .fail(() => showNotifyModal('error', 'Something went wrong while cancelling ride'))
            .always(() => buttonLoader($button, false));
    });

})(jQuery, window);