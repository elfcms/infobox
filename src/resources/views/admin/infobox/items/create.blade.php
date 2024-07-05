@extends('elfcms::admin.layouts.infobox')

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
        <h3>{{ __('infobox::default.create_item') }}</h3>
        <form action="{{ route('admin.infobox.items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <x-elfcms-input-checkbox code="active" label="{{ __('elfcms::default.active') }}" style="blue" checked="1" />
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
                    <label for="category_id">{{ __('elfcms::default.category') }}</label>
                    <div class="input-wrapper">
                        <select name="category_id" id="category_id">
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
                        <input type="text" name="title" id="title" autocomplete="off">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="slug">{{ __('elfcms::default.slug') }}</label>
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
                    <label for="desctiption">{{ __('elfcms::default.description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="description" id="description" cols="30" rows="10"></textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="public_time">{{ __('elfcms::default.public_time') }}</label>
                    <div class="input-wrapper">
                        <input type="date" name="public_time[]" id="public_time" autocomplete="off">
                        <input type="time" name="public_time[]" id="public_time_time" autocomplete="off">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="end_time">{{ __('elfcms::default.end_time') }}</label>
                    <div class="input-wrapper">
                        <input type="date" name="end_time[]" id="end_time" autocomplete="off">
                        <input type="time" name="end_time[]" id="end_time_time" autocomplete="off">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_keywords">{{ __('elfcms::default.meta_keywords') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_keywords" id="meta_keywords" cols="30" rows="3" data-editor="quill"></textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_description">{{ __('elfcms::default.meta_description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_description" id="meta_description" cols="30" rows="3"></textarea>
                    </div>
                </div>

                {{-- <div class="input-box colored">
                    <label for="tags">{{ __('elfcms::default.tags') }}</label>
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
                </div> --}}
            </div>
            @if($properties->count())
            <div class="colored-rows-box">
                <h4> {{ __('infobox::default.properties') }} </h4>
                @foreach ($properties as $property)
                <div class="input-box colored">
                    <label>{{ $property->name }}</label>
                    <div class="input-wrapper">
                        @if ($property->data_type->code == 'text' || $property->data_type->code == 'json')
                        <textarea name="property[{{$property->id}}]" id="property_{{$property->id}}">{{ $property->value }}</textarea>
                        <script>
                            runEditor('#property_{{$property->id}}')
                        </script>
                        @elseif ($property->data_type->code == 'list')
                        <select name="property[{{$property->id}}]" id="property_{{$property->id}}" @if($property->multiple) multiple @endif>
                            <option value="">{{ __('elfcms::default.none') }}</option>
                            @if (!empty($property->options))
                            @foreach ($property->options as $value => $text)
                            <option value="{{$value}}">{{$text}}</option>
                            @endforeach
                            @endif
                        </select>
                        @elseif ($property->data_type->code == 'image')
                        <x-elfcms-input-image-alt inputName="property[{{$property->id}}][image]" valueName="property[{{$property->id}}][path]" valueId="property_{{$property->id}}_path" value="" download="1" />
                        @elseif ($property->data_type->code == 'file')
                        <x-elfcms-input-file code="property[{{$property->id}}]" value="" download="1" />
                        @elseif ($property->data_type->code == 'bool')
                        <input type="checkbox" name="property[{{$property->id}}]" id="property_{{$property->id}}" @if ($property->value == 1) checked @endif value="1">
                        @else
                        <input type="{{ $property->data_type->field[0] }}" name="property[{{$property->id}}]" id="property_{{$property->id}}" value="">
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif
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
    </script>

@endsection
