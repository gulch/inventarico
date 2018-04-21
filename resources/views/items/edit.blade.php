@extends('template', [
    'title' => trans('app.items') . ' → ' . trans('app.editing') . ' «' . $item->title . '»',
])

@section('content')
    <h1 class="ui header">
        <i class="gift icon"></i>
        {{ trans('app.editing') }} &laquo;{{ $item->title }}&raquo;
    </h1>

    <div class="ui warning form segment">
        {!! Form::model($item, ['url' => '/items/'.$item->id, 'method' => 'PATCH']) !!}

        @include('items._form')

        {!! Form::close() !!}
    </div>
@endsection