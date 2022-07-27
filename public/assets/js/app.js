function activateDataAction() {
    $('span[data-action], a[data-action], div[data-action]').on('click', function () {
        var elem = $(this);
        var action = elem.attr('data-action');
        var action_name = elem.attr('data-action-name');
        var segment = elem.closest('div[data-action-element]');
        if (!segment.length) {
            segment = elem.closest('.action-segment');
        }
        var params = '_token=' + $('meta[name="csrf-token"]').attr('content');
        if (elem.attr('data-method')) params += '&_method=' + elem.attr('data-method');

        if (action) {
            segment.addClass('ui basic segment');
            segment.append("<div class=\"ui active inverted dimmer\"><div class=\"ui text loader\">Wait please</div></div>");
            $.post(action, params, function (result) {
                if (result) {
                    segment.find('.dimmer').remove();

                    if (result.success !== undefined) {
                        switch (action_name) {
                            case 'remove':
                                segment.remove();
                                break;
                        }
                    } else {
                        showErrorModalMessage(result.message);
                    }
                }
            }, "json");
        }
    });
}

/* Error Modal */
function showErrorModalMessage(message, text, icon) {
    if (message == undefined) {
        message = 'UNKNOWN ERROR';
    }
    if (icon == undefined) {
        icon = 'orange warning sign';
    }
    if (text == undefined) {
        text = 'ERROR';
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

/* Custom Popups Activation */
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

function urlConstructor() {
    var url = window.location.pathname;
    url = url + '?sort=' + $('input[name=sort]').val();

    var $category_input = $('select[name=category]');
    if ($category_input.length) {
        url = url + '&category=' + $category_input.val();
    }

    var $operationtype_input = $('select[name=operationtype]');
    if ($operationtype_input.length) {
        url = url + '&operationtype=' + $operationtype_input.val();
    }

    var $availability_input = $('input[name=availability]');
    if ($availability_input.length) {
        url = url + '&availability=' + $availability_input.val();
    }

    var $q_input = $('input[name=q]');
    if ($q_input.length && $q_input.val()) {
        url = url + '&q=' + $q_input.val();
    }

    return url;
}

$(document).ready(function () {

    $('#q_clean').click(function () {
        $('input[name=q]').val('');
    });

    $('.message .close').on('click', function () {
        $(this).closest('.message').transition('fade');
    });

    /* Checkboxes Activation */
    $('.ui.checkbox').length && $('.ui.checkbox').checkbox();

    /* Dropdown Activation */
    $('.ui.dropdown').length && $('.ui.dropdown').dropdown();

    activateDataAction();
    activateCustomPopup();

    $('select[name=category], input[name=sort], select[name=operationtype], input[name=availability]').change(function () {
        window.location.href = urlConstructor();
    });

    $('input[name=q]').keyup(function (event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            if ($(this).val()) {
                window.location.href = urlConstructor();
            }
        }
    });

    /* Gallery */
    var galleryElements = document.getElementsByClassName("gallery");
    for (var i = 0; i < galleryElements.length; i++) {
        lightGallery(galleryElements[i], {
            thumbnail: false,
            speed: 250,
            download: true,
            zoom: true
        });
    }

    /* lightbox */
    var lightboxImages = document.getElementsByClassName('lightbox');
    for (var i = 0; i < lightboxImages.length; i++) {
        lightGallery(lightboxImages[i], {
            selector: 'this',
            thumbnail: false,
            speed: 250,
            download: true,
            zoom: true
        });
    }

});
