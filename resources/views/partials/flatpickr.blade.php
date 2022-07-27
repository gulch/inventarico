{{-- Flatpickr --}}
<link rel="stylesheet" type="text/css" href="/assets/vendor/flatpickr/4.6.13/flatpickr.min.css">

<script defer src="/assets/vendor/flatpickr/4.6.13/flatpickr.min.js"></script>
<script defer src="/assets/vendor/flatpickr/4.6.13/{{ config('app.locale') }}.min.js"></script>

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
