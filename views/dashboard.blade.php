@extends('template', [
    'title' => trans('app.dashboard'),
])

@section('content')
    <div class="ui hidden divider"></div>

    <div class="ui two column centered relaxed grid">
        <div class="center aligned column">
            <div class="ui huge statistic">
                <div class="value">
                    {{ number_format($active_instances_sum, 2, '.', ' ') }}
                </div>
                <div class="label">
                    {!! trans('app.active_instances_sum') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="ui hidden divider"></div>
        </div>

        <div class="four column row">
            <div class="center aligned column">
                <div class="ui orange large statistic">
                    <div class="value">
                        {{ $active_things_count }}
                    </div>
                    <div class="label">
                        {!! trans('app.active_things_count') !!}
                    </div>
                </div>
            </div>
            <div class="center aligned column">
                <div class="ui grey large statistic">
                    <div class="value">
                        {{ $archived_things_count }}
                    </div>
                    <div class="label">
                        {!! trans('app.archived_things_count') !!}
                    </div>
                </div>
            </div>
            <div class="center aligned column">
                <div class="ui large green statistic">
                    <div class="value">
                        {{ $active_instances_count }}
                    </div>
                    <div class="label">
                        {!! trans('app.active_instances_count') !!}
                    </div>
                </div>
            </div>
            <div class="center aligned column">
                <div class="ui large teal statistic">
                    <div class="value">
                        {{ $archived_instances_count }}
                    </div>
                    <div class="label">
                        {!! trans('app.archived_instances_count') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
