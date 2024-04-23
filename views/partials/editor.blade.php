{{-- Include Code Mirror --}}
<link href="/assets/vendor/codemirror/5.62.0/codemirror.min.css" rel="stylesheet" type="text/css">
<script src="/assets/vendor/codemirror/5.62.0/codemirror.min.js"></script>
<script src="/assets/vendor/codemirror/5.62.0/xml.min.js"></script>

{{-- Redactor --}}
<link href="/assets/vendor/redactor/3.5.2/redactor.css" rel="stylesheet" type="text/css">
<script src="/assets/vendor/redactor/3.5.2/redactor.js"></script>

{{-- Redactor Plugins --}}
<script src="/assets/vendor/redactor/3.5.2/plugins/alignment.js"></script>
<link href="/assets/vendor/redactor/3.5.2/plugins/clips.css" rel="stylesheet" type="text/css">
<script src="/assets/vendor/redactor/3.5.2/plugins/clips.js"></script>
<script src="/assets/vendor/redactor/3.5.2/plugins/counter.js"></script>
<script src="/assets/vendor/redactor/3.5.2/plugins/fontcolor.js"></script>
<script src="/assets/vendor/redactor/3.5.2/plugins/fontsize.js"></script>
<script src="/assets/vendor/redactor/3.5.2/plugins/fontfamily.js"></script>
<script src="/assets/vendor/redactor/3.5.2/plugins/specialchars.js"></script>
<script src="/assets/vendor/redactor/3.5.2/plugins/table.js"></script>
<script src="/assets/vendor/redactor/3.5.2/plugins/video.js"></script>

{{-- Redactor Language --}}
<script src="/assets/vendor/redactor/3.5.2/lang/{{ config('app.locale') }}.js"></script>
<script src="/assets/js/redactor-plugins-lang.{{ config('app.locale') }}.js"></script>

{{-- Redactor Custom Clips --}}
<script src="/assets/js/redactor-plugin-clips.options.lang.{{ config('app.locale') }}.js"></script>

<script>
    $R('.wysiwyg-editor', {
        lang: document.documentElement.lang,
        formatting: ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'pre', 'p', 'blockquote', 'pre'],
        animation: false,
        structure: true,

        linkTarget: '_blank',
        linkTitle: true,
        linkNofollow: true,

        imageFigure: false,
        imageResizable: true,
        imageUpload: '/photos/upload',
        multipleUpload: false,
        imageData: {
            setup: 'editor',
            _token: document.head.querySelector('meta[name="csrf-token"]').content
        },

        source: {
            codemirror: {
                lineNumbers: true,
                lineWrapping: true,
                mode: "text/html"
            }
        },

        fontcolors: [
            '#ff8484', // price red
            '#78c600', // price green
            '#00c253', // another green
            '#a3a3a3', // grey
            '#1fa4f8' // pastel blue
        ],

        fontfamily: [
            'Arial',
            'Tahoma',
            'Segoe UI',
            'Times New Roman',
            'Consolas',
            'JetBrains Mono',
            'Source Sans Pro',
            'monospace'
        ],

        plugins: [
            'alignment',
            'clips',
            'counter',
            'fontcolor',
            'fontsize',
            'fontfamily',
            'specialchars',
            'table',
            'video'
        ]
    });
</script>
