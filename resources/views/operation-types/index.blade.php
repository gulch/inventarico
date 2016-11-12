@extends('template')

@section('content')

    <div class="ui grid">
        <div class="middle aligned twelve wide column">
            <h1 class="ui header">
                <div class="content">
                    {{ trans('app.operation_types') }}
                    <div class="sub header">{{ trans('app.operation_types_list') }}</div>
                </div>
            </h1>
        </div>
        <div class="middle aligned right aligned four wide column">
            <a href="/operation-types/create" class="ui large labeled icon basic button">
                <i class="add icon"></i>
                {{ trans('app.do_add') }}
            </a>
        </div>
    </div>

    <div class="ui stackable menu">
        <div class="item">
            <i class="cubes large icon"></i>
        </div>
    </div>

    <div class="ui clearing divider"></div>

    @if (sizeof($operationTypes))
        <div class="ui relaxed items">
            @foreach($operationTypes as $operationType)
                <div class="item"
                     data-id="{{ $operationType->id }}"
                     data-action-element="1"
                >
                    <div class="content">
                        <div class="ui segment raised">

                            <div class="ui large header">
                                {{ $operationType->title }}
                            </div>

                            <div class="meta">
                                {{ trans('app.created_at') }}: {{ $operationType->created_at->format('d.m.Y H:i:s') }}
                            </div>

                            <div class="extra">

                                <a href="/operation-types/{{ $operationType->id }}/edit">
                                    <i class="edit icon"></i>{{ trans('app.do_edit') }}
                                </a>
                                <a data-popup="1">
                                    <i class="remove circle icon"></i>{{ trans('app.do_remove') }}
                                </a>
                                <div class="ui custom popup">
                                    <div class="ui huge header center aligned">{{ trans('app.q_delete') }}</div>
                                    <span class="ui negative button"
                                          data-action-name="remove"
                                          data-action="/operation-types/{{ $operationType->id }}"
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
                {!! $operationTypes->render() !!}
            </div>
        </div>
    @else
        @include('partials.nothing-found')
    @endif
@endsection