{{-- Flatpickr --}}
<link rel="stylesheet" type="text/css" href="/assets/vendor/flatpickr/3.0.5/flatpickr.min.css">

<script defer src="/assets/vendor/flatpickr/3.0.5/flatpickr.min.js"></script>
<script defer src="/assets/vendor/flatpickr/3.0.5/l10n/{{ config('app.locale') }}.js"></script>

<script>
    $(document).ready(function () {
        $('.datetimepicker').flatpickr({
            dateFormat: 'd.m.Y H:i',
            enableTime: true,
            time_24hr: true,
            locale: '{{ config('app.locale') }}',
        });
    });
</script>
