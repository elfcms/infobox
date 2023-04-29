@extends('basic::admin.layouts.basic')

@section('pagecontent')

<div class="big-container">

    <nav class="pagenav">
        <ul>
            <li>
                <a href="{{ route('admin.infobox.infobox.index') }}" class="button button-left">{{ __('infobox::elf.infoboxes') }}</a>
                <a href="{{ route('admin.infobox.infobox.create') }}" class="button button-right">+</a>
            </li>
            <li>
                <a href="{{ route('admin.infobox.categories') }}" class="button button-left">{{ __('basic::elf.categories') }}</a>
                <a href="{{ route('admin.infobox.categories.create') }}" class="button button-right">+</a>
            </li>
            <li>
                <a href="{{ route('admin.infobox.items') }}" class="button button-left">{{ __('infobox::elf.items') }}</a>
                <a href="{{ route('admin.infobox.items.create') }}" class="button button-right">+</a>
            </li>
        </ul>
    </nav>
    @section('infoboxpage-content')
    @show

</div>
@endsection
