@extends('template', [
    'title' => trans('app.things'),
])

@section('content')

    <div class="ui grid">
        <div class="middle aligned twelve wide column">
            <h1 class="ui header">
                <div class="content">
                    {{ trans('app.things') }}
                    <div class="sub header">{{ trans('app.things_list') }}</div>
                </div>
            </h1>
        </div>

        <div class="middle aligned right aligned four wide column">
            <a href="/things/create" class="ui big labeled icon basic button">
                <i class="add icon"></i>
                {{ trans('app.do_add') }}
            </a>
        </div>
    </div>

    {{-- Menu Bar --}}
    <div class="ui stackable menu">
        <div class="item">
            <i class="tag large icon"></i>
        </div>

        <div class="item">
            <select name="category" class="ui search dropdown wide-min-320">
                <option value="0" @if ($selected_category === 0) selected @endif>{{ trans('app.all_categories') }}
                </option>
                @include('items._options', ['items' => $categories, 'depth' => 0])
            </select>
        </div>

        <div class="right menu">
            <div class="item">
                <div class="ui labeled icon floating pointing dropdown basic button">
                    <i class="archive icon"></i>
                    <span class="text">{{ trans('app.availability') }}</span>
                    <input type="hidden" name="availability" value="{{ request()->input('availability') ?? 'all' }}">
                    <div class="menu">
                        <div class="item" data-value="all">{{ trans('app.all') }}</div>
                        <div class="item" data-value="available">{{ trans('app.available') }}</div>
                        <div class="item" data-value="archived">{{ trans('app.archived') }}</div>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="ui floating labeled icon pointing dropdown basic button">
                    <i class="sort content ascending icon"></i>
                    <span class="text">{{ trans('app.sorting') }}</span>
                    <input type="hidden" name="sort" value="{{ request()->input('sort') ?? 'published_desc' }}">
                    <div class="menu">
                        <div class="header">{{ trans('app.published_date') }}</div>
                        <div class="item" data-value="published_desc">{{ trans('app.new_first') }}</div>
                        <div class="item" data-value="published_asc">{{ trans('app.old_first') }}</div>
                        <div class="divider"></div>
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

    <div class="ui fluid big icon input items-search-input">
        <input type="text" name="q" placeholder="{{ trans('app.ph_search') }}..."
            @if (request('q')) value="{{ request('q') }}" @endif>
        <i id="q_clean" class="remove circle link icon"></i>
    </div>

    @if ($things)
        <p>
            {{ trans('app.summary') }}: {{ $things->total() }}
        </p>
    @endif

    <div class="ui clearing divider"></div>

    @if ($things)
        <div class="ui relaxed divided items things-items">
            @foreach ($things as $thing)
                <div class="item @if ($thing->is_archived) archived-item @endif"
                     data-id="{{ $thing->id }}"
                     data-action-element="1"
                >
                    <div class="image">
                        @if ($thing->photo)
                            <img src="{{ config('app.thumb_image_upload_path') . $thing->photo->path }}">
                        @else
                            <img src="{{ config('app.assets_img_path') }}/placeholder-white-175x130.svg">
                        @endif
                    </div>

                    <div class="content">
                        <div class="ui statistic tiny right floated">
                            <div class="value">
                                <i class="shopping cart icon"></i>
                                {{ $thing->instances->count() }}
                            </div>

                            @if ($thing->is_archived)
                                <div class="value">
                                    <span class="ui tiny label">{{ trans('app.archived_thing') }}</span>
                                </div>
                            @endif
                        </div>

                        <a href="/things/{{ $thing->id }}/show" target="_blank" class="ui large header">
                            @if (request('q'))
                                @php
                                    $alt = \App\Http\Controllers\ItemsController::transliterato(request('q'));
                                    $alt = $alt ? '|' . implode('|', $alt) : '';
                                @endphp
                                {!! preg_replace('/(' . request('q') . $alt . ')/iu', '<mark>$1</mark>', e($thing->title)) !!}
                            @else
                                {{ $thing->title }}
                            @endif
                        </a>

                        <div class="meta">
                            <p>
                                {{ trans('app.category') }}:
                                <a href="/things?category={{ $thing->category->id }}">
                                    {{ $thing->category->title }}
                                </a>
                            </p>

                            {{ trans('app.created_at') }}: {{ $thing->created_at->format('d.m.Y H:i:s') }}
                            <br>
                            {{ trans('app.updated_at') }}: {{ $thing->updated_at->format('d.m.Y H:i:s') }}
                            <br>
                            {{ trans('app.published_at') }}: {{ $thing->published_at->format('d.m.Y H:i:s') }}
                        </div>

                        <div class="extra">

                            <a href="/instances/create/{{ $thing->id }}">
                                <i class="shopping cart icon"></i>
                                {{ trans('app.do_add_instance') }}
                            </a>

                            <a href="/things/{{ $thing->id }}/edit">
                                <i class="edit outline icon"></i>{{ trans('app.do_edit') }}
                            </a>

                            <a data-popup="1">
                                <i class="remove circle icon"></i>{{ trans('app.do_remove') }}
                            </a>
                            <div class="ui custom popup">
                                <div class="ui huge header center aligned">{{ trans('app.q_delete') }}</div>
                                <span class="ui negative button" data-action-name="remove"
                                    data-action="/things/{{ $thing->id }}" data-method="DELETE">{{ trans('app.yes') }}
                                </span>
                                <span class="ui button">{{ trans('app.no') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="ui hidden divider"></div>

        <div class="ui middle aligned stackable centered grid container">
            <div class="ui row">
                {!! $things->appends([
                        'sort' => request()->input('sort'),
                        'category' => request()->input('category'),
                        'availability' => request()->input('availability'),
                    ])->links() !!}
            </div>
        </div>
    @else
        @include('partials.nothing-found')
    @endif
@endsection
