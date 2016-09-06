@extends('template', [
    'title' => trans('app.password_recovery').' :: '.config('app.name')
])

@section('content')
    <div class="ui container">
        <div class="ui centered stackable grid">
            <div class="six wide column">
                <div class="ui left aligned segment">

                    <h2 class="ui teal header">
                        <div class="content">
                            {{ trans('app.password_recovery') }}
                        </div>
                    </h2>

                    @if(session('status'))
                        <div class="ui icon success message">
                            <i class="check icon"></i>
                            <div class="content">
                                {{ session('status') }}
                            </div>
                        </div>
                    @endif

                    @include('partials.error-message')

                    <form class="ui form" action="/password/email" method="POST">
                        <div class="field">
                            <div class="ui left icon input">
                                <input type="text"
                                       name="email"
                                       placeholder="Email"
                                       value="{{ old('email') }}"
                                >
                                <i class="mail icon"></i>
                            </div>
                        </div>

                        {{ csrf_field() }}

                        <button class="ui basic large button" type="submit">
                            <i class="undo icon"></i>
                            {{ trans('app.do_recovery') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
