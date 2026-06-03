(function($, window) {
    'use strict';

    const appConfig = window.appConfig;

    if (!appConfig) {
        return;
    }

    const { 
        routes     : { storeReview },
        modalAction: { showNotifyModal },
        loader     : { buttonLoader },
        csrfToken 
    } = appConfig;

    $(document).on('submit', '.reviewForm', e => {
        e.preventDefault();
    
        const $form   = $(e.currentTarget);
        const $modal  = $('#reviewModal');
        const $button = $form.find('[type="submit"]');
    
        const payload = {
            ride_id : $form.find('[name="ride_id"]').val(),
            rating  : $form.find('[name="rating"]').val(),
            review  : $form.find('[name="review"]').val(),
            _token  : csrfToken
        };
    
        if (!payload.ride_id) {
            return showNotifyModal('error', 'Ride not found');
        }
      
        if (!payload.rating) {
            return showNotifyModal('error', 'Please provide rating');
        }
      
        if (!payload.review) {
            return showNotifyModal('error', 'Please provide review');
        }
    
        buttonLoader($button, true);
    
        $.post(storeReview, payload, null, 'json')
            .done(({ status, message, data } = {}) => {
    
                if (status === 'success') {
                    showNotifyModal('success', message);
                    $modal.modal('hide');
                    window.location.href = data?.redirect_url ?? window.location.href;
                } else {
                    showNotifyModal('error', message);
                }
            })
            .fail(() => {
                showNotifyModal('error', 'Something went wrong while submitting review');
            })
            .always(() => buttonLoader($button, false));
    });
  
})(jQuery, window);