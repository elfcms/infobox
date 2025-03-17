<div class="infobox-nav-cat infobox-nav-line" data-id="{{ $cat->id }}" data-line="category">
    <div class="infobox-line-position" data-line="category" draggable="true">
        {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/drag_indicator_slim.svg', svg: true) !!}
        <span>{{ $cat->position }}</span>
    </div>
    <a class="infobox-line-title" href="{{ route('admin.infobox.nav',['infobox'=>$infobox,'category'=>$cat]) }}">{{ $cat->title }}</a>
    <a href="{{ route('admin.infobox.categories.edit',$cat) }}" class="button icon-button">
        {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/edit.svg', svg: true) !!}
    </a>
    <form action="{{ route('admin.infobox.categories.destroy',$cat) }}" method="POST" data-submit="check" data-header="{{ __('infobox::default.deleting_of_infobox',['infobox'=>$cat->title]) }}" data-message="{{ __('infobox::default.are_you_sure_to_deleting_infobox') }}">
        @csrf
        @method('DELETE')
        <input type="hidden" name="id" value="{{ $cat->id }}">
        <input type="hidden" name="name" value="{{ $cat->title }}">
        <button type="submit" class="button icon-button icon-alarm-button" title="{{ __('elfcms::default.delete') }}" >
            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/delete.svg', svg: true) !!}
        </button>
    </form>
</div>
