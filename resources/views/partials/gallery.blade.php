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
        <div id="image_preview_{{ $key }}" class="ui four column grid thumbnails @if(!$photos) hide @endif">
            <div class="row">
                @if(!is_null($photos))
                    @foreach($photos as $photo)
                        <div class="one column attachment-thumbnail" data-id="{{ $photo->id }}">
                            <div class="ui segment image dimmable">
                                <div class="ui dimmer">
                                    <div class="content">
                                        <div class="bottom">
                                            <div class="ui mini red button">
                                                <i class="trash icon"></i>Удалить
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <img src="{{ config('app.thumb_image_upload_path') . $photo->path }}">
                                <input type="hidden" name="{{ $field_name }}[]" value="{{ $photo->id }}">
                            </div>
                        </div>
                    @endforeach
                    <script>
                        $(document).ready(function () {
                            var img_prev = $('#image_preview_{{ $key }}');
                            img_prev.find('.image').dimmer({on: 'hover'});
                            img_prev.find('.button').click(function (e) {
                                removePhotoClick(e, this)
                            });
                        });
                    </script>
                @endif
            </div>
        </div>
        <input id="image_input_{{ $key }}" type="file" multiple="multiple" class="hide" accept="image/*">
    </div>
    <div class="ui horizontal divider">
        {{ trans('app.or') }}
    </div>
    <div id="image_choose_btn_{{ $key }}" class="ui labeled icon button">
        {{ trans('app.choose_from_exists') }}
        <i class="counterclockwise rotated sign out icon"></i>
    </div>
</div>
<script>
    var key = '{{ $key }}';
    var temp_key = 0;

    $(document).ready(function () {
        (function () {
            var tests = {
                filereader: typeof FileReader != 'undefined',
                dnd: 'draggable' in document.createElement('span'),
                formdata: !!window.FormData
            };
            var acceptedTypes = {
                'image/png': true,
                'image/jpeg': true,
                'image/gif': true
            };

            var image_module = document.getElementById('image_upload_module_' + key);

            if (image_module !== undefined) {
                image_module.onclick = function () {
                    $("#image_input_" + key).trigger('click');
                    return false;
                };

                image_module.ondrop = function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    sendFiles(e.dataTransfer.files);
                };

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
                var len = files.length;
                if (len > 0) {
                    for (var i = 0; i < len; i++) {
                        var formData = tests.formdata ? new FormData() : null;
                        if (formData) {
                            var local_key = ++temp_key;
                            var image_preview = $('#image_preview_' + key);
                            image_preview.find('.row').append('<div class="one column attachment-thumbnail" data-id="" data-key="' + local_key + '"><div class="ui image segment"><img width="175" src="{{ config('app.assets_img_path') }}/placeholder-white-175x130.svg"><div class=\"ui active inverted dimmer\"><div class=\"ui loader\"></div></div></div></div>');
                            image_preview.removeClass('hide').show();
                            formData.append('image', files[i]);
                            formData.append('key', local_key);
                            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                            formData.append('setup', '{{ $setup }}');

                            $.ajax({
                                url: "/photos/upload/getid",
                                type: "POST",
                                data: formData,
                                processData: false,
                                contentType: false,
                                dataType: 'json',
                                success: function (result) {
                                    if (result.success !== undefined) {
                                        var thumb = $(image_preview).find('div[data-key="' + result.key + '"]');
                                        if (thumb.length > 0) {
                                            thumb.find('.image').html('<div class="ui dimmer"><div class="content"><div class="bottom"><div class="ui mini red button"><i class="trash icon"></i>Удалить</div></div></div></div><img src="' + result.filelink + '"><input type="hidden" name="{{ $field_name }}[]" value="">').addClass('dimmable').dimmer({on: 'hover'});
                                            thumb.find('.button').click(function (e) {
                                                removePhotoClick(e, this)
                                            });
                                            if (result.id) {
                                                thumb.find('input[type="hidden"]').val(result.id);
                                                thumb.attr('data-id', result.id);
                                            }
                                        }
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
            }
        })();

        $('#image_choose_btn_' + key).click(function () {
            var $modal = $('#image_choose_modal_' + key);
            if ($modal.length > 0) {
                $('#image_choose_modal_' + key).modal('show');
            } else {
                createAndShowGalleryImageModal(key, '{{ $setup }}');
            }
        });

    });

    function createAndShowGalleryImageModal(key, type) {
        $(document.body).append(
                '<div id="image_choose_modal_' + key + '" class="ui modal">' +
                '<i class="close icon"></i>' +
                '<div class="content">' +
                '<div class=\"ui active inverted dimmer\"><div class=\"ui loader\"></div></div>' +
                '</div>' +
                '</div>');

        $modal = $('#image_choose_modal_' + key);
        $modal.modal({
            allowMultiple: false,
            blurring: false,
            closable: true,
            duration: 100,
            onHidden: function () {
                $('#image_choose_modal_' + key).html('').remove();
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

                        $('#image_choose_modal_' + key + ' [data-image-id]').click(function () {
                            var elem = $(this);
                            var image_id = elem.attr('data-image-id');
                            if (image_id !== undefined) {
                                var image_preview = $('#image_preview_' + key);
                                image_preview.removeClass('hide').show();
                                image_preview.find('.row').append('<div class="one column attachment-thumbnail" data-id="" data-key="' + temp_key + '"><div class="ui image segment"><img src="{{ config('app.assets_img_path') }}/placeholder-white-175x130.svg"></div></div>');
                                var local_temp_key = temp_key;
                                temp_key++;
                                var thumb = $(image_preview).find('div[data-key="' + local_temp_key + '"]');
                                thumb.find('.image').html('<div class="ui dimmer"><div class="content"><div class="bottom"><div class="ui mini red button"><i class="trash icon"></i>Удалить</div></div></div></div><img src="' + elem.find('img').attr('src') + '"><input type="hidden" name="{{ $field_name }}[]" value="">').addClass('dimmable').dimmer({on: 'hover'});
                                thumb.find('.button').click(function (e) {
                                    removePhotoClick(e, this)
                                });
                                thumb.find('input[type="hidden"]').val(image_id);
                                thumb.attr('data-id', image_id);

                                $('#image_choose_modal_' + key).modal('hide');
                                $('#image_choose_modal_' + key).html('').remove();
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

    function removePhotoClick(evt, elem) {
        var attach = $(elem).closest('.attachment-thumbnail');
        if (attach.length > 0) {
            attach.remove();
        }
        evt.stopPropagation();
    }
</script>