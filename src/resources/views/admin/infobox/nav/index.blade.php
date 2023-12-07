@extends('elfcms::admin.layouts.nav')

@section('infoboxpage-content')

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
