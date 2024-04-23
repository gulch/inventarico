<div class="ui center aligned segment">
    <div id="image_segment_{{ $key }}" class="ui segment photos">
        <div id="image_upload_module_{{ $key }}" class="pointer">
            <div class="center">
                <h5 class="ui icon header center aligned">
                    <i class="icon circular emphasized photo"></i>
                    {{ trans('app.drag_image_here_or_click') }}
                </h5>
            </div>
        </div>
        <div id="image_preview_{{ $key }}"
             class="ui segment @if(!isset($image)) hide @endif preview-uploaded-image"
        >
            <div class="ui image dimmable">
                <div class="ui dimmer">
                    <div class="content">
                        <div class="bottom">
                            <div class="ui mini red button">
                                <i class="trash icon"></i>{{ trans('app.do_remove') }}
                            </div>
                        </div>
                    </div>
                </div>
                @if(isset($image))
                    <img class="ui centered image" src="{{ $path . $image->path }}">
                @else
                    <img class="ui centered image">
                @endif
            </div>
        </div>
        <input id="image_input_{{ $key }}" type="file" class="hide" accept="image/*">
        <input id="image_fieldname_{{ $key }}" type="hidden" name="{{ $field_name }}" @if($id) value="{{ $id }}" @endif>
    </div>
    <div class="ui horizontal divider">
        {{ trans('app.or') }}
    </div>
    <div id="image_choose_btn_{{ $key }}" class="ui labeled icon button">
        {{ trans('app.choose_from_exists') }}
        <i class="counterclockwise rotated sign out icon"></i>
    </div>
</div>

{{-- Image choose modal form --}}
<div id="image_choose_modal_{{ $key }}" class="ui modal">
    <i class="close icon"></i>
    <div class="header">
        {{ trans('app.do_choose_image') }}
    </div>
    <div class="content">

    </div>
</div>

<script>
    function createAndShowImageModal(key, type) {
        $(document.body).append(
                '<div id="image_choose_modal" class="ui modal">' +
                '<input name="image_chooser_key" type="hidden" value="' + key + '">' +
                '<i class="close icon"></i>' +
                '<div class="content">' +
                '<div class=\"ui active inverted dimmer\"><div class=\"ui loader\"></div></div>' +
                '</div>' +
                '</div>');

        $modal = $('#image_choose_modal');
        $modal.modal({
            allowMultiple: false,
            blurring: false,
            closable: true,
            duration: 100,
            onHidden: function () {
                $('#image_choose_modal').html('').remove();
            }
        }).modal('show');

        var token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type: "POST",
            url: '/photos/all/list',
            data: {_token: token},
            dataType: "json",
            timeout: 10000,
            success: function (result) {
                if (result.success == 1) {
                    if (result.content) {
                        $modal.find('.content').html(result.content);
                        $modal.modal('refresh');

                        $('#image_choose_modal [data-image-id]').click(function () {
                            var elem = $(this);
                            var image_id = elem.attr('data-image-id');
                            if (image_id !== undefined) {
                                var key = $('#image_choose_modal input[name=image_chooser_key]').val();
                                $('#image_fieldname_' + key).val(image_id);
                                var image_preview = $('#image_preview_' + key);
                                image_preview.find('img').attr('src', elem.find('img').attr('src'));
                                image_preview.show();
                                $('#image_choose_modal').modal('hide');
                                $('#image_choose_modal').html('').remove();
                            }
                        });

                    }
                } else {
                    $modal.find('.dimmer').remove();
                }
            },
            error: function (request, status, err) {
                if (status == "timeout") {
                    showErrorModalMessage("{{ trans('app.timeout_error_message') }}");
                } else {
                    showErrorModalMessage("{{ trans('app.undefined_error_message') }}");
                }
                $modal.find('.dimmer').remove();
            }
        });
    }

    $(document).ready(function () {
        var key = '{{ $key }}';

        (function () {
            var tests = {
                filereader: typeof FileReader != 'undefined',
                dnd: 'draggable' in document.createElement('span'),
                formdata: !!window.FormData
            };
            var acceptedTypes = {
                'image/png': true,
                'image/jpeg': true
            }

            var image_module = document.getElementById('image_upload_module_' + key);

            if (image_module !== undefined) {
                image_module.onclick = function () {
                    $("#image_input_" + key).trigger('click');
                    return false;
                }

                image_module.ondrop = function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    sendFiles(e.dataTransfer.files);
                }

                image_module.ondragover = function () {
                    return false;
                };
                image_module.ondragend = function () {
                    return false;
                };

                image_module.ondropover = function (e) {
                    e.stopPropagation();
                    e.preventDefault();
                    e.dataTransfer.dropEffect = 'copy';
                }
            }

            $('#image_input_' + key).bind('change', function () {
                sendFiles(this.files);
            });

            function sendFiles(files) {
                if (files.length > 0) {
                    var formData = tests.formdata ? new FormData() : null;
                    if (formData) {
                        formData.append('image', files[0]);
                        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                        formData.append('setup', '{{ $setup }}');

                        var segment = $('#image_segment_' + key);
                        segment.append("<div class=\"ui active inverted loading dimmer\"><div class=\"ui text loader\">{{ trans('app.wait') }}</div></div>");

                        $.ajax({
                            url: "/photos/upload/getid",
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            dataType: 'json',
                            success: function (result) {
                                segment.find('.loading.dimmer').remove();

                                if (result.success !== undefined) {
                                    $('input[name="{{ $field_name }}"]').val(result.id);
                                    $('#image_preview_' + key).removeClass('hide').show().find('.image').attr('src', result.filelink);
                                }
                                else {
                                    if (result.message) {
                                        showErrorModalMessage(result.message);
                                    }
                                }
                            }
                        });
                    }

                }
            }
        })();

        $('#image_preview_' + key).find('.image').dimmer({on: 'hover'});

        $('#image_preview_' + key + ' .dimmer .button').click(function () {
            $('input[name="{{ $field_name }}"]').val('');
            $('#image_preview_' + key).hide().find('.image').removeAttr('src');
        });

        $('#image_choose_btn_' + key).click(function () {
            var $modal = $('#image_choose_modal');
            if ($modal.length > 0) {
                $modal.modal('show');
                $modal.find('input[name=image_chooser_key]').val(key);
            } else {
                createAndShowImageModal(key, '{{ $setup }}');
            }
        });
    });
</script>