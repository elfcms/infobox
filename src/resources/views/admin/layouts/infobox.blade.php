@extends('basic::admin.layouts.basic')

@section('pagecontent')

<div class="big-container">

    <nav class="pagenav">
        <ul>
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
