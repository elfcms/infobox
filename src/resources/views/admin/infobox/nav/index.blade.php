@extends('infobox::admin.layouts.nav')

@section('infoboxpage-content')

<div class="infobox-nav-box">
    <div class="infobox-nav-left">
        @include('infobox::admin.infobox.nav.list')
    </div>
    <div class="infobox-nav-right" id="infobox_nav_content">
        @include('infobox::admin.infobox.nav.content')
    </div>
</div>
@if (!empty($message))
    @include('infobox::admin.infobox.nav.partials.message')
@endif
@endsection
