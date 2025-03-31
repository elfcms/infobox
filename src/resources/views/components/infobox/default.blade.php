@if (!empty($infobox->items))
    @foreach ($infobox->items as $item)
        @if ($item)
        <div>
        @if (!empty($item->image))
            <img src="{{ $item->image }}" alt="{{ $item->title }}" width="100%">
        @endif
            <h4>{{ $item->title }}</h4>
            <p>{{ $item->text }}</p>
        </div>
        @endif
    @endforeach
@endif
