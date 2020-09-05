@include('partials.error-message')

<div class="field ui grey message">
    <div class="header">
        <div class="ui checkbox">
            {!! Form::checkbox('is_archived', 1) !!}
            {!! Form::label('is_archived', trans('app.q_archived_item')) !!}
        </div>
    </div>
</div>

<div class="field">
    {!! Form::label('id__Category', trans('app.category').'*') !!}
    <select name="id__Category" class="ui search dropdown">
        <option value="0" @if($selected_category === 0) selected @endif>---</option>
        @include('items._options', ['items' => $categories, 'depth' => 0])
    </select>
</div>

<div class="field">
    {!! Form::label('title', trans('app.title') . '*') !!}
    {!! Form::text('title', null) !!}
</div>

<div class="field">
    {!! Form::label('description', trans('app.description')) !!}
    {!! Form::textarea('description', null, ['class' => 'wysiwyg-editor']) !!}
</div>

<div class="field" id="overview">
    <h3 class="ui top attached header">
        <i class="unordered list icon"></i>
        {{ trans('app.overview') }}
    </h3>

    <div class="ui bottom attached segment">
        @include('partials.overviews', [
            'overviews' => isset($item) ? $item->overview : null
        ])
    </div>
</div>

<div class="field">
    {!! Form::label('id__Photo', trans('app.photo')) !!}
    @include('partials.image-upload-or-choose', [
        'field_name' => 'id__Photo',
        'id' => isset($item) ? $item->id__Photo : null,
        'image' => isset($item) ? $item->photo : null,
        'key' => uniqid(),
        'setup' => 'photo',
        'path' => config('app.photo_image_upload_path')
    ])
</div>

@include('partials.submit-buttons')

@include('partials.editor')