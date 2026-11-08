(function ($) {
    "use strict";

    const rcInitCheckoutForm = () => {
        const $form = $('#rc-checkout-form');
        if (!$form.length) return;

        // Clear error on input
        $form.find('input, textarea').on('input change', function () {
            const $this = $(this);
            $this.closest('.form-group').find('.invalid-feedback').slideUp(200, function () { $(this).text(''); });
        });

        $form.on('submit', function (e) {
            e.preventDefault();

            const $btn = $form.find('button[type="submit"]');
            if ($btn.hasClass('is-loading')) return;

            // Reset errors
            $form.find('.invalid-feedback').hide().text('');

            let isValid = true;

            // Validate Name
            const name = $form.find('input[name="customer_name"]').val().trim();
            if (!name) {
                isValid = false;
                $form.find('input[name="customer_name"]').siblings('.invalid-feedback').text('Full name is required.').slideDown(200);
            }

            // Validate Email
            const email = $form.find('input[name="customer_email"]').val().trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email || !emailRegex.test(email)) {
                isValid = false;
                $form.find('input[name="customer_email"]').siblings('.invalid-feedback').text('A valid email address is required.').slideDown(200);
            }

            // Validate Phone
            const phone = $form.find('input[name="customer_phone"]').val().trim();
            if (!phone) {
                isValid = false;
                $form.find('input[name="customer_phone"]').siblings('.invalid-feedback').text('Phone number is required.').slideDown(200);
            }

            // Validate Pick-up
            const pickup = $form.find('input[name="customer_pickup"]').val().trim();
            if (!pickup) {
                isValid = false;
                $form.find('input[name="customer_pickup"]').siblings('.invalid-feedback').text('Pick-up location is required.').slideDown(200);
            }

            // Validate Drop-off
            const dropoff = $form.find('input[name="customer_dropoff"]').val().trim();
            if (!dropoff) {
                isValid = false;
                $form.find('input[name="customer_dropoff"]').siblings('.invalid-feedback').text('Drop-off location is required.').slideDown(200);
            }

            // Validate T&C
            const terms = $form.find('input[name="terms_conditions"]').is(':checked');
            if (!terms) {
                isValid = false;
                $form.find('input[name="terms_conditions"]').closest('.form-group').find('.invalid-feedback').text('You must accept the terms and conditions.').slideDown(200);
            }

            if (!isValid) return;

            const originalText = $btn.text();
            $btn.addClass('is-loading').text('SUBMITTING...');
            $btn.prop('disabled', true);

            // Prepare Data
            const formData = {
                action: 'rc_ajax_submit_checkout',
                nonce: php_data.tour_options_nonce,
                booking_token: $form.find('input[name="booking_token"]').val(),
                customer_name: name,
                customer_email: email,
                customer_phone: phone,
                customer_pickup: pickup,
                customer_dropoff: dropoff,
                customer_country: $form.find('input[name="customer_country"]').val().trim(),
                customer_messages: $form.find('textarea[name="customer_messages"]').val().trim(),
                payment_method: $form.find('input[name="payment_method"]:checked').val()
            };

            $.ajax({
                url: php_data.ajax_url,
                type: 'POST',
                data: formData,
                success: function (response) {
                    if (response.success) {
                        $('#booking-ref').text(response.data.reference || '');
                        $('#booking-date').text(response.data.date || '');
                        $('#booking-payment').text(response.data.payment_method || '');

                        if (response.data.country) {
                            $('#booking-country').text(response.data.country);
                            $('#summary-item-country').css('display', 'flex');
                        } else {
                            $('#summary-item-country').hide();
                        }

                        $('#booking-status').text(response.data.status || 'Pending Confirmation');
                        $('#booking-email').text(email);

                        $('#rc-checkout-form-wrapper').fadeOut(300, function () {
                            $('#rc-booking-success').fadeIn(400);
                            $('html, body').animate({
                                scrollTop: $('#rc-booking-success').offset().top - 150
                            }, 600);
                        });
                    } else {
                        $btn.removeClass('is-loading').text(originalText).prop('disabled', false);

                        let $errorBox = $form.find('.server-error');
                        if (!$errorBox.length) {
                            $form.append('<div class="server-error invalid-feedback" style="display:none; color:#dc3545; font-size:14px; margin-top:15px; text-align:center;"></div>');
                            $errorBox = $form.find('.server-error');
                        }
                        $errorBox.text(response.data.message || 'An error occurred during booking.').slideDown();
                    }
                },
                error: function () {
                    $btn.removeClass('is-loading').text(originalText).prop('disabled', false);
                    let $errorBox = $form.find('.server-error');
                    if (!$errorBox.length) {
                        $form.append('<div class="server-error invalid-feedback" style="display:none; color:#dc3545; font-size:14px; margin-top:15px; text-align:center;"></div>');
                        $errorBox = $form.find('.server-error');
                    }
                    $errorBox.text('Server error. Please try again later.').slideDown();
                }
            });
        });
    };

    $(document).ready(function () {
        rcInitCheckoutForm()
    });
})(jQuery);