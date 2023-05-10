@extends('infobox::admin.layouts.infobox')

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
        <h3>{{ __('basic::elf.edit_category') }}{{ $category->id }}</h3>
        <div class="date-info create-info">
            {{ __('basic::elf.created_at') }}: {{ $category->created }}
        </div>
        <div class="date-info update-info">
            {{ __('basic::elf.updated_at') }}: {{ $category->updated }}
        </div>
        <form action="{{ route('admin.infobox.categories.update',$category) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="colored-rows-box">
                <input type="hidden" name="id" id="id" value="{{ $category->id }}">
                <div class="input-box colored">
                    <div class="checkbox-wrapper">
                        <div class="checkbox-inner">
                            <input
                                type="checkbox"
                                name="active"
                                id="active"
                                @if ($category->active == 1)
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
                    <label for="infobox_id">{{ __('infobox::elf.infobox') }}</label>
                    <div class="input-wrapper">
                        #{{ $category->infobox->id }} {{ $category->infobox->title }}
                        <input type="hidden" name="infobox_id" value="{{ $category->infobox->id }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="parent_id">{{ __('basic::elf.parent') }}</label>
                    <div class="input-wrapper">
                        <select name="parent_id" id="parent_id">
                            <option value="">{{ __('basic::elf.none') }}</option>
                        @foreach ($categories as $item)
                            @if ($item->id != $category->id)
                                <option value="{{ $item->id }}" @if ($item->active != 1) class="inactive" @endif @if ($item->id == $category->parent_id) selected @endif>{{ $item->title }}@if ($item->active != 1) [{{ __('basic::elf.inactive') }}] @endif</option>
                            @endif
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="title">{{ __('basic::elf.title') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="title" id="title" autocomplete="off" value="{{ $category->title }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="slug">{{ __('basic::elf.slug') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="slug" id="slug" autocomplete="off" value="{{ $category->slug }}">
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
                        <textarea name="description" id="description" cols="30" rows="10">{{ $category->description }}</textarea>
                    </div>
                </div>
                {{-- <div class="input-box colored">
                    <label for="preview">{{ __('basic::elf.preview') }}</label>
                    <div class="input-wrapper">
                        <input type="hidden" name="preview_path" id="preview_path" value="{{$category->preview}}">
                        <div class="image-button">
                            <div class="delete-image @if (empty($category->preview)) hidden @endif">&#215;</div>
                            <div class="image-button-img">
                            @if (!empty($category->image))
                                <img src="{{ asset($category->preview) }}" alt="Preview">
                            @else
                                <img src="{{ asset('/vendor/elfcms/blog/admin/images/icons/upload.png') }}" alt="Upload file">
                            @endif
                            </div>
                            <div class="image-button-text">
                            @if (!empty($category->image))
                                {{ __('basic::elf.change_file') }}
                            @else
                                {{ __('basic::elf.choose_file') }}
                            @endif
                            </div>
                            <input type="file" name="preview" id="preview">
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="image">{{ __('basic::elf.image') }}</label>
                    <div class="input-wrapper">
                        <input type="hidden" name="image_path" id="image_path" value="{{$category->image}}">
                        <div class="image-button">
                            <div class="delete-image @if (empty($category->image)) hidden @endif">&#215;</div>
                            <div class="image-button-img">
                            @if (!empty($category->image))
                                <img src="{{ asset($category->image) }}" alt="Image">
                            @else
                                <img src="{{ asset('/vendor/elfcms/blog/admin/images/icons/upload.png') }}" alt="Upload file">
                            @endif
                            </div>
                            <div class="image-button-text">
                            @if (!empty($category->image))
                                {{ __('basic::elf.change_file') }}
                            @else
                                {{ __('basic::elf.choose_file') }}
                            @endif
                            </div>
                            <input type="file" name="image" id="image">
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="public_time">{{ __('basic::elf.public_time') }}</label>
                    <div class="input-wrapper">
                        <input type="date" name="public_time[]" id="public_time" autocomplete="off" value="{{ $category->public_time }}">
                        <input type="time" name="public_time[]" id="public_time_time" autocomplete="off" value="{{ $category->public_time_time }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="end_time">{{ __('basic::elf.end_time') }}</label>
                    <div class="input-wrapper">
                        <input type="date" name="end_time[]" id="end_time" autocomplete="off" value="{{ $category->end_time }}">
                        <input type="time" name="end_time[]" id="end_time_time" autocomplete="off" value="{{ $category->end_time_time }}">
                    </div>
                </div> --}}
                <div class="input-box colored">
                    <label for="meta_keywords">{{ __('basic::elf.meta_keywords') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_keywords" id="meta_keywords" cols="30" rows="3">{{ $category->meta_keywords }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_description">{{ __('basic::elf.meta_description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_description" id="meta_description" cols="30" rows="3">{{ $category->meta_description }}</textarea>
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

    autoSlug('.autoslug')
    //add editor
    runEditor('#description')
    </script>

@endsection
