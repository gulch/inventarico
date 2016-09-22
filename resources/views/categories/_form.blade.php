@include('partials.error-message')

<div class="field">
    {!! Form::label('title', trans('app.title') . '*') !!}
    {!! Form::text('title', null) !!}
</div>

@include('partials.submit-buttons')