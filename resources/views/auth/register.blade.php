@extends('template', [
    'title' => trans('app.registration').' :: '.config('app.name')
])

@section('content')
    <div class="ui container">
        <div class="ui centered stackable grid">
            <div class="six wide column">
                <div class="ui left aligned raised segment">

                    <h2 class="ui teal header">
                        <div class="content">
                            {{ trans('app.registration') }}
                        </div>
                    </h2>

                    @include('partials.error-message')

                    <form class="ui form" action="/register" method="POST">
                        <div class="field">
                            <div class="ui left icon input">
                                <input type="text" name="name" placeholder="{{ trans('app.name') }}" value="{{ old('name') }}">
                                <i class="user icon"></i>
                            </div>
                        </div>

                        <div class="field">
                            <div class="ui left icon input">
                                <input type="text" name="email" placeholder="Email" value="{{ old('email') }}">
                                <i class="mail icon"></i>
                            </div>
                        </div>

                        <div class="field">
                            <div class="ui left icon input">
                                <input type="password" name="password" placeholder="{{ trans('app.password') }}">
                                <i class="lock icon"></i>
                            </div>
                        </div>

                        <div class="field">
                            <div class="ui left icon input">
                                <input type="password" name="password_confirmation" placeholder="{{ trans('app.do_password_confirm')}}">
                                <i class="lock icon"></i>
                            </div>
                        </div>

                        {{ csrf_field() }}

                        <button class="ui basic large button" type="submit">
                            <i class="user icon"></i> {{ trans('app.do_register') }}
                        </button>
                    </form>
                </div>

                <div class="ui message">
                    <i class="key grey icon"></i><a href="/login">{{ trans('app.q_registered_already') }}</a>
                </div>

            </div>
        </div>
    </div>
@endsection