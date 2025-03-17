@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="infobox-nav-container"  @if(!empty($pageConfig['second_color'])) style="--second-color:{{$pageConfig['second_color']}};" @endif>
        <div class="infobox-nav-box">
            <div class="infobox-nav-left">
                <div class="invobox-nav-leftbutton">
                    <a href="{{ route('admin.infobox.infoboxes.create') }}"
                        class="button round-button theme-button">
                        {!! iconHtmlLocal('elfcms/admin/images/icons/plus.svg', svg: true) !!}
                        <span>{{ __('infobox::default.create_infobox') }}</span>
                    </a>
                </div>
            </div>
            <div class="infobox-nav-right">
                <div class="infobox-nav-content">
                    @include('elfcms::admin.infobox.nav.partials.buttons')
                </div>
            </div>
        </div>
        <div class="infobox-nav-box infobox-nav-content-box">
            <div class="infobox-nav-left glass">
                @include('elfcms::admin.infobox.nav.list')
            </div>
            <div class="infobox-nav-right glass" id="infobox_nav_content">
                <div class="infobox-nav-content">
                    @include('elfcms::admin.infobox.nav.content')
                </div>
            </div>
        </div>
    </div>
    @if (!empty($message))
        @include('elfcms::admin.infobox.nav.partials.message')
    @endif
@endsection
