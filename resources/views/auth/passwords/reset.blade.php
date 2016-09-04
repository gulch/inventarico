@extends('template', [
    'title' => 'Новый пароль :: ' . config('app.app.name')
])

@section('content')
    <div class="ui container">
        <div class="ui centered stackable grid">
            <div class="six wide column">
                <div class="ui left aligned segment">

                    <h2 class="ui teal header">
                        <div class="content">
                            Установка нового пароля
                        </div>
                    </h2>

                    @include('_partials.errorsmessage')

                    <form class="ui form" action="/password/reset" method="POST">
                        <div class="field">
                            <div class="ui left icon input">
                                <input type="text" name="email" placeholder="Email" value="{{ $email or old('email') }}">
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

                        <input type="hidden" name="token" value="{{ $token }}">

                        {{ csrf_field() }}

                        <button class="ui basic large button" type="submit">
                            <i class="undo icon"></i>
                            Установить
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection