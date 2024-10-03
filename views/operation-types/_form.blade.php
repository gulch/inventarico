@include('partials.error-message')

<div class="field">
    {!! Form::label('title', trans('app.title') . '*') !!}
    {!! Form::text('title', null) !!}
</div>

<div class="field">
    <label>{{ trans('app.kind_of_operation') }}</label>
    <div class="ui selection dropdown">
        <input type="hidden" name="kind">
        <i class="dropdown icon"></i>
        <div class="default text">{{ trans('app.' . \App\Models\OperationType::KIND_OF[0]) }}</div>
        <div class="menu">
            @foreach(\App\Models\OperationType::KIND_OF as $kind_of)
                <div class="item" data-value="{{ $kind_of }}">{{ trans('app.' . $kind_of) }}</div>
            @endforeach
        </div>
    </div>
</div>

@include('partials.submit-buttons')