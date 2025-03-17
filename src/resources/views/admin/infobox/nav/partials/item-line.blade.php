<div class="infobox-nav-el infobox-nav-line" data-line="item" data-id="{{ $item->id }}">
    <div class="infobox-line-position" data-line="item" draggable="true">
        {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/drag_indicator_slim.svg', svg: true) !!}
        <span>{{ $item->position }}</span>
    </div>
    <span class="infobox-line-title" {{-- href="{{ route('admin.infobox.nav',['item'=>$item]) }}" --}}>{{ $item->title }}</span>
    <a href="{{ route('admin.infobox.items.edit',$item) }}" class="button icon-button">
        {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/edit.svg', svg: true) !!}
    </a>
    <form action="{{ route('admin.infobox.items.destroy',$item) }}" method="POST" data-submit="check" data-header="{{ __('infobox::default.deleting_of_infobox',['infobox'=>$item->title]) }}" data-message="{{ __('infobox::default.are_you_sure_to_deleting_infobox') }}">
        @csrf
        @method('DELETE')
        <input type="hidden" name="id" value="{{ $item->id }}">
        <input type="hidden" name="name" value="{{ $item->title }}">
        <button type="submit" class="button icon-button icon-alarm-button" title="{{ __('elfcms::default.delete') }}" >
            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/delete.svg', svg: true) !!}
        </button>
    </form>
</div>
