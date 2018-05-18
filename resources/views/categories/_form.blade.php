@include('partials.error-message')

<div class="field">
    {!! Form::label('parent_id', trans('app.parent_category')) !!}
    <select name="parent_id" class="ui dropdown">
        <option value="0" @if($parent_category === 0) selected @endif>---</option>
        @include('categories._options', ['items' => $parent_categories, 'depth' => 0])
    </select>
</div>

<div class="field">
    {!! Form::label('title', trans('app.title') . '*') !!}
    {!! Form::text('title', null) !!}
</div>

@include('partials.submit-buttons')