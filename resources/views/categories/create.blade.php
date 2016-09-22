@extends('template')

@section('content')
    <h1 class="ui header">
        <i class="folder outline icon"></i>
        {{ trans('app.creating_new_category') }}
    </h1>

    <div class="ui warning form segment">
        {!! Form::open(['url' => '/categories']) !!}

        @include('categories._form')

        {!! Form::close() !!}
    </div>
@endsection