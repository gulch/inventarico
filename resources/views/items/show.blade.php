@extends('template', [
    'title' => trans('app.items') . ' → ' . $item->title,
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
    ],
])

@section('content')

    <h1 class="ui header">
        <i class="gift icon"></i>
        <div class="content">
            <div class="sub header">
                {{ $item->category->title }}
            </div>
            {{ $item->title }}
        </div>
    </h1>

    <div class="ui divider"></div>

    <div class="ui segment">

        @if($item->photo)
            <img src="{{ config('app.photo_image_upload_path') . $item->photo->path }}"
                 alt="{{ $item->photo->description }}"
                 class="ui fluid image"
            >
        @endif

        <a class="ui right floated labeled icon button"
           href="/item/{{ $item->id }}/edit"
        >
            <i class="edit icon"></i>
            {{ trans('app.do_edit_item') }}
        </a>

        <h2 class="ui dividing header">
            {{ trans('app.description') }}
        </h2>

        {!! $item->description !!}

        <h2 class="ui dividing header">
            {{ trans('app.overview') }}
        </h2>

        @if(isset($item->overview) && $overviews = json_decode($item->overview, true))
            <?php
            usort($overviews, function ($a, $b) {
                return $a['order'] <=> $b['order'];
            });
            ?>

            <table class="ui very basic unstackable table">
                <tbody>
                @foreach($overviews as $overview)
                    <tr>
                        <td>
                            {{ $overview['title'] }}
                        </td>
                        <td class="right aligned ten wide">
                            {{ $overview['value'] }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        <a class="ui right floated labeled icon button"
           href="/operations/create/{{ $item->id }}"
        >
            <i class="plus icon"></i>
            {{ trans('app.add_new_operation') }}
        </a>

        <h2 class="ui dividing header">
            {{ trans('app.operations') }}
        </h2>

        <div class="ui large feed">

            @foreach($item->operations()->orderBy('operated_at', 'desc')->get() as $operation)

                <div class="event">
                    <div class="label">
                        <i class="cube icon"></i>
                    </div>
                    <div class="content">

                        <div class="summary">
                            {{ $operation->type->title }}
                            <div class="date">
                                {{ $operation->operated_at->format('d.m.Y H:i') }}
                            </div>
                            <div class="ui label">
                                {{ $operation->condition === 'NEW' ? trans('app.new') : trans('app.used') }}
                            </div>
                            <?php
                            $operation_label_type = 'teal basic';

                            if ($operation->type->kind === 'profitable') {
                                $operation_label_type = 'green';
                            } elseif ($operation->type->kind === 'expenditure') {
                                $operation_label_type = 'red';
                            }

                            ?>
                            <div class="ui left pointing {{ $operation_label_type }} label">
                                {{ $operation->price }} {{ $operation->currency }}
                            </div>
                        </div>

                        <div class="extra text">
                            {!! $operation->note !!}
                        </div>

                        @if(sizeof($operation->photos))
                            <div class="extra images gallery">
                                @foreach($operation->photos as $photo)
                                    <a href="{{ config('app.photo_image_upload_path') . $photo->path }}">
                                        <img class="ui rounded image"
                                             src="{{ config('app.thumb_image_upload_path') . $photo->path }}"
                                        >
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        <div class="meta">
                            {{ trans('app.created_at') }}: {{ $operation->created_at->format('d.m.Y H:i') }}
                            &nbsp;
                            <a href="/operations/{{ $operation->id }}/edit">
                                {{ trans('app.do_edit') }}
                            </a>
                        </div>
                    </div>
                </div>

            @endforeach

        </div>

    </div>
@endsection