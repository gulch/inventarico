@include('partials.error-message')

<div class="field">
    {!! Form::label('description', trans('app.description')) !!}
    {!! Form::text('description',null, ['placeholder' => trans('app.enter_description_for_image')]) !!}
</div>

<div id="photo_segment" class="ui segment photos">
    <div id="ph_photo_upload" class="content pointer">
        <div class="center">
            <h3 class="ui icon header center aligned">
                <i class="icon circular emphasized photo"></i>
                {{ trans('app.drag_image_here_or_click') }}
            </h3>
        </div>
    </div>

    <input id="photo_input" type="file" class="hide" accept="image/*">

    <div id="image_preview" class="ui segment @if(!isset($photo)) hide @endif">
        @if(isset($photo))
            <img class="ui centered image" src="{{ config('app.photo_image_upload_path') . $photo->path }}">
        @else
            <img class="ui centered image">
        @endif
    </div>
</div>
<input type="hidden" name="path" @if(isset($photo)) value="{{ $photo->path }}" @endif>

{!! Form::submit(trans('app.do_save'), ['class' => 'ui submit primary button']) !!}
<a class="ui button" href="/photos">{{ trans('app.do_cancel') }}</a>

<script>
    $(document).ready(function () {
        var tests = {
            filereader: typeof FileReader != 'undefined',
            dnd: 'draggable' in document.createElement('span'),
            formdata: !!window.FormData
        };
        var acceptedTypes = {
            'image/png': true,
            'image/jpeg': true
        };
        var photo_segment = document.getElementById('ph_photo_upload');

        if (photo_segment !== undefined) {
            photo_segment.onclick = function () {
                $("#photo_input").trigger('click');
                return false;
            };

            photo_segment.ondrop = function (e) {
                e.preventDefault();
                e.stopPropagation();
                sendFiles(e.dataTransfer.files);
            };

            photo_segment.ondragover = function () {
                return false;
            };
            photo_segment.ondragend = function () {
                return false;
            };

            photo_segment.ondropover = function (e) {
                e.stopPropagation();
                e.preventDefault();
                e.dataTransfer.dropEffect = 'copy';
            }
        }

        $('#photo_input').bind('change', function () {
            sendFiles(this.files);
        });

        function sendFiles(files) {
            if (files.length > 0) {
                var formData = tests.formdata ? new FormData() : null;
                if (formData) {
                    formData.append('image', files[0]);
                    formData.append('setup', 'photo');
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                    var segment = $('#photo_segment');
                    segment.append("<div class=\"ui active inverted dimmer\"><div class=\"ui text loader\"></div></div>");

                    $.ajax({
                        url: "/photos/upload",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function (result) {
                            segment.find('.dimmer').remove();

                            if (result.success !== undefined) {
                                $('input[name="path"]').val(result.path);
                                $('#image_preview').show().find('.image').attr('src', result.filelink);
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
    });
</script>