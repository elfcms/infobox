<details @class(['infobox-nav-cat', 'empty'=>$cat->categories->count()==0, 'selected' => (!empty($category) && $cat->id == $category->id)]) @if((!empty($category) && $cat->id == $category->id) || (!empty($category) && in_array($cat->id, $category->parentsID()))) open @endif>
    <summary>
        <a href="{{ route('admin.infobox.nav',['infobox'=>$cat->infobox,'category'=>$cat]) }}">{{$cat->title}}</a>
    </summary>
@if ($cat->categories)
    {{-- @each('infobox::admin.infobox.nav.partials.detail',$cat->categories,'cat') --}}
    @foreach ($cat->categories as $cat)
        @include('infobox::admin.infobox.nav.partials.detail')
    @endforeach
@endif
</details>
