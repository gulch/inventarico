@extends('template', [
    'title' => trans('app.operation_types') . ' → ' . trans('app.creating_new_operation_type'),
])

@section('content')
    <h1 class="ui header">
        <i class="cubes icon"></i>
        {{ trans('app.creating_new_operation_type') }}
    </h1>

    <div class="ui warning form segment">
        {!! Form::open(['url' => '/operation-types']) !!}

        @include('operation-types._form')

        {!! Form::close() !!}
    </div>
@endsection