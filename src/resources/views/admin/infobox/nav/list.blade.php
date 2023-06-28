@forelse ($infoboxes as $ib)
<details  @class(['infobox-nav-ib','selected' => $ib->id == $infobox->id]) @if($ib->id == $infobox->id) open @endif>
    <summary>
        <a href="{{ route('admin.infobox.nav',['infobox'=>$ib]) }}">{{ $ib->title }}</a>
    </summary>
    {{-- @each('infobox::admin.infobox.nav.partials.detail',$ib->topCategories,'cat') --}}
    {{-- @if ($ib->topCategories) --}}
        {{-- @foreach ($ib->topCategories as $cat) --}}
        @forelse ($ib->topCategories as $cat)
            @include('infobox::admin.infobox.nav.partials.detail',['open' => $ib == $infobox ? 'open' : ''])
        {{-- @endforeach --}}
        @empty
            {{ __('infobox::elf.no_categories') }}
        @endforelse

    {{-- @endif --}}
</details>
@empty
    {{ __('basic::elf.nothing_was_found') }}
@endforelse
