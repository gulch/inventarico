@include('partials.error-message')

<div class="field ui grey message">
    <div class="header">
        <div class="ui checkbox">
            {!! Form::checkbox('is_archived', 1) !!}
            {!! Form::label('is_archived', trans('app.q_archived_thing')) !!}
        </div>
    </div>
</div>

<div class="inline field">
    @php
    $published_date = $thing?->published_at?->format('Y-m-d H:i:s') ?? date('Y-m-d H:i:00');
    @endphp
    {!! Form::label('published_at', trans('app.published_date') . '*') !!}
    {!! Form::text('published_at', $published_date, ['class' => 'datetimepicker', 'readonly' => true]) !!}
</div>

<div class="field">
    {!! Form::label('id__Category', trans('app.category') . '*') !!}
    <select name="id__Category" class="ui search dropdown">
        <option value="0" @if ($selected_category === 0) selected @endif>---</option>
        @include('things._options', ['items' => $categories, 'depth' => 0])
    </select>
</div>

<div class="field">
    {!! Form::label('title', trans('app.title') . '*') !!}
    {!! Form::text('title', null) !!}
</div>

<div class="field" id="overview">
    <h3 class="ui top attached header">
        <i class="unordered list icon"></i>
        {{ trans('app.overview') }}
    </h3>

    <div class="ui bottom attached segment">
        @include('partials.overviews', [
            'overviews' => $thing?->overview,
        ])
    </div>
</div>

<div class="field">
    {!! Form::label('id__Photo', trans('app.photo')) !!}
    @include('partials.image-upload-or-choose', [
        'field_name' => 'id__Photo',
        'id' => $thing?->id__Photo,
        'image' => $thing?->photo,
        'key' => uniqid(),
        'setup' => 'photo',
        'path' => config('app.photo_image_upload_path'),
    ])
</div>

<div class="field">
    {!! Form::label('description', trans('app.description')) !!}
    {!! Form::textarea('description', null, ['class' => 'wysiwyg-editor']) !!}
</div>

@include('partials.submit-buttons')

@include('partials.editor')

@include('partials.flatpickr')
