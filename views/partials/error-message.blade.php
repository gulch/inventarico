@if ($errors->any())
    <div class="ui icon warning message">
        <i class="warning sign icon"></i>
        <div class="content">
            <div class="header">
                {{ trans('app.errors_happens') }}:
            </div>
            <ul class="list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif