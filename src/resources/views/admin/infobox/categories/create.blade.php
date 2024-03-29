@extends('elfcms::admin.layouts.infobox')

@section('infoboxpage-content')

    @if (Session::has('categoryresult'))
        <div class="alert alert-success">{{ Session::get('categoryresult') }}</div>
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
        <h3>{{ __('elfcms::default.create_category') }}</h3>
        <form action="{{ route('admin.infobox.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <div class="checkbox-wrapper">
                        <div class="checkbox-inner">
                            <input
                                type="checkbox"
                                name="active"
                                id="active"
                                checked
                            >
                            <i></i>
                            <label for="active">
                                {{ __('elfcms::default.active') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="infobox_id">{{ __('infobox::default.infobox') }}</label>
                    <div class="input-wrapper">
                    @if (!empty($currentInfobox))
                        #{{ $currentInfobox->id }} {{ $currentInfobox->title }}
                        <input type="hidden" name="infobox_id" value="{{ $currentInfobox->id }}">
                    @else
                        <select name="infobox_id" id="infobox_id">
                        @foreach ($infoboxes as $infobox)
                            <option value="{{ $infobox->id }}" data-id="{{ $infobox->id }}">{{ $infobox->title }}</option>
                        @endforeach
                        </select>
                    @endif
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="parent_id">{{ __('elfcms::default.parent') }}</label>
                    <div class="input-wrapper">
                        <select name="parent_id" id="parent_id">
                            <option value="">{{ __('elfcms::default.none') }}</option>
                        @foreach ($categories as $item)
                            <option value="{{ $item->id }}" @class(['inactive'=>$item->active != 1, 'hidden' => $item->infobox->id != $firstInfobox->id]) data-id="{{ $item->infobox->id }}" @if (!empty($category_id) && $item->id == $category_id) selected @endif>{{ $item->title }}@if ($item->active != 1) [{{ __('elfcms::default.inactive') }}] @endif</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="title">{{ __('elfcms::default.title') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="title" id="title">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="slug">{{ __('elfcms::default.slug') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="slug" id="slug">
                    </div>
                    <div class="input-wrapper">
                        <div class="autoslug-wrapper">
                            <input type="checkbox" data-text-id="title" data-slug-id="slug" class="autoslug" checked>
                            <div class="autoslug-button"></div>
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="desctiption">{{ __('elfcms::default.description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="description" id="description" cols="30" rows="10"></textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="position">{{ __('elfcms::default.position') }}</label>
                    <div class="input-wrapper">
                        <input type="number" name="position" id="position">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_keywords">{{ __('elfcms::default.meta_keywords') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_keywords" id="meta_keywords" cols="30" rows="3"></textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_description">{{ __('elfcms::default.meta_description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_description" id="meta_description" cols="30" rows="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="button-box single-box">
                <button type="submit" class="default-btn submit-button">{{ __('elfcms::default.submit') }}</button>
                <button type="submit" name="submit" value="save_and_close" class="default-btn alternate-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.infobox.nav',['infobox'=>$currentInfobox,'category'=>$curentCategory]) }}" class="default-btn">{{ __('elfcms::default.cancel') }}</a>
            </div>
        </form>
    </div>
    <script>
    const imageInput = document.querySelector('#image')
    if (imageInput) {
        inputFileImg(imageInput)
    }
    const previewInput = document.querySelector('#preview')
    if (previewInput) {
        inputFileImg(previewInput)
    }
    const infobox = document.querySelector('select[name="infobox_id"]');
    const parents = document.querySelector('select[name="parent_id"]');
    if (infobox && parents) {
        const options = parents.querySelectorAll('option');
        if (options) {
            infobox.addEventListener('change',function(){
                const current = infobox.options[infobox.selectedIndex];
                if (current) {
                    let id = current.dataset.id;
                    let selected = false;
                    options.forEach((option, i) => {
                        if (option.dataset.id == id) {
                            if (!selected) {
                                parents.selectedIndex = i;
                                selected = true;
                            }
                            option.classList.remove('hidden');
                        }
                        else {
                            option.classList.add('hidden');
                        }
                    });
                }
            });
        }
    }
    autoSlug('.autoslug')
    //add editor
    runEditor('#description')
    </script>

@endsection
