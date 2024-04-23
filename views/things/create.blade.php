@extends('template', [
    'title' => trans('app.things') . ' › ' . trans('app.creating_new_thing'),
])

@section('content')
    <h1 class="ui header">
        <i class="tag icon"></i>
        {{ trans('app.creating_new_thing') }}
    </h1>

    <div class="ui warning form segment">
        {!! Form::open(['url' => '/things']) !!}

        @include('things._form')

        {!! Form::close() !!}
    </div>
@endsection
