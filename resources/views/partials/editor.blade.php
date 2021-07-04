{{-- Redactor CSS --}}
<link href="/assets/vendor/redactor/3.4.7/redactor.css" rel="stylesheet" type="text/css">

{{-- Redactor Plugins CSS --}}


{{-- Include Code Mirror CSS --}}
<link href="/assets/vendor/codemirror/5.62.0/codemirror.min.css" rel="stylesheet" type="text/css">

{{-- Redactor JS --}}
<script src="/assets/vendor/redactor/3.4.7/redactor.js"></script>

{{-- Include Code Mirror JS --}}
<script src="/assets/vendor/codemirror/5.62.0/codemirror.min.js"></script>
<script src="/assets/vendor/codemirror/5.62.0/xml.min.js"></script>

{{-- Redactor Plugins JS --}}
<script src="/assets/vendor/redactor/3.4.7/plugins/counter/counter.js"></script>

{{-- Language --}}
<script src="/assets/vendor/redactor/3.4.7/lang/{{ config('app.locale') }}.js"></script>

<script>
    $R('.wysiwyg-editor', {
        plugins: ['counter'],
        lang: document.documentElement.lang,

        imageFigure: false,
        imageResizable: true,
        imageUpload: '/photos/upload',
        imageData: {
            setup: 'editor',
            _token: document.head.querySelector('meta[name="csrf-token"]').content
        },

        source: {
            codemirror: {
                lineNumbers: true
            }
        }
    });
</script>
