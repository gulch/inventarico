{{-- Flatpickr --}}
<link rel="stylesheet" type="text/css" href="/assets/vendor/flatpickr/2.0.0/flatpickr.min.css">

<script defer src="/assets/vendor/flatpickr/2.0.0/flatpickr.min.js"></script>
<script defer src="/assets/vendor/flatpickr/2.0.0/flatpickr.l10n.{{ config('app.locale') }}.js"></script>

<script>
    $(document).ready(function () {
        $('.datetimepicker').flatpickr({
            dateFormat: 'd.m.Y H:i',
            enableTime: true,
            time_24hr: true
        });
    });
</script>
