{{-- <div class="infobox-nav-category">
    <a href="{{ route('admin.infobox.categories.edit',$cat) }}">{{ $cat->title }}</a>
</div> --}}
<details class="infobox-nav-category">
    <summary>
        <a href="{{ route('admin.infobox.nav',['infobox'=>$infobox,'category'=>$cat]) }}">{{$cat->title}}</a>
        <div class="infobox-nav-buttons">
            <a href="{{ route('admin.infobox.items.create',['category_id'=>$cat->id]) }}" class="infobox-nav-button add-item" title="{{ __('infobox::default.add_item') }}"></a>
            <a href="{{ route('admin.infobox.categories.create',['category_id'=>$cat->id]) }}" class="infobox-nav-button add-category" title="{{ __('infobox::default.add_category') }}"></a>
            <a href="{{ route('admin.infobox.categories.edit',$cat) }}" class="infobox-nav-button edit" title="{{ __('infobox::default.edit_category') }}"></a>
            <form action="{{ route('admin.infobox.categories.destroy',$cat) }}" method="POST" data-submit="check" data-header="{{ __('infobox::default.deleting_of_category',['category'=>$cat->title]) }}" data-message="{{ __('infobox::default.are_you_sure_to_deleting_category') }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id" value="{{ $cat->id }}">
                <input type="hidden" name="name" value="{{ $cat->title }}">
                <button type="submit" class="infobox-nav-button delete" title="{{ __('elfcms::default.delete') }}"></button>
            </form>
            <div class="contextmenu-content-box">
                <a href="{{ route('admin.infobox.items.create',['category_id'=>$cat->id]) }}" class="contextmenu-item" title="{{ __('infobox::default.add_item') }}"></a>
                <a href="{{ route('admin.infobox.categories.create',['category_id'=>$cat->id]) }}" class="contextmenu-item" title="{{ __('infobox::default.add_category') }}"></a>
                <a href="{{ route('admin.infobox.categories.edit',$cat) }}" class="contextmenu-item" title="{{ __('infobox::default.edit_category') }}"></a>
                <form action="{{ route('admin.infobox.categories.destroy',$cat) }}" method="POST" data-submit="check" data-header="{{ __('infobox::default.deleting_of_category',['category'=>$cat->title]) }}" data-message="{{ __('infobox::default.are_you_sure_to_deleting_category') }}">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" value="{{ $cat->id }}">
                    <input type="hidden" name="name" value="{{ $cat->title }}">
                    <button type="submit" class="contextmenu-item" title="{{ __('elfcms::default.delete') }}"></button>
                </form>
            </div>
        </div>
    </summary>
@if ($cat->categories)
    @foreach ($cat->categories as $cat)
        @include('elfcms::admin.infobox.nav.partials.category',['items'=>$cat->items])
    @endforeach
@endif
@if ($items)
    @foreach ($items as $item)
        @include('elfcms::admin.infobox.nav.partials.item')
    @endforeach
@endif
</details>
