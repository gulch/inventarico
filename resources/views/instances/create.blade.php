@extends('template', [
    'title' => trans('app.instances') . ' → ' . trans('app.creating_new_instance') . ' ' . trans('app.for') . ' «' . $thing->title . '»',
])

@section('content')
    <h1 class="ui header">
        <i class="shopping cart icon"></i>
        {{ trans('app.creating_new_instance') }} {{ trans('app.for') }} &laquo;{{ $thing->title }}&raquo;
    </h1>

    <div class="ui warning form segment">
        {!! Form::open(['url' => '/instances']) !!}

        @include('instances._form')

        {!! Form::close() !!}
    </div>
@endsection
