function getCookie(name) {
    const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    return match ? decodeURIComponent(match[2]) : "";
}

function getUtmParam(name) {
    var val = getCookie(name);
    if (val) return val;
    val = getCookie('_' + name);
    if (val) return val;
    try {
        val = localStorage.getItem(name);
        if (val) return val;
        val = sessionStorage.getItem(name);
        if (val) return val;
    } catch (e) {}
    var params = new URLSearchParams(window.location.search);
    return params.get(name) || "";
}

document.addEventListener("DOMContentLoaded", function () {
    var utms = ["utm_source", "utm_medium", "utm_campaign", "utm_term", "utm_content"];
    var utmFromJson = {};
    try {
        var stored = localStorage.getItem('utm_params') || sessionStorage.getItem('utm_params') || '{}';
        utmFromJson = JSON.parse(stored) || {};
    } catch (e) {}
    utms.forEach(function (utm) {
        var val = getUtmParam(utm) || (utmFromJson[utm] || '');
        if (val) {
            var fields = document.querySelectorAll('input[name="' + utm + '"]');
            for (var i = 0; i < fields.length; i++) fields[i].value = val;
        }
    });
});

$(document).ready(function () {

    $(document).on('submit', '.js_one_send', function (e) {
        let _form = $(this);
        _form.find("input[type=submit]").attr('disabled', 'disabled');
        _form.find("button[type=submit]").attr('disabled', 'disabled');
    });

    $(document).on('click', ".calc__interest", function (e) {
        $('.popup-interest').addClass('show');
    });

    $(document).on('click', 'a[data-current_tab]', function () {
        var tabValue     = $(this).data('current_tab');
        var inputElement = $(this).parents('.big-form__variant').find('input:hidden.current_tab');
        inputElement.attr('name', inputElement.attr('name')
                                              .replace(/\[current_tab]\[(\w+)\]/, '[current_tab][' + tabValue + ']'));
    });

    $(document).on('click', 'input.js_monthly_net_income', function () {
        var tabValue     = $(this).parents('.big-form__holder').find('a.active[data-current_tab]').data('current_tab');
        var inputElement = $(this).parents('.big-form__holder').find('input:hidden.current_tab');
        inputElement.attr('name', inputElement.attr('name')
                                              .replace(/\[current_tab]\[(\w+)\]/, '[current_tab][' + tabValue + ']'));
    });

    /*возможно однажды к этому вернемся*/
    /*
     * deprecated
     $(document).on('click', '.js_calc_apply', function () {
     let $this                = $(this);
     let parent               = $this.parents('.js_tab');
     let category             = parent.data('category');
     let tab                  = parent.data('tab_name');
     let currency             = parent.find('.js_currency').find('button.active').data('cur_name');
     let currency_rate        = parent.find('.js_currency').find('button.active').data('cur');
     let loan_amount          = parent.find('.js_loan_amount').text();
     let total_payment_amount = parent.find('.js_total_payment_amount').text();
     let interest_rate        = parent.find('.js_interest_rate').text();
     let rpsn                 = parent.find('.js_rpsn').text();
     let insurance_payment    = parent.find('.js_insurance_payment').text();
     let monthly_payment      = parent.find('.js_monthly_payment').text();
     let duration             = parent.find('.js_duration').val();

     $.ajax({
     url:        front.ajax_url,
     type:       'POST',
     dataType:   'json',
     cache:      false,
     data:       {
     action:                    'save_mini_calc_form_data',
     nonce_code:                front.nonce,
     calc_category:             category,
     calc_tab:                  tab,
     calc_currency:             currency,
     calc_currency_rate:        currency_rate,
     calc_loan_amount:          loan_amount,
     calc_total_payment_amount: total_payment_amount,
     calc_interest_rate:        interest_rate,
     calc_rpsn:                 rpsn,
     calc_insurance_payment:    insurance_payment,
     calc_monthly_payment:      monthly_payment,
     calc_duration:             duration
     },
     beforeSend: function () { // Не даем несколько раз нажать кнопку
     $this.attr('disabled', 'disabled');
     },
     success:    function (result) {
     if (result.success) {
     window.location.href = result.url;

     } else {
     console.log('error');
     console.log(result);
     }
     // $this.removeAttr('disabled');
     // $this.removeAttr('disabled');
     }
     });
     })
     */

    $(document).on('click', '.js_go_aio', function () {
        let $this                = $(this);
        let parent               = $this.parents('.calc__section');
        let category             = parent.parents('.js_tab').data('category') ?? $this.data('category');
        let tab                  = parent.parents('.js_tab').data('tab_name') ?? $this.data('tab_name');
        let currency             = parent.find('.js_currency').find('button.active').data('cur_name') ?? 'czk';
        let currency_rate        = parent.find('.js_currency').find('button.active').data('cur') ?? 1;
        let loan_amount          = parent.find('.js_loan_amount').text();
        let total_payment_amount = parent.find('.js_total_payment_amount').text();
        let interest_rate        = parent.find('.js_interest_rate').text();
        let rpsn                 = parent.find('.js_rpsn').text();
        let insurance_payment    = parent.find('.js_insurance_payment').text();
        let monthly_payment      = parent.find('.js_monthly_payment').text();
        let duration             = parent.find('.js_duration').val();

        let min_loan_amount = parseInt(parent.find('input.property-sum').attr('min'));
        let num_loan_amount = Number(loan_amount.replace(/[^\d.-]/g, ""));

        if (min_loan_amount > 0 && min_loan_amount > num_loan_amount) {
            return false;
        }

        var utmData = {};
        var utmKeys = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];
        var utmFromJson = {};
        try {
            var stored = localStorage.getItem('utm_params') || sessionStorage.getItem('utm_params') || '{}';
            utmFromJson = JSON.parse(stored) || {};
        } catch (e) {}
        utmKeys.forEach(function (utm) {
            var val = getUtmParam(utm) || (utmFromJson[utm] || '');
            if (val) utmData['calc_' + utm] = val;
        });
        if (typeof console !== 'undefined' && console.log) {
            console.log('Apply UTM captured:', Object.keys(utmData).length ? utmData : 'none found - check cookies/localStorage');
        }

        $.ajax({
            url:        front.ajax_url,
            type:       'POST',
            dataType:   'json',
            cache:      false,
            data:       Object.assign({
                action:                    'go_aio_from_calc',
                nonce_code:                front.nonce,
                calc_category:             category,
                calc_tab:                  tab,
                calc_currency:             currency,
                calc_currency_rate:        currency_rate,
                calc_loan_amount:          loan_amount,
                calc_total_payment_amount: total_payment_amount,
                calc_interest_rate:        interest_rate,
                calc_rpsn:                 rpsn,
                calc_insurance_payment:    insurance_payment,
                calc_monthly_payment:      monthly_payment,
                calc_duration:             duration
            }, utmData),
            beforeSend: function () { // Не даем несколько раз нажать кнопку
                $this.attr('disabled', 'disabled');
            },
            success:    function (result) {
                if (result.success) {
                    window.location.href = result.url;

                } else {
                    console.log('error');
                    console.log(result);
                }

            }
        });
    })


    $(document).on('submit', '#login_form', function (e) {
        e.preventDefault();

        let _form = $(this);
        var data  = _form.serialize();
        $.ajax({
            url:      front.ajax_url,
            type:     'POST',
            dataType: 'json',
            cache:    false,
            data:     data,

            beforeSend: function () { // Не даем несколько раз нажать кнопку
                _form.find("input[type=submit]").attr('disabled', 'disabled');
                _form.find("button[type=submit]").attr('disabled', 'disabled');
            },
            success:    function (result) {
                if (result.success) {
                    $('#js_login_wrap').html(result.html);
                    $('.big-form__cols input').on('keyup', jmp);
                } else {
                    $('.js_info').text(result.message).show();
                    // console.log('error');
                    // console.log(result);
                    _form.find("input[type=submit]").removeAttr('disabled');
                    _form.find("button[type=submit]").removeAttr('disabled');
                }
            }
        });
        return false;
    })

    $(document).on('submit', '#login_code_form', function (e) {
        e.preventDefault();

        let _form = $(this);
        var data  = _form.serialize();

        $.ajax({
            url:        front.ajax_url,
            type:       'POST',
            dataType:   'json',
            cache:      false,
            data:       data,
            beforeSend: function () { // Не даем несколько раз нажать кнопку
                _form.find("input[type=submit]").attr('disabled', 'disabled');
                _form.find("button[type=submit]").attr('disabled', 'disabled');
            },
            success:    function (result) {
                if (result.success) {
                    window.location.href = result.url;
                } else {
                    $('.js_info').text(result.message).show();
                    // console.log('error');
                    // console.log(result);
                    _form.find("input[type=submit]").removeAttr('disabled');
                    _form.find("button[type=submit]").removeAttr('disabled');
                }
            }
        });
        return false;
    })

    function jmp(e) {
        var max = ~~e.target.getAttribute('maxlength');
        if (max && e.target.value.length >= max) {
            var nextInput = e.target.nextElementSibling;
            while (nextInput && !/text/.test(nextInput.type)) {
                nextInput = nextInput.nextElementSibling;
            }
            if (nextInput && /text/.test(nextInput.type)) {
                nextInput.focus();
            }
        }
    }
});

document.addEventListener("DOMContentLoaded", function () {
    var form = document.querySelector("#watchdog-form");
    if (form) {
        var submitButton    = form.querySelector("input#watchdog-submit");
        var acceptanceInput = form.querySelector("input#watchdog-acceptance");
        if (acceptanceInput) {
            toggleSubmitButton();
            acceptanceInput.addEventListener("change", function () {
                toggleSubmitButton();
            });
        }

        function toggleSubmitButton() {
            if (acceptanceInput && acceptanceInput.checked) {
                submitButton.removeAttribute("disabled");
            } else {
                submitButton.setAttribute("disabled", "disabled");
            }
        }

        form.addEventListener('submit', function () {
            submitButton.disabled = true; // блокируем кнопку
        });
    }
});