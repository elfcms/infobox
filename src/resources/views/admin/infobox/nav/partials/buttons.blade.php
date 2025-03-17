<div class="infobox-nav-topbuttons">
    @if (!empty($infobox->id))
    <a href="{{ route('admin.infobox.categories.create',['infobox'=>$infobox,'category_id'=>$category->id??null]) }}" class="button round-button theme-button">
        {!! iconHtmlLocal('elfcms/admin/images/icons/plus.svg', svg: true) !!}
        <span>{{ __('infobox::default.add_category') }}</span>
    </a>
    <a href="{{ route('admin.infobox.items.create',['infobox'=>$infobox,'category_id'=>$category->id ?? null]) }}" class="button round-button theme-button">
        {!! iconHtmlLocal('elfcms/admin/images/icons/plus.svg', svg: true) !!}
        <span>{{ __('infobox::default.add_item') }}</span>
    </a>
    @endif
</div>
