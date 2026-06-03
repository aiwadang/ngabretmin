(function ($, window) {
    "use strict";

    const appConfig = window.appConfig;

    if (!appConfig) {
        return;
    }

    const { 
        paymentStatusCodes: { CASH, GATEWAY },
        modalAction       : { showNotifyModal },
        loader            : { buttonLoader }
    } = appConfig;

    // ================= Payment Method Change =================

    $(document).on('change', '.ridePaymentForm select[name="method_code"]', e => {

        const $select = $(e.currentTarget);
        const $form   = $select.closest('.ridePaymentForm');
        const $method = $select.find(':selected');

        const methodCurrency = $method.data('method_currency') || null;
        const paymentType    = methodCurrency ? GATEWAY : CASH;

        $form.find('[name="currency"]').val(methodCurrency ?? '').end().data('paymentType', paymentType);

        if (paymentType === GATEWAY) {

            $form.data({
                minAmount : Number($method.data('min_amount') ?? 0),
                maxAmount : Number($method.data('max_amount') ?? 0)
            });

        } else {
            $form.removeData(['minAmount', 'maxAmount']);
        }
    });

    // ================= Payment Form Submit =================

    $(document).on('submit', '.ridePaymentForm', e => {

        const $form   = $(e.currentTarget);
        const $button = $form.find('button[type="submit"]');

        const rideAmount  = Number($form.find('[name="ride_amount"]').val());
        const paymentType = $form.data('paymentType') ?? CASH;

        const methodMinAmount = Number($form.data('minAmount') ?? 0);
        const methodMaxAmount = Number($form.data('maxAmount') ?? 0);

        let errorMessage = null;

        if (!Number.isFinite(rideAmount) || rideAmount <= 0) {
            errorMessage = 'Invalid ride amount';
        }

        if (!errorMessage && paymentType === GATEWAY) {
            if (rideAmount < methodMinAmount) {
                errorMessage = 'Amount is less than the minimum amount of payment method';
            } else if (rideAmount > methodMaxAmount) {
                errorMessage = 'Amount is more than the maximum amount of payment method';
            }
        }

        if (errorMessage) {
            e.preventDefault();
            showNotifyModal('error', errorMessage);
            return;
        }

        buttonLoader($button, true);

        $form.find('[name="payment_type"]').val(paymentType);
    });

})(jQuery, window);
