@extends('template')

@section('content')
    <h1 class="ui header">
        <i class="photo icon"></i>
        {{ trans('app.editing') }} &laquo;{{ $photo->path }}&raquo;
    </h1>
    <div class="ui warning form segment">
        {!! Form::model($photo, ['url' => '/photos/' . $photo->id, 'method' => 'PATCH']) !!}

        @include('photos._form')

        {!! Form::close() !!}
    </div>
@endsection