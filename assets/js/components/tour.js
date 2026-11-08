(function ($) {
    "use strict";

    const rcInitQuantitySelectors = () => {
        $(document).on('click', '.quantity-selector .btn-qty, .quantity-selector .qty-btn', function (e) {
            e.preventDefault();
            const $btn = $(this);
            const $input = $btn.siblings('input[type="number"]');
            if (!$input.length) return;

            let val = parseInt($input.val()) || 0;
            const min = parseInt($input.attr('min')) || 0;
            const max = parseInt($input.attr('max')) || 50;

            if ($btn.hasClass('btn-plus') || $btn.text().trim() === '+') {
                if (val < max) val++;
            } else if ($btn.hasClass('btn-minus') || $btn.text().trim() === '-') {
                if (val > min) val--;
            }

            $input.val(val).trigger('change');
        });
    };

    const rcInitAjaxTourOptions = () => {
        const $form = $('.rc-form-booking, .vm-form-booking');
        const $btn = $form.find('#rc-btn-check-availability, .rc-btn-check-availability, #vm-btn-check-availability, .vm-btn-check-availability');
        const $container = $('#rc-tour-options-container, #vm-tour-options-container, .rc-tour-options-container');

        if (!$form.length || !$btn.length || !$container.length) return;

        $container.hide();

        $btn.on('click', function (e) {
            e.preventDefault();

            const date = $form.find('input[type="date"]').val();
            const $error = $form.find('.rc-form-error, .vm-form-error');

            $error.hide().text('');

            if (!date) {
                $error.text('Please select a tour date.').slideDown(200);
                return;
            }

            const adults = parseInt($form.find('.quantity-selector input').eq(0).val()) || 0;
            const children = parseInt($form.find('.quantity-selector input').eq(1).val()) || 0;

            if ($btn.hasClass('is-loading')) return;

            const originalText = $btn.text();
            $btn.addClass('is-loading').text('CHECKING...');
            $btn.prop('disabled', true);

            const postId = $container.data('post-id') || $('[data-post-id]').data('post-id');

            $.ajax({
                url: php_data.ajax_url,
                type: 'POST',
                data: {
                    action: 'rc_ajax_check_availability',
                    nonce: php_data.tour_options_nonce,
                    post_id: postId,
                    date: date,
                    adults: adults,
                    children: children
                },
                success: function (response) {
                    $btn.removeClass('is-loading').text(originalText);
                    $btn.prop('disabled', false);

                    if (response.success) {
                        if (response.data.count > 0 && response.data.html) {
                            $container.html(response.data.html);
                            $container.slideDown(400, function () {
                                $('html, body').animate({
                                    scrollTop: $container.offset().top - 120
                                }, 600);
                            });
                        } else {
                            $container.html('<div class="container"><div class="rc-tour-options-empty vm-tour-options-empty" style="padding:40px 20px;text-align:center;background:#fff;border-radius:12px;margin:30px 0;border:1px solid #eaeaea;">No available tour options found for the selected date. Please try another date.</div></div>');
                            $container.slideDown(400);
                        }
                    } else {
                        const msg = (response.data && response.data.message) ? response.data.message : 'An error occurred.';
                        $error.text(msg).slideDown(200);
                    }
                },
                error: function (xhr) {
                    $btn.removeClass('is-loading').text(originalText);
                    $btn.prop('disabled', false);
                    let msg = 'Server error. Please try again later.';
                    if (xhr.responseJSON && xhr.responseJSON.data && xhr.responseJSON.data.message) {
                        msg = xhr.responseJSON.data.message;
                    }
                    $error.text(msg).slideDown(200);
                }
            });
        });

        $container.on('click', '.option-item', function (e) {
            // Do not trigger selection logic if clicking the active button itself
            if ($(e.target).hasClass('btn-select--active')) return;

            $container.find('.option-item').removeClass('is-selected');
            $container.find('.btn-select').removeClass('btn-select--active').text('Select');

            $(this).addClass('is-selected');
            $(this).find('.btn-select').addClass('btn-select--active').text('Continue');
        });

        $container.on('click', '.btn-select.btn-select--active', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const $thisBtn = $(this);
            if ($thisBtn.hasClass('is-loading')) return;

            const originalText = $thisBtn.text();
            $thisBtn.addClass('is-loading').text('Processing...');
            $thisBtn.prop('disabled', true);

            const $tourItem = $thisBtn.closest('.option-item');
            const optionId = $tourItem.data('key');
            const postId = $container.data('post-id');
            const date = $form.find('input[type="date"]').val();
            const adults = parseInt($form.find('.quantity-selector input').eq(0).val()) || 0;
            const children = parseInt($form.find('.quantity-selector input').eq(1).val()) || 0;

            $.ajax({
                url: php_data.ajax_url,
                type: 'POST',
                data: {
                    action: 'rc_ajax_process_booking',
                    nonce: php_data.tour_options_nonce,
                    post_id: postId,
                    option_id: optionId,
                    date: date,
                    adults: adults,
                    children: children
                },
                success: function (response) {
                    if (response.success && response.data.redirect_url) {
                        window.location.href = response.data.redirect_url;
                    } else {
                        $thisBtn.removeClass('is-loading').text(originalText).prop('disabled', false);
                        alert(response.data.message || 'An error occurred.');
                    }
                },
                error: function () {
                    $thisBtn.removeClass('is-loading').text(originalText).prop('disabled', false);
                    alert('Server error. Please try again later.');
                }
            });
        });
    };

    $(document).ready(function () {
        rcInitQuantitySelectors();
        rcInitAjaxTourOptions();
    });
})(jQuery);