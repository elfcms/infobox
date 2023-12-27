@forelse ($infoboxes as $ib)
<details  @class(['infobox-nav-ib','selected' => $ib->id == $infobox->id]) @if($ib->id == $infobox->id) open @endif>
    <summary>
        <a href="{{ route('admin.infobox.nav',['infobox'=>$ib]) }}">{{ $ib->title }}</a>
        <a href="{{ route('admin.infobox.infoboxes.edit',$ib) }}" class="inline-button circle-button alternate-button transparent-button"></a>
    </summary>
    {{-- @each('elfcms::admin.infobox.nav.partials.detail',$ib->topCategories,'cat') --}}
    {{-- @if ($ib->topCategories) --}}
        {{-- @foreach ($ib->topCategories as $cat) --}}
        @forelse ($ib->topCategories as $cat)
            @include('elfcms::admin.infobox.nav.partials.detail',['open' => $ib == $infobox ? 'open' : ''])
        {{-- @endforeach --}}
        @empty
            <div class="infobox-nav-list-none">{{ __('infobox::default.no_categories') }}</div>
        @endforelse

    {{-- @endif --}}
</details>
@empty
    {{ __('elfcms::default.nothing_was_found') }}
@endforelse
