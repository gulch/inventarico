@extends('template', [
    'title' => trans('app.authorization').' :: '.config('app.name')
])

@section('content')
    <div class="ui container">
        <div class="ui centered stackable grid">
            <div class="six wide column">
                <div class="ui left aligned raised segment">

                    <h2 class="ui teal header">
                        <div class="content">
                            {{ trans('app.authorization') }}
                        </div>
                    </h2>

                    @include('partials.error-message')

                    <form class="ui form" action="/login" method="POST">
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
                            <div class="ui checkbox">
                                <input name="remember" type="checkbox" tabindex="0" class="hidden">
                                <label>{{ trans('app.remember_me') }}</label>
                            </div>
                        </div>

                        {{ csrf_field() }}

                        <button class="ui basic large button" type="submit">
                            {{ trans('app.do_login') }} <i class="sign in icon"></i>
                        </button>
                    </form>
                </div>

                <div class="ui message">
                    <i class="user grey icon"></i><a href="/register">{{ trans('app.do_register') }}?</a>
                    <br>
                    <i class="unlock grey icon"></i><a href="/password/reset">{{ trans('app.q_forgot_password') }}</a>
                </div>

            </div>
        </div>
    </div>
@endsection