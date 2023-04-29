@extends('infobox::admin.layouts.infobox')

@section('infoboxpage-content')

    @if (Session::has('infoboxresult'))
        <div class="alert alert-success">{{ Session::get('infoboxresult') }}</div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="errors-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="item-form">
        <h3>{{ __('infobox::elf.create_infobox') }}</h3>
        <form action="{{ route('admin.infobox.infobox.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="id" value="{{ $infobox->id }}">
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <div class="checkbox-wrapper">
                        <div class="checkbox-inner">
                            <input
                                type="checkbox"
                                name="active"
                                id="active"
                                @if ($post->active == 1)
                                checked
                                @endif
                            >
                            <i></i>
                            <label for="active">
                                {{ __('basic::elf.active') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="title">{{ __('basic::elf.title') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="title" id="title" autocomplete="off" value="{{ $infobox->title }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="slug">{{ __('basic::elf.slug') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="slug" id="slug" autocomplete="off" data-isslug value="{{ $infobox->slug }}">
                    </div>
                    <div class="input-wrapper">
                        <div class="autoslug-wrapper">
                            <input type="checkbox" data-text-id="title" data-slug-id="slug" class="autoslug" checked>
                            <div class="autoslug-button"></div>
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="description">{{ __('basic::elf.description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="description" id="description" cols="30" rows="2">{{ $infobox->description }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_keywords">{{ __('basic::elf.meta_keywords') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_keywords" id="meta_keywords" cols="30" rows="2">{{ $infobox->meta_keywords }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_description">{{ __('basic::elf.meta_description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_description" id="meta_description" cols="30" rows="2">{{ $infobox->meta_description }}</textarea>
                    </div>
                </div>
            </div>
            <div class="button-box single-box">
                <button type="submit" class="default-btn submit-button">{{ __('basic::elf.submit') }}</button>
            </div>
        </form>
    </div>
    <script>
    autoSlug('.autoslug')
    inputSlugInit()
    const imageInput = document.querySelector('#image')
    if (imageInput) {
        inputFileImg(imageInput)
    }


infoboxOptionInit();
    </script>

@endsection
