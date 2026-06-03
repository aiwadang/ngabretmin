(function ($, window) {
    "use strict";

    const appConfig = window.appConfig;
    
    if (!appConfig) {
        return;
    }

    const { 
        routes     : { addTips, removeTips },
        modalAction: { showNotifyModal },
        loader     : { buttonLoader },
        initSelectTwo  
    } = appConfig;

    const updateUI = html => {
        const $modal = $('#tipsModal');

        $modal.modal('hide');
        $('.ride_end_wrapper').html(html);

        document.activeElement?.blur();
    };

    const provideTips = ({ rideId, amount, predefined, $button}) => {

        if (!rideId) {
            return showNotifyModal('error', 'Ride not found');
        }

        if (!Number.isFinite(amount) || amount <= 0) {
            return showNotifyModal('error', 'Tips amount not provided');
        } 

        const payload = {
            ride_id        : rideId,
            amount         : amount,
            predefined_tips: predefined
        };

        buttonLoader($button, true);

        console.log('this called tips',addTips);
        
        $.get(addTips, payload, null, 'json')
            .done(({ status, message, data } = {}) => {
                if (status === 'success') {
                    showNotifyModal('success', message);
                    updateUI(data?.html);
                    initSelectTwo();
                } else {
                    showNotifyModal('error', message);
                }
            })
            .fail(() => showNotifyModal('error', 'Something went wrong while adding tips'))
            .always(() => buttonLoader($button, false));
    }


    $(document).on('click', '.addTipsButton', e => {
        e.preventDefault();

        const $btn = $(e.currentTarget);
        const rideId = $btn.data('ride_id');

        if (!rideId) {
            return showNotifyModal('error', 'Ride not found');
        }

        const $modal = $('#tipsModal');
        $modal.find('[name="ride_id"]').val(rideId);
        $modal.modal('show');
    });

    $(document).on('click', '.tipsAmountBtn', e => {
        e.preventDefault();

        const $btn   = $(e.currentTarget);
        const $modal = $btn.closest('.modal');

        const rideId  = $modal.find('[name="ride_id"]').val();
        const amount  = Number($btn.data('tips_amount') ?? 0);
        const $button = $modal.find('button[type="submit"]');

        provideTips({
            rideId,
            amount,
            predefined: 1,
            $button
        });
    });

    $(document).on('submit', '.tipsForm', e => {
        e.preventDefault();

        const $form = $(e.currentTarget);
        const rideId  = $form.find('[name="ride_id"]').val();
        const amount  = Number($form.find('[name="amount"]').val());
        const $button = $form.find('button[type="submit"]');

        provideTips({
            rideId,
            amount,
            predefined: 0,
            $button
        });
    });

    $(document).on('click', '.removeTipsButton', e => {
        e.preventDefault();

        const $btn   = $(e.currentTarget);
        const rideId = $btn.data('ride_id');

        if (!rideId) {
            return showNotifyModal('error', 'Ride not found');
        }

        const payload = { ride_id: rideId };

        $.get(removeTips, payload, null, 'json')
            .done(({ status, message, data } = {}) => {

                if (status === 'success') {
                    $('.ride_end_wrapper').html(data?.html);
                    initSelectTwo();
                    showNotifyModal('success', message);
                } else {
                    showNotifyModal('error', message);
                }

            })
            .fail(() => showNotifyModal('error', 'Something went wrong while removing tips'));
    });

})(jQuery, window);
