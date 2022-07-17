@extends('template', [
    'title' => trans('app.things') . ' → ' . trans('app.creating_new_item'),
])

@section('content')
    <h1 class="ui header">
        <i class="tag icon"></i>
        {{ trans('app.creating_new_item') }}
    </h1>

    <div class="ui warning form segment">
        {!! Form::open(['url' => '/items']) !!}

        @include('items._form')

        {!! Form::close() !!}
    </div>
@endsection
