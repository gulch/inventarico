{{-- Font Awesome CSS --}}
<link href="/assets/vendor/font-awesome/4.6.3/css/fa.css" rel="stylesheet" type="text/css">

{{-- Froala CSS --}}
<link href="/assets/vendor/froala/2.3.4/css/froala_editor.css" rel="stylesheet" type="text/css">
<link href="/assets/vendor/froala/2.3.4/css/froala_style.css" rel="stylesheet" type="text/css">

{{-- Froala Plugins CSS --}}
<link href="/assets/vendor/froala/2.3.4/css/plugins/char_counter.css" rel="stylesheet" type="text/css">
<link href="/assets/vendor/froala/2.3.4/css/plugins/code_view.css" rel="stylesheet" type="text/css">
<link href="/assets/vendor/froala/2.3.4/css/plugins/colors.css" rel="stylesheet" type="text/css">
<link href="/assets/vendor/froala/2.3.4/css/plugins/emoticons.css" rel="stylesheet" type="text/css">
<link href="/assets/vendor/froala/2.3.4/css/plugins/table.css" rel="stylesheet" type="text/css">
<link href="/assets/vendor/froala/2.3.4/css/plugins/video.css" rel="stylesheet" type="text/css">
<link href="/assets/vendor/froala/2.3.4/css/plugins/image.css" rel="stylesheet" type="text/css">

{{-- Include Code Mirror CSS --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.css"
      rel="stylesheet"
      type="text/css"
>

{{-- Froala JS --}}
<script src="/assets/vendor/froala/2.3.4/js/froala_editor.min.js"></script>

{{-- Include Code Mirror JS --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>


{{-- Froala Plugins JS --}}
<script src="/assets/vendor/froala/2.3.4/js/plugins/char_counter.min.js"></script>
<script src="/assets/vendor/froala/2.3.4/js/plugins/code_view.min.js"></script>
<script src="/assets/vendor/froala/2.3.4/js/plugins/code_beautifier.min.js"></script>
<script src="/assets/vendor/froala/2.3.4/js/plugins/colors.min.js"></script>
<script src="/assets/vendor/froala/2.3.4/js/plugins/emoticons.min.js"></script>
<script src="/assets/vendor/froala/2.3.4/js/plugins/table.min.js"></script>
<script src="/assets/vendor/froala/2.3.4/js/plugins/video.min.js"></script>
<script src="/assets/vendor/froala/2.3.4/js/plugins/image.min.js"></script>
<script src="/assets/vendor/froala/2.3.4/js/plugins/align.min.js"></script>
<script src="/assets/vendor/froala/2.3.4/js/plugins/font_size.min.js"></script>
<script src="/assets/vendor/froala/2.3.4/js/plugins/link.min.js"></script>
<script src="/assets/vendor/froala/2.3.4/js/plugins/lists.min.js"></script>
<script src="/assets/vendor/froala/2.3.4/js/plugins/quote.min.js"></script>
<script src="/assets/vendor/froala/2.3.4/js/plugins/paragraph_format.min.js"></script>
<script src="/assets/vendor/froala/2.3.4/js/plugins/paragraph_style.min.js"></script>

{{-- Russian Language --}}
<script src="/assets/vendor/froala/2.3.4/js/languages/ru.js"></script>

<script>
    $(document).ready(function () {
        $('.wysiwyg-editor').froalaEditor({
            language: 'ru',
            imageUploadURL: '/{{ config('app.admin_segment_name') }}/image/upload',

            imageUploadParams: {
                setup: 'editor'
            }
        });

        $('.fr-box a:contains("Unlicensed")').parent().remove();
    });
</script>
