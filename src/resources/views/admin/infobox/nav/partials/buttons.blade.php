<div class="infobox-nav-topbuttons">
    @if (!empty($infobox->id))
        <a href="{{ route('admin.infobox.categories.create', ['infobox' => $infobox, 'category_id' => $category->id ?? null]) }}"
            class="button round-button theme-button">
            {!! iconHtmlLocal('elfcms/admin/images/icons/plus.svg', svg: true) !!}
            <span>{{ __('infobox::default.add_category') }}</span>
        </a>
        <a href="{{ route('admin.infobox.items.create', ['infobox' => $infobox, 'category_id' => $category->id ?? null]) }}"
            class="button round-button theme-button">
            {!! iconHtmlLocal('elfcms/admin/images/icons/plus.svg', svg: true) !!}
            <span>{{ __('infobox::default.add_item') }}</span>
        </a>
        @if (empty($category))
            @empty($page)
            <form action="{{ route('admin.infobox.create-page', $infobox) }}" method="post">
                @method('POST')
                @csrf
                <button class="button round-button theme-button">
                    {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/page_add.svg', svg: true) !!}
                    <span>{{ __('elfcms::default.create_page') }}</span>
                </button>
            </form>
            @else
            <a href="{{ route('admin.page.pages.edit', $page) }}"
                class="button round-button theme-button">
                {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/page_edit.svg', svg: true) !!}
                <span>{{ __('elfcms::default.edit_page') }}</span>
            </a>
            @endempty
        @endif
    @endif
</div>
