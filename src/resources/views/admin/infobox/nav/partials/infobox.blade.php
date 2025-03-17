<div class="infobox-nav-infobox infobox-nav-line" data-id="{{ $ib->id }}">
    <div class="infobox-line-position" data-line="infobox" draggable="true">
        {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/drag_indicator_slim.svg', svg: true) !!}
        <span>{{ $ib->position }}</span>
    </div>
    <a class="infobox-line-title" href="{{ route('admin.infobox.nav',['infobox'=>$ib]) }}">{{ $ib->title }}</a>
    <a href="{{ route('admin.infobox.infoboxes.edit',$ib) }}" class="button icon-button">
        {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/edit.svg', svg: true) !!}
    </a>
    <form action="{{ route('admin.infobox.infoboxes.destroy',$ib) }}" method="POST" data-submit="check" data-header="{{ __('infobox::default.deleting_of_infobox',['infobox'=>$ib->title]) }}" data-message="{{ __('infobox::default.are_you_sure_to_deleting_infobox') }}">
        @csrf
        @method('DELETE')
        <input type="hidden" name="id" value="{{ $ib->id }}">
        <input type="hidden" name="name" value="{{ $ib->title }}">
        <button type="submit" class="button icon-button icon-alarm-button" title="{{ __('elfcms::default.delete') }}" >
            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/delete.svg', svg: true) !!}
        </button>
    </form>
</div>
