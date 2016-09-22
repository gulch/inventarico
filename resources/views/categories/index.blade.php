@extends('template')

@section('content')

    <h1 class="ui header">
        <div class="content">
            {{ trans('app.categories') }}
            <div class="sub header">{{ trans('app.categories_list') }}</div>
        </div>
    </h1>

    <div class="ui stackable menu">
        <div class="item">
            <i class="folder outline large icon"></i>
        </div>

        <div class="right menu">
            <a href="/categories/create" class="item">
                <i class="add icon"></i>
                {{ trans('app.do_add') }}
            </a>
        </div>
    </div>

    <div class="ui clearing divider"></div>

    @if (!is_null($categories))
        <div class="ui relaxed items">
            @foreach($categories as $category)
                <div class="item"
                     data-id="{{ $category->id }}"
                     data-action-element="1"
                >
                    <div class="content">
                        <div class="ui segment raised">

                            <div class="ui large header">
                                {{ $category->title }}
                            </div>

                            <div class="meta">
                                {{ trans('app.created_at') }}: {{ $category->created_at->format('d.m.Y H:i:s') }}
                            </div>

                            <div class="extra">

                                <a href="/categories/{{ $category->id }}/edit">
                                    <i class="edit icon"></i>{{ trans('app.do_edit') }}
                                </a>
                                <a data-popup="1">
                                    <i class="remove circle icon"></i>{{ trans('app.do_remove') }}
                                </a>
                                <div class="ui custom popup">
                                    <div class="ui huge header center aligned">{{ trans('app.q_delete') }}</div>
                                    <span class="ui negative button"
                                          data-action-name="remove"
                                          data-action="/categories/{{ $category->id }}"
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
                {!! $categories->render() !!}
            </div>
        </div>
    @else
        @include('partials.nothing-found')
    @endif
@endsection