@extends('template', [
    'title' => 'Авторизация :: ' . config('app.name')
])

@section('content')
    <div class="ui container">
        <div class="ui centered stackable grid">
            <div class="six wide column">
                <div class="ui left aligned segment">

                    <h2 class="ui teal header">
                        <div class="content">
                            Авторизация
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
                                <input type="password" name="password" placeholder="Пароль">
                                <i class="lock icon"></i>
                            </div>
                        </div>

                        <div class="field">
                            <div class="ui checkbox">
                                <input name="remember" type="checkbox" tabindex="0" class="hidden">
                                <label>Запомнить меня</label>
                            </div>
                        </div>

                        {{ csrf_field() }}

                        <button class="ui basic large button" type="submit">
                            Войти <i class="sign in icon"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection