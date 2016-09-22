@include('partials.error-message')

<div class="field">
    {!! Form::label('id__Category', trans('app.category').'*') !!}
    {!! Form::select('id__Category', $categories, null, ['class' => 'ui search dropdown']) !!}
</div>

<div class="field">
    {!! Form::label('title', trans('app.title') . '*') !!}
    {!! Form::text('title', null) !!}
</div>

<div class="field">
    {!! Form::label('description', trans('app.description')) !!}
    {!! Form::textarea('description', null, ['class' => 'wysiwyg-editor']) !!}
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