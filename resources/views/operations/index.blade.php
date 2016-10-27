@extends('template')

@section('content')

    <h1 class="ui header">
        <div class="content">
            {{ trans('app.operations') }}
            <div class="sub header">{{ trans('app.operations_list') }}</div>
        </div>
    </h1>

    <div class="ui stackable menu">
        <div class="item">
            <i class="gift large icon"></i>
        </div>
    </div>

    <div class="ui clearing divider"></div>

    @if (sizeof($operations))
        <div class="ui relaxed divided items">
            @foreach($operations as $operation)
                <div class="item"
                     data-id="{{ $operation->id }}"
                     data-action-element="1"
                >
                    <div class="image">
                        @if($operation->item->photo)
                            <img src="{{ config('app.thumb_image_upload_path') . $operation->item->photo->path }}">
                        @else
                            <img src="{{ config('app.assets_img_path') }}/placeholder-white-175x130.svg">
                        @endif
                    </div>

                    <div class="content">
                        <div class="ui basic segment">

                            <div class="ui large header">
                                &laquo;{{ $operation->type->title }}&raquo;
                                {{ trans('app.for') }}
                                <a href="/items/{{ $operation->item->id }}/show">
                                    {{ $operation->item->title }}
                                </a>
                            </div>

                            <div class="meta">

                                <p>
                                    <span class="ui large basic label">
                                        <i class="clock icon"></i>
                                        {{ $operation->operated_at->format('d.m.Y H:i:s') }}
                                    </span>

                                    <span class="ui large label">
                                        {{ $operation->condition === 'NEW' ? trans('app.new') : trans('app.used') }}
                                    </span>

                                    <span class="ui left pointing teal basic label">
                                        {{ $operation->price }} {{ $operation->currency }}
                                    </span>
                                </p>

                                {{ trans('app.created_at') }}: {{ $operation->created_at->format('d.m.Y H:i:s') }}
                                <br>
                                {{ trans('app.updated_at') }}: {{ $operation->updated_at->format('d.m.Y H:i:s') }}
                            </div>

                            <div class="extra">

                                <a href="/operations/{{ $operation->id }}/edit">
                                    <i class="edit icon"></i>{{ trans('app.do_edit') }}
                                </a>
                                <a data-popup="1">
                                    <i class="remove circle icon"></i>{{ trans('app.do_remove') }}
                                </a>
                                <div class="ui custom popup">
                                    <div class="ui huge header center aligned">{{ trans('app.q_delete') }}</div>
                                    <span class="ui negative button"
                                          data-action-name="remove"
                                          data-action="/operations/{{ $operation->id }}"
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
                {!! $operations->render() !!}
            </div>
        </div>

    @else
        @include('partials.nothing-found')
    @endif
@endsection