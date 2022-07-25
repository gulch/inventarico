@extends('template', ['title' => trans('app.operations_list') . ' :: INVENTARICO' ])

@section('content')

    <h1 class="ui header">
        <div class="content">
            {{ trans('app.operations') }}
            <div class="sub header">{{ trans('app.operations_list') }}</div>
        </div>
    </h1>

    <div class="ui stackable menu">
        <div class="item">
            <i class="cube large icon"></i>
        </div>

        <div class="item">
            {!! Form::select('operationtype', $operationTypes, app('request')->input('operationtype'), ['class' => 'ui search dropdown']) !!}
        </div>

        <div class="right menu">
            <div class="item">
                <div class="ui floating labeled icon pointing dropdown basic button">
                    <i class="sort content ascending icon"></i>
                    <span class="text">{{ trans('app.sorting') }}</span>
                    <input type="hidden"
                           name="sort"
                           value="{{ app('request')->input('sort') ?? 'operation_date_desc' }}"
                    >
                    <div class="menu">
                        <div class="header">{{ trans('app.operation_date') }}</div>
                        <div class="item" data-value="operation_date_desc">{{ trans('app.new_first') }}</div>
                        <div class="item" data-value="operation_date_asc">{{ trans('app.old_first') }}</div>
                        <div class="divider"></div>
                        <div class="header">{{ trans('app.created_date') }}</div>
                        <div class="item" data-value="created_desc">{{ trans('app.new_first') }}</div>
                        <div class="item" data-value="created_asc">{{ trans('app.old_first') }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @if (sizeof($operations))
        <p>
            {{ trans('app.summary') }}: {{ $operations->total() }}
        </p>
    @endif

    <div class="ui clearing divider"></div>

    @if (sizeof($operations))
        <div class="ui relaxed divided items">
            @foreach($operations as $operation)
                <div class="item"
                     data-id="{{ $operation->id }}"
                     data-action-element="1"
                >
                    <div class="image">
                        @if($operation->instance?->thing?->photo)
                            <img src="{{ config('app.thumb_image_upload_path') . $operation->instance->thing->photo->path }}">
                        @else
                            <img src="{{ config('app.assets_img_path') }}/placeholder-white-175x130.svg">
                        @endif
                    </div>

                    <div class="content">
                        <div class="ui basic segment">

                            <div class="ui large header">
                                &laquo;{{ $operation->type->title }}&raquo;
                                {{ trans('app.for') }}
                                <a href="/things/{{ $operation->instance->thing->id }}/show">
                                    {{ $operation->instance->title }}
                                </a>
                            </div>

                            <div class="meta">
                            <p>

                                <i class="clock outline icon"></i>
                                {{ $operation->operated_at->format('d.m.Y H:i:s') }}

                            </p>
                                <p>
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
                                    <i class="edit outline icon"></i>{{ trans('app.do_edit') }}
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
                {!! $operations->appends([
                        'sort' => app('request')->input('sort'),
                        'operationtype' => app('request')->input('operationtype')
                    ])->links()
                !!}
            </div>
        </div>

    @else
        @include('partials.nothing-found')
    @endif
@endsection
