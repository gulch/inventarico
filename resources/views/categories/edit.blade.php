@extends('template')

@section('content')
    <h1 class="ui header">
        <i class="tag icon"></i>
        {{ trans('app.editing') }} &laquo;{{ $category->title }}&raquo;
    </h1>

    <div class="ui warning form segment">
        {!! Form::model($tag, ['url' => '/categories/'.$category->id, 'method' => 'PATCH']) !!}

        @include('categories._form')

        {!! Form::close() !!}
    </div>
@endsection