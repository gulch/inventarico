{{-- Include Code Mirror --}}
<link href="/assets/vendor/codemirror/5.62.0/codemirror.min.css" rel="stylesheet" type="text/css">
<script src="/assets/vendor/codemirror/5.62.0/codemirror.min.js"></script>
<script src="/assets/vendor/codemirror/5.62.0/xml.min.js"></script>

{{-- Redactor --}}
<link href="/assets/vendor/redactor/3.4.7/redactor.css" rel="stylesheet" type="text/css">
<script src="/assets/vendor/redactor/3.4.7/redactor.js"></script>

{{-- Redactor Plugins --}}
<script src="/assets/vendor/redactor/3.4.7/plugins/alignment.js"></script>
<link href="/assets/vendor/redactor/3.4.7/plugins/clips.css" rel="stylesheet" type="text/css">
<script src="/assets/vendor/redactor/3.4.7/plugins/clips.js"></script>
<script src="/assets/vendor/redactor/3.4.7/plugins/counter.js"></script>
<script src="/assets/vendor/redactor/3.4.7/plugins/fontcolor.js"></script>
<script src="/assets/vendor/redactor/3.4.7/plugins/fontsize.js"></script>
<script src="/assets/vendor/redactor/3.4.7/plugins/specialchars.js"></script>
<script src="/assets/vendor/redactor/3.4.7/plugins/table.js"></script>
<script src="/assets/vendor/redactor/3.4.7/plugins/video.js"></script>

{{-- Redactor Language --}}
<script src="/assets/vendor/redactor/3.4.7/lang/{{ config('app.locale') }}.js"></script>

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
                lineNumbers: true
            }
        },

        plugins: [
            'alignment', 
            'clips',
            'counter',
            'fontcolor',
            'fontsize',
            'specialchars',
            'table',
            'video'
        ],
        clips: [
            ['OFFICIAL WEBSITE', 'OFFICIAL WEBSITE'],
            ['купив на Aliexpress за', 'купив на <b>Aliexpress</b> за'],
            ['купив на Amazon за', 'купив на <b>Amazon</b> за']
        ]
    });
</script>
