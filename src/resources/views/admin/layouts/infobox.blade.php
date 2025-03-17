@extends('elfcms::admin.layouts.main')

@section('pagecontent')

<div class="big-container">

    <nav class="pagenav">
        <ul>
            <li>
                <a href="{{ route('admin.infobox.infoboxes') }}" class="button button-left">{{ __('infobox::default.infoboxes') }}</a>
                <a href="{{ route('admin.infobox.infoboxes.create') }}" class="button button-right">+</a>
            </li>
            <li>
                <a href="{{ route('admin.infobox.categories') }}" class="button button-left">{{ __('elfcms::default.categories') }}</a>
                <a href="{{ route('admin.infobox.categories.create') }}" class="button button-right">+</a>
            </li>
            <li>
                <a href="{{ route('admin.infobox.items') }}" class="button button-left">{{ __('infobox::default.items') }}</a>
                <a href="{{ route('admin.infobox.items.create') }}" class="button button-right">+</a>
            </li>
        </ul>
    </nav>
    @section('pagecontent')
    @show

</div>
@endsection
