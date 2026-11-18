(function ($) {
    "use strict";

    const ERROR_ID = 'rc-vn-phone-blocked';
    const ERROR_MESSAGE =
        'Vietnamese phone numbers are currently not accepted. Please enter an international phone number.';

    // Scoped strictly to Contact Me (1) and Book Car (2)
    const TARGET_FORMS = {
        1: {
            fieldId: 17,
            fieldKey: 'your_phone_whatsapp_1794240866187',
        },
        2: {
            fieldId: 14,
            fieldKey: 'whats_app_1794103710633',
        },
    };

    const normalizePhoneNumber = (phone) => {
        if (phone === null || phone === undefined) {
            return '';
        }

        const trimmed = String(phone).trim();
        if (!trimmed) {
            return '';
        }

        const normalized = trimmed.replace(/[\s\-\(\)\.]+/g, '');
        if (!/^\+?\d+$/.test(normalized)) {
            return '';
        }

        return normalized;
    };

    const isVietnamPhoneNumber = (phone) => {
        const normalized = normalizePhoneNumber(phone);
        if (!normalized) {
            return false;
        }

        // International: +84xxxxxxxxx, 0084xxxxxxxxx, or 84xxxxxxxxx
        if (/^(?:\+84|0084)0?[35789]\d{8}$/.test(normalized)) {
            return true;
        }

        if (/^84[35789]\d{8}$/.test(normalized)) {
            return true;
        }

        // Domestic: 03, 05, 07, 08, 09 + 8 digits
        if (/^0[35789]\d{8}$/.test(normalized)) {
            return true;
        }

        return false;
    };

    // Expose shared validator for Checkout and other theme scripts
    window.rcPhoneValidation = {
        normalizePhoneNumber,
        isVietnamPhoneNumber,
        vnBlockedMessage: ERROR_MESSAGE,
    };

    const parseNumericId = (rawId) => {
        if (rawId === null || rawId === undefined) {
            return 0;
        }
        const parsed = parseInt(String(rawId).split('_')[0], 10);
        return Number.isNaN(parsed) ? 0 : parsed;
    };

    const isTargetPhoneField = (fieldModel) => {
        if (!fieldModel || typeof fieldModel.get !== 'function') {
            return false;
        }

        const formId = parseNumericId(fieldModel.get('formID'));
        const config = TARGET_FORMS[formId];
        if (!config) {
            return false;
        }

        const fieldKey = String(fieldModel.get('key') || '');
        const fieldId = parseNumericId(fieldModel.get('id'));

        return (config.fieldKey && fieldKey === config.fieldKey) || (config.fieldId && fieldId === config.fieldId);
    };

    const validatePhoneFieldModel = (fieldModel, removeOnly = false) => {
        if (!isTargetPhoneField(fieldModel) || typeof window.nfRadio === 'undefined') {
            return;
        }

        const fieldId = fieldModel.get('id');
        const value = String(fieldModel.get('value') || '').trim();
        const fieldsChannel = window.nfRadio.channel('fields');

        if (!value || !isVietnamPhoneNumber(value)) {
            fieldsChannel.request('remove:error', fieldId, ERROR_ID);
            return;
        }

        if (!removeOnly) {
            fieldsChannel.request('add:error', fieldId, ERROR_ID, ERROR_MESSAGE);
        }
    };

    let listenersBound = false;
    const bindNinjaFormsValidation = () => {
        if (listenersBound || typeof window.nfRadio === 'undefined') {
            return;
        }
        listenersBound = true;

        const fieldsChannel = window.nfRadio.channel('fields');
        const submitChannel = window.nfRadio.channel('submit');

        fieldsChannel.on('blur:field', function (_el, fieldModel) {
            validatePhoneFieldModel(fieldModel, false);
        });

        fieldsChannel.on('change:modelValue', function (fieldModel) {
            validatePhoneFieldModel(fieldModel, true);
        });

        submitChannel.on('validate:field', function (fieldModel) {
            validatePhoneFieldModel(fieldModel, false);
        });
    };

    const bindCheckoutValidation = () => {
        const formEl = document.getElementById('rc-checkout-form');
        if (!formEl || formEl.dataset.rcVnPhoneBound === '1') {
            return;
        }
        formEl.dataset.rcVnPhoneBound = '1';

        const $form = $(formEl);
        const $phoneInput = $form.find('input[name="customer_phone"]');
        if (!$phoneInput.length) {
            return;
        }

        const showPhoneError = () => {
            const $fb = $phoneInput.siblings('.invalid-feedback');
            $fb.text(ERROR_MESSAGE).slideDown(200);
        };

        $phoneInput.on('blur.rcVnPhone', function () {
            const val = String($(this).val() || '').trim();
            if (val && isVietnamPhoneNumber(val)) {
                showPhoneError();
            }
        });

        // Capture-phase submit guard runs before compiled main.bundle.js handlers
        formEl.addEventListener(
            'submit',
            function (e) {
                const val = String($phoneInput.val() || '').trim();
                if (val && isVietnamPhoneNumber(val)) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    showPhoneError();
                    $('html, body').animate(
                        { scrollTop: $phoneInput.offset().top - 140 },
                        400
                    );
                    $phoneInput.trigger('focus');
                }
            },
            true
        );
    };

    $(document).on('nfFormReady', function () {
        bindNinjaFormsValidation();
    });

    $(document).ready(function () {
        bindNinjaFormsValidation();
        bindCheckoutValidation();
    });
})(jQuery);

