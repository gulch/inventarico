@extends('template')

@section('content')
    <h1 class="ui header">
        <i class="cubes icon"></i>
        {{ trans('app.editing') }} &laquo;{{ $operationType->title }}&raquo;
    </h1>

    <div class="ui warning form segment">
        {!! Form::model($operationType, ['url' => '/operation-types/'.$operationType->id, 'method' => 'PATCH']) !!}

        @include('operation-types._form')

        {!! Form::close() !!}
    </div>
@endsection