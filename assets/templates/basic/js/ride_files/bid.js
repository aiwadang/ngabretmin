(function($) {
    'use strict';

    const appConfig = window.appConfig;

    if (!appConfig) {
        return;
    }

    const { 
        csrfToken, 
        routes     : { rejectBid, acceptBid },
        loader     : { showTypingLoader },
        modalAction: { showNotifyModal },
    } = appConfig;

    const rollbackBidAction = ($btn, $bidDiv, $previousHtml, repType, action) => {
        if (repType === 'popup') {
            $btn.text(action).prop('disabled', false);
        } else {
            $bidDiv.html($previousHtml);
        }
    };

    $(document).on('click', '.bidRejectBtn', e => {
        const $btn    = $(e.currentTarget);
        const bidId   = $btn.data('bid_id');
        const repType = $btn.data('rep');

        if (!bidId) {
            return showNotifyModal('error', 'Bid not found');
        }

        const $bidSideDiv   = $(`#bid-card-${bidId}`);
        const $previousHtml = $bidSideDiv.html();

        if (repType == 'popup') {
            $btn.text('Rejecting...').prop('disabled', true);
        } else {
            $bidSideDiv.html(showTypingLoader('Rejecting Bid'));
        }

        const payload  = { bid_id : bidId };
        const rollback = () => rollbackBidAction($btn, $bidSideDiv, $previousHtml, repType, 'Reject');

        $.get(rejectBid, payload, null, 'json')
        .done(({ status, message } = {}) => {    

            if (status === 'success') {
                if (repType === 'popup') {
                    $btn.text('Reject').prop('disabled', false);
                }

                $bidSideDiv.fadeOut(200, () => $bidSideDiv.remove());

                const $modal = $('#bidPopupModal');
                
                if ($modal.length) {
                    $modal.modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }

                showNotifyModal('success', message);
            } else {
                rollback();
                showNotifyModal('error', message);
            }

        })
        .fail(() => {
            rollback();
            showNotifyModal('error', 'Something went wrong while rejecting bid');
        }); 
    });

    $(document).on('click', '.bidConfirmBtn', e => {

        const $btn    = $(e.currentTarget);
        const bidId   = $btn.data('bid_id');
        const repType = $btn.data('rep');

        if (!bidId) {
            return showNotifyModal('error', 'Bid not found');
        }

        const $bidSideDiv   = $(`#bid-card-${bidId}`);
        const $previousHtml = $bidSideDiv.html();

        if (repType == 'popup') {
            $btn.text('Confirming...').prop('disabled', true);
        } else {
            $bidSideDiv.html(showTypingLoader('Confirming Bid'));
        }

        const payload = {
            bid_id : bidId,
            _token : csrfToken
        };

        const rollback = () => rollbackBidAction($btn, $bidSideDiv, $previousHtml, repType, 'Confirm');

        $.post(acceptBid, payload, null, 'json')
        .done(({ status, message, data } = {}) => {

            if (status === 'success') {
                $('.rideDetailDiv').remove();
                $('.bids_wrapper').empty();
                $('.active_ride_wrapper').html(data?.html ?? '');
                $('#bidPopupModal').modal('hide');

                showNotifyModal('success', message);
            } else {
                rollback();
                showNotifyModal('error', message);
            }

        })
        .fail(() => {
            rollback();
            showNotifyModal('error', 'Something went wrong while accepting bid');
        }); 
    });
    
})(jQuery);