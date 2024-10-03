@extends('template', [
    'title' => trans('app.instances') . ' › ' . trans('app.editing') . ' «' . $instance->title . '»',
])

@section('content')
    <h1 class="ui header">
        <i class="tag icon"></i>
        {{ trans('app.editing') }} &laquo;{{ $instance->title }}&raquo;
    </h1>

    <div class="ui warning form segment">
        {!! Form::model($instance, ['url' => '/instances/'.$instance->id, 'method' => 'PATCH']) !!}

        @include('instances._form')

        {!! Form::close() !!}
    </div>
@endsection
