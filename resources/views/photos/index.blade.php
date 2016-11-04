@extends('template', [
    'scripts' => [
        [
            'load' => 'defer',
            'src' => '/assets/vendor/lightgallery/1.0.0/js/lightgallery.js'
        ],
        [
            'load' => 'defer',
            'src' => '/assets/vendor/lightgallery/1.0.0/js/lg-zoom.js'
        ]
    ],
    'styles' => [
        '/assets/vendor/lightgallery/1.0.0/css/lightgallery.css'
    ]
])

@section('content')
    <h1 class="ui header">
        <div class="content">
            {{ trans('app.photos') }}
            <div class="sub header">{{ trans('app.photos_list') }}</div>
        </div>
    </h1>

    <div class="ui divider"></div>

    @include('photos._images-list')

    <div class="ui hidden divider"></div>

    <div class="ui middle aligned stackable centered grid container">
        <div class="ui row">
            {!! $photos->render() !!}
        </div>
    </div>
@endsection