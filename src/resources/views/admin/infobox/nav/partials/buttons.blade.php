<div class="infobox-nav-topbuttons">
    @if (!empty($infobox->id))
    <a href="{{ route('admin.infobox.categories.create',['infobox'=>$infobox,'category_id'=>$category->id??null]) }}" class="default-btn success-button icon-text-button light-icon plus-button">
        {{ __('infobox::default.add_category') }}
    </a>
    <a href="{{ route('admin.infobox.items.create',['infobox'=>$infobox,'category_id'=>$category->id ?? null]) }}" class="default-btn success-button icon-text-button light-icon plus-button">
        {{ __('infobox::default.add_item') }}
    </a>
    @endif
</div>
