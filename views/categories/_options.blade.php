@foreach($items as $item)
    <option value="{{ $item->id }}"
            @if($parent_category === $item->id) selected @endif
            @if(isset($category) && $category->id == $item->id) disabled @endif
    >{!! $depth ? ' ' . str_repeat('&nbsp;&nbsp;', $depth) . ' ' : '' !!}{{ $item->title }}</option>
    @if($item->hasChildren())
        @include('categories._options', ['items' => $item->getChildren(), 'depth' => $depth + 1])
    @endif
@endforeach
