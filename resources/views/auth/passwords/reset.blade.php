@extends('template', [
    'title' => trans('app.password_reset').' :: '.config('app.app.name')
])

@section('content')
    <div class="ui container">
        <div class="ui centered stackable grid">
            <div class="six wide column">
                <div class="ui left aligned segment">

                    <h2 class="ui teal header">
                        <div class="content">
                            {{ trans('app.password_reset') }}
                        </div>
                    </h2>

                    @include('partials.error-message')

                    <form class="ui form" action="/password/reset" method="POST">
                        <div class="field">
                            <div class="ui left icon input">
                                <input type="text" name="email" placeholder="Email" value="{{ $email or old('email') }}">
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
                                <input type="password" name="password_confirmation" placeholder="{{ trans('app.do_password_confirm') }}">
                                <i class="lock icon"></i>
                            </div>
                        </div>

                        <input type="hidden" name="token" value="{{ $token }}">

                        {{ csrf_field() }}

                        <button class="ui basic large button" type="submit">
                            <i class="undo icon"></i>
                            {{ trans('app.do_set') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection