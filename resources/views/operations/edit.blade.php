@extends('template', [
    'title' => trans('app.operations') . ' → ' . trans('app.editing_operation_for') . ' «' . $instance->title . '»',
])

@section('content')
    <h1 class="ui header">
        <i class="tag icon"></i>
        {{ trans('app.editing_operation_for') }} &laquo;{{ $instance->title }}&raquo;
    </h1>

    <div class="ui warning form segment">
        {!! Form::model($operation, ['url' => '/operations/' . $operation->id, 'method' => 'PATCH']) !!}

        @include('operations._form')

        {!! Form::close() !!}
    </div>
@endsection
