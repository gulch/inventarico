@extends('template', [
    'title' => trans('app.items')
])

@section('content')

    <div class="ui grid">
        <div class="middle aligned twelve wide column">
            <h1 class="ui header">
                <div class="content">
                    {{ trans('app.items') }}
                    <div class="sub header">{{ trans('app.items_list') }}</div>
                </div>
            </h1>
        </div>

        <div class="middle aligned right aligned four wide column">
            <a href="/items/create" class="ui large labeled icon basic button">
                <i class="add icon"></i>
                {{ trans('app.do_add') }}
            </a>
        </div>
    </div>

    {{-- Menu Bar --}}
    <div class="ui stackable menu">
        <div class="item">
            <i class="gift large icon"></i>
        </div>

        <div class="item">
            <select name="category" class="ui large search dropdown wide-min-320">
                <option value="0" @if($selected_category === 0) selected @endif>---</option>
                @include('items._options', ['items' => $categories, 'depth' => 0])
            </select>
        </div>

        <a class="item @if(url()->current() == route('available-items')) active @endif"
           href="{{ route('available-items') }}"
        >
            {{ trans('app.available') }}
        </a>

        <a class="item @if(url()->current() == route('archived-items')) active @endif"
           href="{{ route('archived-items') }}"
        >
            {{ trans('app.archived') }}
        </a>

        <div class="right menu">
            <div class="item">
                <div class="ui floating labeled icon pointing dropdown basic button">
                    <i class="sort content ascending icon"></i>
                    <span class="text">{{ trans('app.sorting') }}</span>
                    <input type="hidden"
                           name="sort"
                           value="{{ app('request')->input('sort') ?? 'created_desc' }}"
                    >
                    <div class="menu">
                        <div class="header">{{ trans('app.created_date') }}</div>
                        <div class="item" data-value="created_desc">{{ trans('app.new_first') }}</div>
                        <div class="item" data-value="created_asc">{{ trans('app.old_first') }}</div>
                        <div class="divider"></div>
                        <div class="header">{{ trans('app.title_sort') }}</div>
                        <div class="item" data-value="alphabet_asc">{{ trans('app.alphabet_asc') }}</div>
                        <div class="item" data-value="alphabet_desc">{{ trans('app.alphabet_desc') }}</div>
                        <div class="divider"></div>
                        <div class="header">{{ trans('app.updated_date') }}</div>
                        <div class="item" data-value="updated_desc">{{ trans('app.new_first') }}</div>
                        <div class="item" data-value="updated_asc">{{ trans('app.old_first') }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @if ($items)
        <p>
            {{ trans('app.summary') }}: {{ $items->total() }}
        </p>
    @endif

    <div class="ui clearing divider"></div>

    @if ($items)
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

                            <div class="ui statistic tiny right floated">
                                <div class="value">
                                    <i class="cubes icon"></i>
                                    {{ $item->operations->count() }}
                                </div>

                                @if($item->is_archived)
                                    <div class="value">
                                        <span class="ui tiny label">{{ trans('app.archived_item') }}</span>
                                    </div>
                                @endif
                            </div>

                            <a href="/items/{{ $item->id }}/show" target="_blank" class="ui large header">
                                {{ $item->title }}
                            </a>

                            <div class="meta">
                                <p>
                                    {{ trans('app.category') }}:
                                    <a href="/items?category={{ $item->category->id }}">
                                        {{ $item->category->title }}
                                    </a>
                                </p>

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
                {!! $items->appends([
                    'sort' => app('request')->input('sort'),
                    'category' => app('request')->input('category')
                    ])->links()
                !!}
            </div>
        </div>

    @else
        @include('partials.nothing-found')
    @endif
@endsection