@extends('elfcms::admin.layouts.main')

@section('pagecontent')

    <div class="item-form">
        <h2>{{ __('infobox::default.edit_infobox',['infobox'=>$infobox->title]) }}</h2>
        <form action="{{ route('admin.infobox.infoboxes.update',$infobox) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="id" value="{{ $infobox->id }}">
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <label for="active">
                        {{ __('elfcms::default.active') }}
                    </label>
                    <x-elfcms::ui.checkbox.switch name="active" id="active" checked="{{ $infobox->active }}" />
                </div>
                <div class="input-box colored">
                    <label for="title">{{ __('elfcms::default.title') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="title" id="title" autocomplete="off" value="{{ $infobox->title }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="slug">{{ __('elfcms::default.slug') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="slug" id="slug" autocomplete="off" data-isslug value="{{ $infobox->slug }}">
                    </div>
                    <div class="input-wrapper">
                        <x-elfcms::ui.checkbox.autoslug textid="title" slugid="slug" checked />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="description">{{ __('elfcms::default.description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="description" id="description" cols="30" rows="2">{{ $infobox->description }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_keywords">{{ __('elfcms::default.meta_keywords') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_keywords" id="meta_keywords" cols="30" rows="2">{{ $infobox->meta_keywords }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_description">{{ __('elfcms::default.meta_description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_description" id="meta_description" cols="30" rows="2">{{ $infobox->meta_description }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label>
                        <a href="{{ route('admin.infobox.properties.category.index',['infobox'=>$infobox]) }}">{{ __('infobox::default.category_properties') }}</a>
                    </label>
                </div>
                <div class="input-box colored">
                    <label>
                        <a href="{{ route('admin.infobox.properties.item.index',['infobox'=>$infobox]) }}">{{ __('infobox::default.item_properties') }}</a>
                    </label>
                </div>
            </div>
            <div class="button-box single-box">
                <button type="submit" class="button color-text-button success-button">{{ __('elfcms::default.save') }}</button>
                <button type="submit" name="submit" value="save_and_close" class="button color-text-button info-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.infobox.nav',['infobox'=>$infobox]) }}" class="button color-text-button">{{ __('elfcms::default.cancel') }}</a>
            </div>
        </form>
    </div>
    <script>
    inputSlugInit()
    const imageInput = document.querySelector('#image')
    if (imageInput) {
        inputFileImg(imageInput)
    }


infoboxOptionInit();
    </script>

@endsection
