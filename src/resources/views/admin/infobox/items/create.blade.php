@extends('infobox::admin.layouts.infobox')

@section('infoboxpage-content')

    @if (Session::has('itemresult'))
        <div class="alert alert-success">{{ Session::get('itemresult') }}</div>
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
                                {{ __('basic::elf.active') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="infobox_id">{{ __('infobox::elf.infobox') }}</label>
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
                    <label for="category_id">{{ __('basic::elf.category') }}</label>
                    <div class="input-wrapper">
                        <select name="category_id" id="category_id">
                        @foreach ($categories as $item)
                        <option value="{{ $item->id }}" @class(['inactive'=>$item->active != 1, 'hidden' => $item->infobox->id != $firstInfobox->id]) data-id="{{ $item->infobox->id }}">{{ $item->title }}@if ($item->active != 1) [{{ __('basic::elf.inactive') }}] @endif</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="title">{{ __('basic::elf.title') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="title" id="title" autocomplete="off">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="slug">{{ __('basic::elf.slug') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="slug" id="slug" autocomplete="off">
                    </div>
                    <div class="input-wrapper">
                        <div class="autoslug-wrapper">
                            <input type="checkbox" data-text-id="title" data-slug-id="slug" class="autoslug" checked>
                            <div class="autoslug-button"></div>
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="desctiption">{{ __('basic::elf.description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="description" id="description" cols="30" rows="10"></textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="desctiption">{{ __('basic::elf.description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="description" id="description" cols="30" rows="10"></textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_keywords">{{ __('basic::elf.meta_keywords') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_keywords" id="meta_keywords" cols="30" rows="3" data-editor="quill"></textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_description">{{ __('basic::elf.meta_description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_description" id="meta_description" cols="30" rows="3"></textarea>
                    </div>
                </div>

                <div class="input-box colored">
                    <label for="tags">{{ __('basic::elf.tags') }}</label>
                    <div class="input-wrapper">
                        <div class="tag-form-wrapper">
                            <div class="tag-list-box"></div>
                            <div class="tag-input-box">
                                <input type="text" class="tag-input" autocomplete="off">
                                <button type="button" class="default-btn tag-add-button">Add</button>
                                <div class="tag-prompt-list"></div>
                            </div>
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
    const imageInput = document.querySelector('#image')
    if (imageInput) {
        inputFileImg(imageInput)
    }
    const previewInput = document.querySelector('#preview')
    if (previewInput) {
        inputFileImg(previewInput)
    }
    const infobox = document.querySelector('select[name="infobox_id"]');
    const parents = document.querySelector('select[name="category_id"]');
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
                                console.log(i);
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

    tagFormInit()
    //add editor
    runEditor('#description')
    runEditor('#text')
    </script>

@endsection
