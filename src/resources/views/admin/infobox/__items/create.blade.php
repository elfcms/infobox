@extends('infobox::admin.layouts.infobox')

@section('infoboxpage-content')

    @if (Session::has('likeedited'))
        <div class="alert alert-success">{{ Session::get('likeedited') }}</div>
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
        <h3>{{ __('infobox::elf.create_item') }}</h3>
        <form action="{{ route('admin.infobox.items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <label for="code">{{ __('basic::elf.code') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="code" id="code" autocomplete="off" data-isslug>
                    </div>
                    <div class="input-wrapper">
                        <div class="autoslug-wrapper">
                            <input type="checkbox" data-text-id="title" data-slug-id="code" class="autoslug" checked>
                            <div class="autoslug-button"></div>
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="title">{{ __('basic::elf.title') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="title" id="title" autocomplete="off">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="text">{{ __('basic::elf.text') }}</label>
                    <div class="input-wrapper">
                        <textarea name="text" id="text" cols="30" rows="10"></textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="image">{{ __('basic::elf.image') }}</label>
                    <div class="input-wrapper">
                        <input type="hidden" name="image_path" id="image_path">
                        <div class="image-button">
                            <div class="delete-image hidden">&#215;</div>
                            <div class="image-button-img">
                                <img src="{{ asset('/vendor/elfcms/basic/admin/images/icons/upload.png') }}" alt="Upload file">
                            </div>
                            <div class="image-button-text">
                                {{ __('basic::elf.choose_file') }}
                            </div>
                            <input type="file" name="image" id="image">
                        </div>
                    </div>
                </div>
                <div class="input-box colored" id="optionsbox">
                    <label for="">{{ __('infobox::elf.options') }}</label>
                    <div class="input-wrapper">
                        <div>
                            <div class="sb-input-options-table">
                                <div class="options-table-head-line">
                                    <div class="options-table-head">
                                        {{ __('infobox::elf.type') }}
                                    </div>
                                    <div class="options-table-head">
                                        {{ __('basic::elf.name') }}
                                    </div>
                                    <div class="options-table-head">
                                        {{ __('basic::elf.value') }}
                                    </div>
                                    <div class="options-table-head">
                                        {{ __('basic::elf.delete') }}
                                    </div>
                                </div>
                                <div class="options-table-string-line" data-line="0">
                                    <div class="options-table-string">
                                        <select name="options_new[0][type]" id="option_new_type_0" data-option-type>
                                        @foreach ($data_types as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <div class="options-table-string">
                                        <input type="text" name="options_new[0][name]" id="option_new_name_0" data-option-name data-isslug>
                                    </div>
                                    <div class="options-table-string">
                                        <input type="text" name="options_new[0][value]" id="option_new_value_0" data-option-value>
                                    </div>
                                    <div class="options-table-string">
                                        <input type="checkbox" name="options_new[0][deleted]" id="option_new_disabled_0" data-option-deleted>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="default-btn option-table-add" id="addoptionline">{{ __('basic::elf.add_option') }}</button>
                        </div>
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
