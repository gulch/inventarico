@include('partials.error-message')

<div class="field ui grey message">
    <div class="header">
        <div class="ui checkbox">
            {!! Form::checkbox('is_archived', 1) !!}
            {!! Form::label('is_archived', trans('app.q_archived_instance')) !!}
        </div>
    </div>
</div>

<div class="inline field">
    @php
    $published_date = $instance?->published_at?->format('d.m.Y H:i:s') ?? date('d.m.Y H:i:00');
    @endphp
    {!! Form::label('published_at', trans('app.published_date') . '*') !!}
    {!! Form::text('published_at', $published_date, ['class' => 'datetimepicker', 'readonly' => true]) !!}
</div>

<div class="fields">
    <div class="twelve wide field">
        {!! Form::label('title', trans('app.title') . '*') !!}
        {!! Form::text('title', null) !!}
    </div>
    <div class="four wide field">
        {!! Form::label('price', trans('app.price')) !!}
        {!! Form::text('price', null) !!}
    </div>
</div>

<div class="field">

</div>

<div class="field" id="overview">
    <h3 class="ui top attached header">
        <i class="unordered list icon"></i>
        {{ trans('app.overview') }}
    </h3>

    <div class="ui bottom attached segment">
        @include('partials.overviews', [
            'overviews' => $instance?->overview,
        ])
    </div>
</div>

<div class="field">
    {!! Form::label('description', trans('app.description')) !!}
    {!! Form::textarea('description', null, ['class' => 'wysiwyg-editor']) !!}
</div>

<input type="hidden" name="id__Thing" value="{{ $thing->id }}">

@include('partials.submit-buttons')

@include('partials.editor')

@include('partials.flatpickr')
