<div class="ui hidden divider"></div>
<div class="field">
    <a id="save-button"
       data-content="{{ trans('app.save_tooltip') }}"
       data-variation="tiny"
       class="ui green button">{{ trans('app.do_save') }}</a>
    <a id="save-n-close-button" class="ui olive button">{{ trans('app.do_save_n_close') }}</a>
    <div class="ui message hide save-message"></div>
</div>

<script>
    function initSubmitButtons() {
        if (typeof jQuery !== 'undefined') {
            activateSubmitButton();
        } else {
            setTimeout(initSubmitButtons, 200);
        }
    }
    initSubmitButtons();

    function activateSubmitButton() {
        var send_func = function ($btn, do_redirect) {
            if (typeof do_redirect == "undefined") {
                do_redirect = 0;
            }
            var form = $btn.closest('form');
            form.append("<div class=\"ui active inverted dimmer\"><div class=\"ui loader\"></div></div>");

            var form_data = form.serialize();
            form_data += '&do_redirect=' + do_redirect;

            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form_data,
                dataType: "json",
                timeout: 10000,
                success: function (result) {
                    if (result.success == 'OK') {
                        if (result.redirect) {
                            window.location.href = result.redirect;
                        }
                        if(result.id) {
                            if(!form.find('input[name="id"]').length) {
                                $('<input>').attr({
                                    type: 'hidden',
                                    value: result.id,
                                    name: 'id'
                                }).appendTo('form');
                            }
                        }
                    }
                    if (result.message) {
                        form.find('.save-message').html(result.message).removeClass('hide');
                    }
                    form.find('.dimmer').remove();
                },
                error: function (request, status, err) {
                    if (status == "timeout") {
                        showErrorModalMessage("{{ trans('app.timeout_error_message') }}");
                    } else {
                        showErrorModalMessage("{{ trans('app.undefined_error_message') }}");
                    }
                    form.find('.dimmer').remove();
                }
            });
        }

        $('#save-button').click(function () {
            send_func($(this), 0);
        });

        $('#save-n-close-button').click(function () {
            send_func($(this), 1);
        });
    }
</script>