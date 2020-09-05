@extends('template', [
    'title' => trans('app.dashboard')
])

@section('content')
    <div class="container">
        <div class="ui container">
            <div class="ui hidden divider"></div>
            <div class="ui grid center aligned">
                <div class="row">
                    <div class="wide column">
                        <div class="ui raised segment">
                            <div class="ui huge header">
                                {{ trans('app.dashboard') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection