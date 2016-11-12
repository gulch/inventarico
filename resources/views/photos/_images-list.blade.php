<div class="ui four doubling cards">
    @if(sizeof($photos))
        @foreach($photos as $photo)
            <div class="card segment">
                <a href="{{ config('app.photo_image_upload_path') . $photo->path }}"
                   class="image text-centered lightbox"
                >
                    <img src="{{ config('app.thumb_image_upload_path') . $photo->path }}">
                </a>

                <div class="content">
                    <div class="description">
                        {{ $photo->description }}
                    </div>
                </div>

                <div class="extra content">
                    <span>
                        <i class="history icon"></i>
                        {{ $photo->created_at->format('d.m.Y H:i:s') }}
                    </span>
                    <span>
                        <i class="history icon"></i>
                        {{ $photo->created_at->format('d.m.Y H:i:s') }}
                    </span>
                </div>

                <div class="extra">
                    <a href="/photos/{{ $photo->id }}/edit">
                        <i class="edit icon"></i>{{ trans('app.do_edit') }}
                    </a>
                    <a data-popup="1">
                        <i class="remove circle icon"></i>{{ trans('app.do_remove') }}
                    </a>
                    <div class="ui custom popup">
                        <div class="ui huge header center aligned">{{ trans('app.q_delete') }}</div>
                        <span class="ui negative button"
                              data-action-name="remove"
                              data-action="/photos/{{ $photo->id }}"
                              data-method="DELETE">{{ trans('app.yes') }}
                        </span>
                        <span class="ui button">{{ trans('app.no') }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        @include('partials.nothing-found')
    @endif
</div>