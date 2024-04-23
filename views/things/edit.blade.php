@extends('template', [
    'title' => trans('app.things') . ' › ' . trans('app.editing') . ' «' . $thing->title . '»',
])

@section('content')
    <h1 class="ui header">
        <i class="tag icon"></i>
        {{ trans('app.editing') }} &laquo;{{ $thing->title }}&raquo;
    </h1>

    <div class="ui warning form segment">
        {!! Form::model($thing, ['url' => '/things/'.$thing->id, 'method' => 'PATCH']) !!}

        @include('things._form')

        {!! Form::close() !!}
    </div>
@endsection
