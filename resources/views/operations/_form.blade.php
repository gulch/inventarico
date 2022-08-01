@include('partials.error-message')

<div class="inline field">
    @php
    $operated_date = $operation?->operated_at?->format('d.m.Y H:i:s') ?? date('d.m.Y H:i:00');
    @endphp
    {!! Form::label('operated_at', trans('app.operated_date') . '*') !!}
    {!! Form::text('operated_at', $operated_date, ['class' => 'datetimepicker', 'readonly' => true]) !!}
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

<div class="field">
    {!! Form::label('note', trans('app.note')) !!}
    {!! Form::textarea('note', null, ['class' => 'wysiwyg-editor']) !!}
</div>

<input type="hidden" name="id__Instance" value="{{ $instance->id }}">

@include('partials.submit-buttons')

@include('partials.editor')

@include('partials.flatpickr')
