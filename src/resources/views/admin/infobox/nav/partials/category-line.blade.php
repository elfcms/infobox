<div class="infobox-nav-cat infobox-nav-line" data-id="{{ $cat->id }}" data-line="category">
    <div class="infobox-line-position" data-line="category" draggable="true">{{ $cat->position }}</div>
    <a class="infobox-line-title" href="{{ route('admin.infobox.nav',['infobox'=>$infobox,'category'=>$cat]) }}">{{ $cat->title }}</a>
    <a href="{{ route('admin.infobox.categories.edit',$cat) }}" class="inline-button circle-button alternate-button"></a>
    <form action="{{ route('admin.infobox.infoboxes.destroy',$cat) }}" method="POST" data-submit="check" data-header="{{ __('infobox::default.deleting_of_infobox',['infobox'=>$cat->title]) }}" data-message="{{ __('infobox::default.are_you_sure_to_deleting_infobox') }}">
        @csrf
        @method('DELETE')
        <input type="hidden" name="id" value="{{ $cat->id }}">
        <input type="hidden" name="name" value="{{ $cat->title }}">
        <button type="submit" class="inline-button circle-button delete-button" title="{{ __('elfcms::default.delete') }}" ></button>
    </form>
</div>
