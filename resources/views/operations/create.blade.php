@extends('template', [
    'title' => trans('app.operations') . ' → ' . trans('app.creating_new_operation') . ' ' . trans('app.for') . ' «' . $item->title . '»',
])

@section('content')
    <h1 class="ui header">
        <i class="cubes icon"></i>
        {{ trans('app.creating_new_operation') }} {{ trans('app.for') }} &laquo;{{ $item->title }}&raquo;
    </h1>

    <div class="ui warning form segment">
        {!! Form::open(['url' => '/operations']) !!}

        @include('operations._form')

        {!! Form::close() !!}
    </div>
@endsection