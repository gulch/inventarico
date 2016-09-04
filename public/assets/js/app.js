function activeFormSubmit() {
    $('.button.form-submit-btn').click(function () {
        var form = $(this).closest('form');
        form.append("<div class=\"ui active inverted dimmer\"><div class=\"ui loader\"></div></div>");
        $.post(form.attr('action'), form.serialize(), function (result) {
            if (result.success == 1) {
                form[0].outerHTML = '<div class="ui message">' +
                    '<h2 class="ui icon green header">' +
                    '<i class="fi-check icon"></i>' +
                    '<div class="content">Успешно!' +
                    '<div class="sub header">' + result.message + '</div>' +
                    '</div>' +
                    '</h2>' +
                    '</div>';
            } else {
                form.find('.message').html(result.message).removeClass('hide');
            }

            form.find('.dimmer').remove();
        }, "json");
    });
}

function activateDataAction()
{
    $('span[data-action], a[data-action], div[data-action]').on('click', function () {
        var elem = $(this);
        var action = elem.attr('data-action');
        var action_name = elem.attr('data-action-name');
        var segment = elem.closest('div[data-action-element]');
        if (!segment.length) {
            segment = elem.closest('.segment');
        }
        var params = '_token=' + $('meta[name="csrf-token"]').attr('content');
        if (elem.attr('data-method')) params += '&_method=' + elem.attr('data-method');

        if (action) {
            segment.addClass('ui basic segment');
            segment.append("<div class=\"ui active inverted dimmer\"><div class=\"ui text loader\">Секундочку</div></div>");
            $.post(action, params, function (result) {
                if (result) {
                    segment.find('.dimmer').remove();

                    if (result.success !== undefined) {
                        switch (action_name) {
                            case 'remove':
                                segment.remove();
                                break;
                            case 'publish':
                                elem[0].outerHTML = '<i class="check icon"></i>Опубликовано';
                                break;
                            case 'unpublish':
                                elem[0].outerHTML = '<i class="check icon"></i>Снято с публикации';
                                break;
                        }
                    }
                    else {
                        showErrorModalMessage(result.message);
                    }
                }
            }, "json");
        }
    });
}

/* Модальный диалог ошибки */
function showErrorModalMessage(message, text, icon) {
    if (message == undefined) {
        message = 'Неизвестная ошибка';
    }
    if (icon == undefined) {
        icon = 'orange warning sign';
    }
    if (text == undefined) {
        text = 'Ошибка';
    }
    var key = Math.floor(Math.random() * 1000);
    $(document.body).append(
        '<div id="errormodal_' + key + '" class="ui small modal">' +
        '<i class="remove close icon"></i>' +
        '<div class="header"><i class="' + icon + ' circle middle big icon"></i>' + text + '</div>' +
        '<div class="content"><p>' + message + '</p></div>' +
        '<div class="actions">' +
        '<div class="ui large basic button">OK</div>' +
        '</div>' +
        '</div>');
    $('#errormodal_' + key).modal({allowMultiple: false}).modal('show');
    $('#errormodal_' + key + ' .button').click(function () {
        $(this).closest('.modal').modal('hide').remove();
    });
}

/* Активация кастомных попапов */
function activateCustomPopup() {
    $('a[data-popup="1"]').each(function () {
        var $popup = $(this);
        $popup.popup({
            popup: $popup.closest('div').find('.custom.popup'),
            on: 'click'
        });

        $popup.closest('div').find('.custom.popup .button').click(function () {
            $popup.popup('hide');
        });
    });
}

$(document).ready(function () {

    $('.message .close').on('click', function () {
        $(this).closest('.message').transition('fade');
    });

    /* Активация чекбоксов */
    $('.ui.checkbox').length && $('.ui.checkbox').checkbox();

    activeFormSubmit();
    activateDataAction();
    activateCustomPopup();
});
