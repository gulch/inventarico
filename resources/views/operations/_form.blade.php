@include('partials.error-message')

<div class="inline field">
    <?php
    if (isset($operation)) {
        $operated_date = $operation->operated_at->format('Y-m-d H:i:s');
    } else {
        $operated_date = date('Y-m-d H:i:00');
    }
    ?>
    {!! Form::label('operated_at', trans('app.operated_date') . '*') !!}
    {!! Form::text('operated_at', $operated_date, ['id' => 'datetimepicker', 'readonly' => true]) !!}
</div>

<div class="ui divider"></div>

<div class="field">
    {!! Form::label('id__OperationType', trans('app.operation_type').'*') !!}
    {!! Form::select('id__OperationType', $operationTypes, null, ['class' => 'ui search dropdown']) !!}
</div>

<div class="fields">
    <div class="eight wide field">
        {!! Form::label('condition', trans('app.condition')) !!}
        {!! Form::select('condition', $conditions, null, ['class' => 'ui dropdown']) !!}
    </div>
    <div class="four wide field">
        {!! Form::label('price', trans('app.price')) !!}
        {!! Form::number('price', null) !!}
    </div>
    <div class="four wide field">
        {!! Form::label('currency', trans('app.currency')) !!}
        {!! Form::select('currency', $currencies, null, ['class' => 'ui dropdown']) !!}
    </div>
</div>

<div class="field">
    {!! Form::label('note', trans('app.note')) !!}
    {!! Form::textarea('note', null, ['class' => 'wysiwyg-editor']) !!}
</div>

<div class="field" id="gallery">
    <h3 class="ui top attached header">
        <i class="photo icon"></i>
        {{ trans('app.photos') }}
    </h3>

    <div class="ui bottom attached segment">
        <div class="field">
            @include('partials.gallery', [
                'field_name' => 'operation_photos',
                'photos' => isset($operation) ? $operation->photos : null,
                'key' => uniqid(),
                'setup' => 'photo'
            ])
        </div>
    </div>
</div>

<input type="hidden" name="id__Item" value="{{ $item->id }}">

@include('partials.submit-buttons')

@include('partials.editor')

{{-- Flatpickr --}}
<link rel="stylesheet"
      type="text/css"
      href="/assets/vendor/flatpickr/2.0.0/flatpickr.min.css">

<script src="/assets/vendor/flatpickr/2.0.0/flatpickr.min.js"></script>
<script src="/assets/vendor/flatpickr/2.0.0/flatpickr.l10n.{{ config('app.locale') }}.js"></script>

<script>
    $(document).ready(function () {
        $('#datetimepicker').flatpickr({
            dateFormat: 'd.m.Y H:i',
            enableTime: true,
            time_24hr: true
        });
    });
</script>
