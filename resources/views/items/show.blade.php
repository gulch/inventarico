@extends('template')

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

        <h2 class="ui dividing header">
            {{ trans('app.operations') }}
        </h2>

        <div class="ui large feed">

            @foreach($item->operations as $operation)

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
                        </div>
                        <div class="extra text">
                            {!! $operation->note !!}
                        </div>
                        <div class="extra images">
                            @foreach($operation->photos as $photo)
                                <a href="#">
                                    <img src="{{ config('app.thumb_image_upload_path') . $phot->path }}">
                                </a>
                            @endforeach
                        </div>
                        <div class="meta">
                            {{ trans('app.created_at') }}: {{ $operation->created_at->format('d.m.Y H:i') }}
                        </div>
                    </div>
                </div>

            @endforeach

        </div>

    </div>
@endsection