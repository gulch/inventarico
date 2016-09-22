<div class="ui four cards">
    @if (sizeof($photos))
        @foreach($photos as $img)
            <div class="ui card">
                <div class="image pointer" data-image-id="{{ $img->id }}">
                    <img class="lazyload"
                         src="/{{ config('app.assets_img_path') }}/placeholder-white-175x130.svg"
                         data-src="{{ config('app.thumb_image_upload_path') . $img->path }}"
                    >
                </div>
                <div class="content">
                    <div class="description">
                        {{ $img->description }}
                    </div>
                </div>
            </div>
        @endforeach
    @else
        @include('partials.nothing-found')
    @endif
</div>