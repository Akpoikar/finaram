(function ($) {

    'use strict';

    if (typeof calculator === 'undefined' || calculator === null) {
        return;
    }

    $(function () {
        postboxes.add_postbox_toggles(calculator.screenId);
    });


    $(document).on('click', 'button[data-verdict]', function () {
        var verdict  = $(this).data('verdict');
        var fieldset = $(this).parents('fieldset');
        var calc_id  = $('[name="post"]').val();
        var nonce    = fieldset.data('nonce');
        $.ajax({
            url:      ajaxurl,
            type:     'POST',
            dataType: 'json',
            cache:    false,
            data:     {
                action:     'step_one_verdict',
                verdict:    verdict,
                nonce_code: nonce,
                calc_id:    calc_id,
            },

            beforeSend: function () { // Не даем несколько раз нажать кнопку
                fieldset.attr('disabled', 'disabled');
            },
            success:    function (result) {
                if (result.success) {
                    window.location.reload();
                } else {
                    $('#verdict_wrap').text(result.message);
                    console.log('error');
                    console.log(result);
                    $(this).parents('fieldset').removeAttr('disabled');
                }
            }
        });
    });

    $(document).on('click', 'button#js_choice_broker', function () {
        var button  = $(this);
        var broker  = $('select[name="broker_email"]').val();
        if (broker.trim() == '' ) {
            return false;
        }
        var calc_id = $('[name="post"]').val();
        var nonce   = button.data('nonce');
        $.ajax({
            url:      ajaxurl,
            type:     'POST',
            dataType: 'json',
            cache:    false,
            data:     {
                action:     'choice_broker',
                broker:     broker,
                nonce_code: nonce,
                calc_id:    calc_id,
            },

            beforeSend: function () { // Не даем несколько раз нажать кнопку
                button.attr('disabled', 'disabled');
            },
            success:    function (result) {
                if (result.success) {
                    window.location.reload();
                } else {
                    $('#broker_wrap').text(result.message);
                    console.log('error');
                    console.log(result);
                    button.removeAttr('disabled');
                }
            }
        });
    });

})(jQuery);
