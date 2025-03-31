<div class="infobox-nav-item">
    <a href="{{ route('admin.infobox.items.edit',$item) }}">{{ $item->title }}</a>
    <div class="infobox-nav-buttons">
        <a href="{{ route('admin.infobox.items.edit',$item) }}" class="infobox-nav-button edit" title="{{ __('infobox::default.edit_item') }}"></a>
        <form action="{{ route('admin.infobox.items.destroy',$item) }}" method="POST" data-submit="check" data-header="{{ __('infobox::default.deleting_of_item',['item'=>$item->title]) }}" data-message="{{ __('infobox::default.are_you_sure_to_deleting_item') }}">
            @csrf
            @method('DELETE')
            <input type="hidden" name="id" value="{{ $item->id }}">
            <input type="hidden" name="name" value="{{ $item->title }}">
            <button type="submit" class="infobox-nav-button delete" title="{{ __('elfcms::default.delete') }}" ></button>
        </form>
        <div class="contextmenu-content-box">
            <a href="{{ route('admin.infobox.items.edit',$item) }}" class="contextmenu-item">{{ __('infobox::default.edit_item') }}</a>
            <form action="{{ route('admin.infobox.items.destroy',$item) }}" method="POST" data-submit="check" data-header="{{ __('infobox::default.deleting_of_item',['item'=>$item->title]) }}" data-message="{{ __('infobox::default.are_you_sure_to_deleting_item') }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id" value="{{ $item->id }}">
                <input type="hidden" name="name" value="{{ $item->title }}">
                <button type="submit" class="contextmenu-item">{{ __('elfcms::default.delete') }}</button>
            </form>
        </div>
    </div>
</div>
