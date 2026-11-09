(function ($) {
    "use strict";

    const rcInitCheckoutForm = () => {
        const $form = $('#rc-checkout-form');
        if (!$form.length) return;

        let paypalSdkLoaded = false;
        let paypalSdkLoading = false;
        let paypalButtonsRendered = false;
        let currentBookingId = 0;

        // Clear error on input
        $form.find('input, textarea').on('input change', function () {
            const $this = $(this);
            $this.closest('.form-group').find('.invalid-feedback').slideUp(200, function () { $(this).text(''); });
            $form.find('.server-error').slideUp();
        });

        const showServerError = (message) => {
            let $errorBox = $form.find('.server-error');
            if (!$errorBox.length) {
                $form.find('#rc-paypal-button-wrap, #rc-standard-submit-wrap').first().before(
                    '<div class="server-error invalid-feedback alert alert-danger" style="display:none; margin: 15px 0; text-align:center;"></div>'
                );
                $errorBox = $form.find('.server-error');
            }
            $errorBox.text(message || 'An error occurred during booking.').slideDown();
        };

        const validateForm = () => {
            $form.find('.invalid-feedback').hide().text('');
            let isValid = true;
            let $firstInvalid = null;

            // Validate Name
            const name = $form.find('input[name="customer_name"]').val().trim();
            if (!name) {
                isValid = false;
                const $fb = $form.find('input[name="customer_name"]').siblings('.invalid-feedback');
                $fb.text('Full name is required.').slideDown(200);
                if (!$firstInvalid) $firstInvalid = $form.find('input[name="customer_name"]');
            }

            // Validate Email
            const email = $form.find('input[name="customer_email"]').val().trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email || !emailRegex.test(email)) {
                isValid = false;
                const $fb = $form.find('input[name="customer_email"]').siblings('.invalid-feedback');
                $fb.text('A valid email address is required.').slideDown(200);
                if (!$firstInvalid) $firstInvalid = $form.find('input[name="customer_email"]');
            }

            // Validate Phone
            const phone = $form.find('input[name="customer_phone"]').val().trim();
            if (!phone) {
                isValid = false;
                const $fb = $form.find('input[name="customer_phone"]').siblings('.invalid-feedback');
                $fb.text('Phone number is required.').slideDown(200);
                if (!$firstInvalid) $firstInvalid = $form.find('input[name="customer_phone"]');
            }

            // Validate Pick-up
            const pickup = $form.find('input[name="customer_pickup"]').val().trim();
            if (!pickup) {
                isValid = false;
                const $fb = $form.find('input[name="customer_pickup"]').siblings('.invalid-feedback');
                $fb.text('Pick-up location is required.').slideDown(200);
                if (!$firstInvalid) $firstInvalid = $form.find('input[name="customer_pickup"]');
            }

            // Validate Drop-off
            const dropoff = $form.find('input[name="customer_dropoff"]').val().trim();
            if (!dropoff) {
                isValid = false;
                const $fb = $form.find('input[name="customer_dropoff"]').siblings('.invalid-feedback');
                $fb.text('Drop-off location is required.').slideDown(200);
                if (!$firstInvalid) $firstInvalid = $form.find('input[name="customer_dropoff"]');
            }

            // Validate T&C
            const terms = $form.find('input[name="terms_conditions"]').is(':checked');
            if (!terms) {
                isValid = false;
                const $fb = $form.find('input[name="terms_conditions"]').closest('.form-group').find('.invalid-feedback');
                $fb.text('You must accept the terms and conditions.').slideDown(200);
                if (!$firstInvalid) $firstInvalid = $form.find('input[name="terms_conditions"]');
            }

            if (!isValid && $firstInvalid && $firstInvalid.length) {
                $('html, body').animate({
                    scrollTop: $firstInvalid.offset().top - 140
                }, 400);
                $firstInvalid.trigger('focus');
            }

            return isValid;
        };

        const displaySuccessScreen = (data, customerEmail) => {
            $('#booking-ref').text(data.reference || '');
            $('#booking-date').text(data.date || '');
            $('#booking-payment').text(data.payment_method || '');

            if (data.country) {
                $('#booking-country').text(data.country);
                $('#summary-item-country').css('display', 'flex');
            } else {
                $('#summary-item-country').hide();
            }

            $('#booking-status').text(data.status || 'Confirmed');
            if (data.status === 'Confirmed') {
                $('#booking-status').removeClass('badge-pending').addClass('badge-confirmed');
            }
            $('#booking-email').text(customerEmail || '');

            $('#rc-checkout-form-wrapper').fadeOut(300, function () {
                $('#rc-booking-success').fadeIn(400);
                $('html, body').animate({
                    scrollTop: $('#rc-booking-success').offset().top - 150
                }, 600);
            });
        };

        // Initialize PayPal SDK & Buttons
        const initPayPalButtons = () => {
            if (paypalButtonsRendered) return;

            const clientId = (typeof php_data !== 'undefined' && php_data.paypal_client_id) ? php_data.paypal_client_id : '';
            const currency = (typeof php_data !== 'undefined' && php_data.paypal_currency) ? php_data.paypal_currency : 'USD';

            if (!clientId) {
                $('#paypal-button-container').html(
                    '<div class="alert alert-warning text-center" style="font-size:0.875rem;">' +
                    'PayPal is currently not configured or unavailable. Please choose Bank Transfer or Pay on Arrival.' +
                    '</div>'
                );
                return;
            }

            const renderButtons = () => {
                if (typeof paypal === 'undefined' || !paypal.Buttons) {
                    showServerError('Failed to initialize PayPal. Please refresh the page.');
                    return;
                }

                if (paypalButtonsRendered) return;
                paypalButtonsRendered = true;

                paypal.Buttons({
                    style: {
                        layout: 'vertical',
                        color: 'gold',
                        shape: 'rect',
                        label: 'pay',
                        height: 48
                    },
                    onClick: function (data, actions) {
                        $form.find('.server-error').slideUp();
                        if (!validateForm()) {
                            return actions.reject();
                        }
                        return actions.resolve();
                    },
                    createOrder: function (data, actions) {
                        $('.paypal-loading-notice').html(
                            '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>' +
                            '<span>Initializing secure PayPal order...</span>'
                        ).show();

                        return new Promise(function (resolve, reject) {
                            const formData = {
                                action: 'rc_ajax_create_paypal_order',
                                nonce: php_data.tour_options_nonce,
                                booking_token: $form.find('input[name="booking_token"]').val(),
                                customer_name: $form.find('input[name="customer_name"]').val().trim(),
                                customer_email: $form.find('input[name="customer_email"]').val().trim(),
                                customer_phone: $form.find('input[name="customer_phone"]').val().trim(),
                                customer_pickup: $form.find('input[name="customer_pickup"]').val().trim(),
                                customer_dropoff: $form.find('input[name="customer_dropoff"]').val().trim(),
                                customer_country: $form.find('input[name="customer_country"]').val().trim(),
                                customer_messages: $form.find('textarea[name="customer_messages"]').val().trim(),
                            };

                            $.ajax({
                                url: php_data.ajax_url,
                                type: 'POST',
                                data: formData,
                                success: function (response) {
                                    $('.paypal-loading-notice').hide();
                                    if (response.success && response.data && response.data.orderID) {
                                        currentBookingId = response.data.booking_id;
                                        resolve(response.data.orderID);
                                    } else {
                                        const errMsg = (response.data && response.data.message) ? response.data.message : 'Could not create PayPal order.';
                                        showServerError(errMsg);
                                        reject(new Error(errMsg));
                                    }
                                },
                                error: function () {
                                    $('.paypal-loading-notice').hide();
                                    showServerError('Server connection error. Please try again.');
                                    reject(new Error('Network error'));
                                }
                            });
                        });
                    },
                    onApprove: function (data, actions) {
                        $('.paypal-loading-notice').html(
                            '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>' +
                            '<span>Verifying and finalizing payment with PayPal...</span>'
                        ).show();

                        return $.ajax({
                            url: php_data.ajax_url,
                            type: 'POST',
                            data: {
                                action: 'rc_ajax_capture_paypal_order',
                                nonce: php_data.tour_options_nonce,
                                orderID: data.orderID,
                                booking_token: $form.find('input[name="booking_token"]').val(),
                                booking_id: currentBookingId
                            },
                            success: function (response) {
                                $('.paypal-loading-notice').hide();
                                if (response.success && response.data) {
                                    displaySuccessScreen(response.data, $form.find('input[name="customer_email"]').val().trim());
                                } else {
                                    const errMsg = (response.data && response.data.message) ? response.data.message : 'Payment verification failed.';
                                    showServerError(errMsg);
                                }
                            },
                            error: function () {
                                $('.paypal-loading-notice').hide();
                                showServerError('Network error verifying payment. If your account was charged, please contact us immediately.');
                            }
                        });
                    },
                    onCancel: function (data) {
                        $('.paypal-loading-notice').hide();
                        showServerError('PayPal payment was cancelled. You can retry or choose another payment method.');
                    },
                    onError: function (err) {
                        $('.paypal-loading-notice').hide();
                        showServerError('A PayPal system error occurred. Please try again or choose another payment method.');
                    }
                }).render('#paypal-button-container');
            };

            if (typeof paypal !== 'undefined' && paypal.Buttons) {
                renderButtons();
                return;
            }

            if (paypalSdkLoading) return;
            paypalSdkLoading = true;

            const script = document.createElement('script');
            script.src = 'https://www.paypal.com/sdk/js?client-id=' + encodeURIComponent(clientId) + '&currency=' + encodeURIComponent(currency) + '&intent=capture';
            script.async = true;
            script.onload = () => {
                paypalSdkLoaded = true;
                paypalSdkLoading = false;
                renderButtons();
            };
            script.onerror = () => {
                paypalSdkLoading = false;
                showServerError('Failed to load PayPal secure checkout SDK. Please check your network or try Bank Transfer.');
            };
            document.body.appendChild(script);
        };

        // Switch between Payment Methods
        const handlePaymentMethodChange = () => {
            const selectedMethod = $form.find('input[name="payment_method"]:checked').val();

            if (selectedMethod === 'paypal') {
                $('#rc-standard-submit-wrap').hide();
                $('#rc-paypal-button-wrap').fadeIn(250);
                initPayPalButtons();
            } else {
                $('#rc-paypal-button-wrap').hide();
                $('#rc-standard-submit-wrap').fadeIn(250);
            }
        };

        $form.on('change', 'input[name="payment_method"]', handlePaymentMethodChange);

        // Initial check on page load
        handlePaymentMethodChange();

        // Standard Submit Form (for Bank Transfer & Pay on Arrival)
        $form.on('submit', function (e) {
            e.preventDefault();

            const selectedMethod = $form.find('input[name="payment_method"]:checked').val();
            // If PayPal is selected, PayPal Buttons handle payment via their SDK onClick / createOrder
            if (selectedMethod === 'paypal') {
                return;
            }

            const $btn = $form.find('button[type="submit"]');
            if ($btn.hasClass('is-loading')) return;

            if (!validateForm()) return;

            const originalText = $btn.text();
            $btn.addClass('is-loading').text('SUBMITTING...');
            $btn.prop('disabled', true);

            // Prepare Data for Bank Transfer / Pay on Arrival
            const formData = {
                action: 'rc_ajax_submit_checkout',
                nonce: php_data.tour_options_nonce,
                booking_token: $form.find('input[name="booking_token"]').val(),
                customer_name: $form.find('input[name="customer_name"]').val().trim(),
                customer_email: $form.find('input[name="customer_email"]').val().trim(),
                customer_phone: $form.find('input[name="customer_phone"]').val().trim(),
                customer_pickup: $form.find('input[name="customer_pickup"]').val().trim(),
                customer_dropoff: $form.find('input[name="customer_dropoff"]').val().trim(),
                customer_country: $form.find('input[name="customer_country"]').val().trim(),
                customer_messages: $form.find('textarea[name="customer_messages"]').val().trim(),
                payment_method: selectedMethod
            };

            $.ajax({
                url: php_data.ajax_url,
                type: 'POST',
                data: formData,
                success: function (response) {
                    if (response.success && response.data) {
                        displaySuccessScreen(response.data, formData.customer_email);
                    } else {
                        $btn.removeClass('is-loading').text(originalText).prop('disabled', false);
                        showServerError(response.data ? response.data.message : 'An error occurred during booking.');
                    }
                },
                error: function () {
                    $btn.removeClass('is-loading').text(originalText).prop('disabled', false);
                    showServerError('Server error. Please try again later.');
                }
            });
        });
    };

    $(document).ready(function () {
        rcInitCheckoutForm();
    });
})(jQuery);
