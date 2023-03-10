@extends('template', [
    'title' => trans('app.categories') . ' › ' . trans('app.editing') . ' «' . $category->title . '»',
])

@section('content')
    <h1 class="ui header">
        <i class="folder outline icon"></i>
        {{ trans('app.editing') }} &laquo;{{ $category->title }}&raquo;
    </h1>

    <div class="ui warning form segment">
        {!! Form::model($category, ['url' => '/categories/'.$category->id, 'method' => 'PATCH']) !!}

        @include('categories._form')

        {!! Form::close() !!}
    </div>
@endsection