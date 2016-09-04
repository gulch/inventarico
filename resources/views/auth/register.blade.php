@extends('template', [
    'title' => 'Регистрация :: ' . config('app.name')
])

@section('content')
    <div class="ui container">
        <div class="ui centered stackable grid">
            <div class="six wide column">
                <div class="ui left aligned segment">

                    <h2 class="ui teal header">
                        <div class="content">
                            Регистрация
                        </div>
                    </h2>

                    @include('partials.error-message')

                    <form class="ui form" action="/register" method="POST">
                        <div class="field">
                            <div class="ui left icon input">
                                <input type="text" name="name" placeholder="Имя" value="{{ old('name') }}">
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
                                <input type="password" name="password" placeholder="Пароль">
                                <i class="lock icon"></i>
                            </div>
                        </div>

                        <div class="field">
                            <div class="ui left icon input">
                                <input type="password" name="password_confirmation" placeholder="Подтвердите пароль">
                                <i class="lock icon"></i>
                            </div>
                        </div>

                        {{ csrf_field() }}

                        <button class="ui basic large button" type="submit">
                            <i class="user icon"></i> Зарегистрироваться
                        </button>
                    </form>
                    <div class="ui message">
                        <i class="key grey icon"></i><a href="/login">Уже зарегистрированы?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection