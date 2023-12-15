@extends('elfcms::admin.layouts.nav')

@section('infoboxpage-content')
<div class="infobox-nav-box">
    <div class="infobox-nav-left">
        <div class="invobox-nav-leftbutton">
            <a href="{{ route('admin.infobox.infoboxes.create') }}" class="default-btn success-button icon-text-button light-icon plus-button">
                {{ __('infobox::default.create_infobox') }}
            </a>
        </div>
    </div>
    <div class="infobox-nav-right">
        <div class="infobox-nav-content">
            @include('elfcms::admin.infobox.nav.partials.buttons')
        </div>
    </div>
</div>
<div class="infobox-nav-box">
    <div class="infobox-nav-left">
        @include('elfcms::admin.infobox.nav.list')
    </div>
    <div class="infobox-nav-right" id="infobox_nav_content">
        @include('elfcms::admin.infobox.nav.content')
    </div>
</div>
@if (!empty($message))
    @include('elfcms::admin.infobox.nav.partials.message')
@endif
@endsection
