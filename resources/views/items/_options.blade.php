@foreach($items as $item)
    <option value="{{ $item->id }}"
            @if($selected_category == $item->id) selected @endif
    >{{ $depth ? ' ' . str_repeat('–', $depth) . ' ' : '' }}{{ $item->title }}</option>
    @if($item->hasChildren())
        @include('items._options', ['items' => $item->getChildren(), 'depth' => $depth + 1])
    @endif
@endforeach
