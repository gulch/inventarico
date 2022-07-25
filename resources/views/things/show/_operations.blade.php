<div class="ui feed">
    @foreach ($instance->operations()->orderBy('operated_at', 'desc')->get() as $operation)
        <div class="event">
            <div class="label">
                <i class="cube circular icon"></i>
            </div>
            <div class="content">

                <div class="summary">
                    {{ $operation->type->title }}
                    <div class="date">
                        {{ $operation->operated_at->format('d.m.Y H:i') }}
                    </div>
                </div>

                <div class="extra text">
                    @if($operation->condition !== 'NONE')
                        <div class="ui label">
                            {{ $operation->condition === 'NEW' ? trans('app.new') : trans('app.used') }}
                        </div>
                    @endif

                    @if($operation->price > 0)
                        @php
                        $operation_label_type = 'teal basic';

                        if ($operation->type->kind === 'profitable') {
                            $operation_label_type = 'green';
                        } elseif ($operation->type->kind === 'expenditure') {
                            $operation_label_type = 'red';
                        }
                        @endphp

                        <div class="ui {{ $operation_label_type }} label">
                            {{ $operation->price }} {{ $operation->currency }}
                        </div>
                    @endif
                </div>

                <div class="extra text operation-note">
                    {!! $operation->note !!}
                </div>

                @if (sizeof($operation->photos))
                    <div class="extra images gallery">
                        @foreach ($operation->photos as $photo)
                            <a href="{{ config('app.photo_image_upload_path') . $photo->path }}">
                                <img class="ui rounded image"
                                     src="{{ config('app.thumb_image_upload_path') . $photo->path }}"
                                >
                            </a>
                        @endforeach
                    </div>
                @endif

                <div class="meta">
                    {{ trans('app.created_at') }}: {{ $operation->created_at->format('d.m.Y H:i') }}
                    &nbsp;
                    <a href="/operations/{{ $operation->id }}/edit">
                        <i class="edit outline icon"></i>
                        {{ trans('app.do_edit') }}
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>
