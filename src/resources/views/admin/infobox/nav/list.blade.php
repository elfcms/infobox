@forelse ($infoboxes as $ib)
<details  @class(['infobox-nav-ib','selected' => $ib->id == $infobox->id]) @if($ib->id == $infobox->id) open @endif>
    <summary>
        <a href="{{ route('admin.infobox.nav',['infobox'=>$ib]) }}" class="infobox-nav-ib-link">{{ $ib->title }}</a>
        <a href="{{ route('admin.infobox.infoboxes.edit',$ib) }}" class="button icon-button">
            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/edit.svg', svg: true) !!}
        </a>
    </summary>
    {{-- @each('elfcms::admin.infobox.nav.partials.detail',$ib->topCategories,'cat') --}}
    {{-- @if ($ib->topCategories) --}}
        {{-- @foreach ($ib->topCategories as $cat) --}}
        @forelse ($ib->topCategories as $cat)
            @include('elfcms::admin.infobox.nav.partials.detail',['open' => $ib == $infobox ? 'open' : ''])
        {{-- @endforeach --}}
        @empty
            <div class="infobox-nav-nothing">{{ __('infobox::default.no_categories') }}</div>
        @endforelse

    {{-- @endif --}}
</details>
@empty
    <div class="infobox-nav-nothing">{{ __('elfcms::default.nothing_was_found') }}</div>
@endforelse
