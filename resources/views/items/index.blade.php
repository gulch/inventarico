@extends('template')

@section('content')

    <h1 class="ui header">
        <div class="content">
            {{ trans('app.items') }}
            <div class="sub header">{{ trans('app.items_list') }}</div>
        </div>
    </h1>

    <div class="ui stackable menu">
        <div class="item">
            <i class="gift large icon"></i>
        </div>

        <div class="right menu">
            <a href="/items/create" class="item">
                <i class="add icon"></i>
                {{ trans('app.do_add') }}
            </a>
        </div>
    </div>

    <div class="ui clearing divider"></div>

    @if (sizeof($items))
        <div class="ui relaxed divided items">
            @foreach($items as $item)
                <div class="item"
                     data-id="{{ $item->id }}"
                     data-action-element="1"
                >
                    <div class="image">
                        @if($item->photo)
                            <img src="{{ config('app.thumb_image_upload_path') . $item->photo->path }}">
                        @else
                            <img src="{{ config('app.assets_img_path') }}/placeholder-white-175x130.svg">
                        @endif
                    </div>

                    <div class="content">
                        <div class="ui basic segment">

                            <div class="ui large header">
                                {{ $item->title }}
                            </div>

                            <div class="meta">
                                {{ trans('app.created_at') }}: {{ $item->created_at->format('d.m.Y H:i:s') }}
                                <br>
                                {{ trans('app.updated_at') }}: {{ $item->updated_at->format('d.m.Y H:i:s') }}
                            </div>

                            <div class="extra">

                                <a href="/operations/create/{{ $item->id }}">
                                    <i class="cubes icon"></i>{{ trans('app.do_add_operation') }}
                                </a>

                                <a href="/items/{{ $item->id }}/edit">
                                    <i class="edit icon"></i>{{ trans('app.do_edit') }}
                                </a>

                                <a data-popup="1">
                                    <i class="remove circle icon"></i>{{ trans('app.do_remove') }}
                                </a>
                                <div class="ui custom popup">
                                    <div class="ui huge header center aligned">{{ trans('app.q_delete') }}</div>
                                    <span class="ui negative button"
                                          data-action-name="remove"
                                          data-action="/items/{{ $item->id }}"
                                          data-method="DELETE">{{ trans('app.yes') }}
                                        </span>
                                    <span class="ui button">{{ trans('app.no') }}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="ui hidden divider"></div>

        <div class="ui middle aligned stackable centered grid container">
            <div class="ui row">
                {!! $items->render() !!}
            </div>
        </div>

    @else
        @include('partials.nothing-found')
    @endif
@endsection