@extends('template', [
    'title' => trans('app.things') . ' › ' . $thing->title,
    'scripts' => [
        [
            'load' => 'defer',
            'src' => '/assets/vendor/lightgallery/1.0.0/js/lightgallery.js',
        ],
        [
            'load' => 'defer',
            'src' => '/assets/vendor/lightgallery/1.0.0/js/lg-zoom.js',
        ],
    ],
    'styles' => ['/assets/vendor/lightgallery/1.0.0/css/lightgallery.css'],
])

@section('content')

    <h1 class="ui header">
        <i class="tag icon"></i>
        <div class="content">
            <div class="sub header">

                <div class="ui large breadcrumb">
                    <a class="section" href="/things">
                        {{ trans('app.things') }}
                    </a>

                    <i class="right angle icon divider"></i>

                    @if ($thing->category->hasAncestors())
                        @foreach ($thing->category->ancestors()->get() as $ancestor)
                            <a class="section" href="/things?category={{ $ancestor->id }}">
                                {{ $ancestor->title }}
                            </a>
                            <i class="right angle icon divider"></i>
                        @endforeach
                    @endif

                    <a class="section" href="/things?category={{ $thing->category->id }}">
                        {{ $thing->category->title }}
                    </a>
                </div>

            </div>
            {{ $thing->title }}
            @if ($thing->is_archived)
                <div class="ui left pointing teal label">
                    {{ trans('app.archived_thing') }}
                </div>
            @endif
        </div>
    </h1>

    <div class="ui divider"></div>

    <div class="ui labeled icon stackable menu">
        <a class="item" href="/instances/create/{{ $thing->id }}">
            <i class="plus icon"></i>
            {{ trans('app.do_add_instance') }}
        </a>
        <a class="item" href="/things/{{ $thing->id }}/edit">
            <i class="edit outline icon"></i>
            {{ trans('app.do_edit_thing') }}
        </a>
    </div>

    <div class="ui segment">

        @if ($thing->photo)
            <img src="{{ config('inco.photo_image_upload_path') . $thing->photo->path }}"
                alt="{{ $thing->photo->description }}" class="ui fluid image">
        @endif

        <h2 class="ui dividing header">
            {{ trans('app.description') }}
        </h2>

        {!! $thing->description !!}

        @if (isset($thing->overview) && ($overviews = json_decode($thing->overview, true)))
            <h2 class="ui dividing header">
                {{ trans('app.overview') }}
            </h2>

            @php
                usort($overviews, function ($a, $b) {
                    return $a['order'] <=> $b['order'];
                });
            @endphp

            <table class="ui very basic unstackable table">
                <tbody>
                    @foreach ($overviews as $overview)
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

    </div>


    @foreach ($thing->instances as $instance)
        <div class="ui raised segments">
            <div class="ui segment action-segment @if($instance->is_archived) secondary @endif">
                <div class="ui items">
                    <div class="item">
                        <div class="content">

                            @if($instance->is_archived)
                                <p class="ui grey large ribbon label">
                                    {{ trans('app.archived_instance') }}
                                </p>
                            @endif

                            <p class="grey-text"
                               title="{{ trans('app.published_date') }}"
                            >
                                {{ $instance->published_at->format('d.m.Y H:i') }}
                            </p>

                            <div class="header header-margins">
                                <i class="shopping cart icon"></i>
                                {{ $instance->title }}
                            </div>

                            @if ($instance->description)
                                <div class="description operation-note">
                                    {!! $instance->description !!}
                                </div>
                            @endif

                            <div class="description">

                                <span class="ui tag large label">
                                    {{ $instance->price }}
                                </span>

                                @if (isset($instance->overview) && ($instance_overviews = json_decode($instance->overview, true)))

                                    @php
                                        usort($instance_overviews, function ($a, $b) {
                                            return $a['order'] <=> $b['order'];
                                        });
                                    @endphp

                                    @foreach ($instance_overviews as $inov)
                                        <span class="ui basic large label"
                                              title="{{ $inov['description'] }}"
                                        >
                                            {{ $inov['title'] }}
                                            <span class="detail">
                                                {{ $inov['value'] }}
                                            </span>
                                        </span>
                                    @endforeach

                                @endif

                            </div>

                            <div class="extra">
                                {{ trans('app.created_at') }}: {{ $instance->created_at->format('d.m.Y H:i') }}
                                &nbsp;

                                <a href="/operations/create/{{ $instance->id }}">
                                    <i class="cube icon"></i>
                                    {{ trans('app.do_add_operation') }}
                                </a>

                                <a href="/instances/{{ $instance->id }}/edit">
                                    <i class="edit outline icon"></i>
                                    {{ trans('app.do_edit') }}
                                </a>

                                <a data-popup="1">
                                    <i class="remove circle icon"></i>{{ trans('app.do_remove') }}
                                </a>
                                <div class="ui custom popup">
                                    <div class="ui huge header center aligned">{{ trans('app.q_delete') }}</div>
                                    <span class="ui negative button" data-action-name="remove"
                                        data-action="/instances/{{ $instance->id }}"
                                        data-method="DELETE">{{ trans('app.yes') }}
                                    </span>
                                    <span class="ui button">{{ trans('app.no') }}</span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ui segment @if($instance->is_archived) secondary @endif">
                @include('things.show._operations')
            </div>
        </div>
    @endforeach


    <div class="ui hidden divider"></div>
    <div class="ui hidden divider"></div>
    <div class="ui hidden divider"></div>
    <div class="ui hidden divider"></div>
@endsection
