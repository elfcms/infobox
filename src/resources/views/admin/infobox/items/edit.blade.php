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
        <h3>{{ __('basic::elf.edit_post') }}{{ $post->id }}</h3>
        <form action="{{ route('admin.blog.posts.update',$post->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="colored-rows-box">
                <input type="hidden" name="id" id="id" value="{{ $post->id }}">
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
                    <label for="category_id">{{ __('basic::elf.category') }}</label>
                    <div class="input-wrapper">
                        <select name="category_id" id="category_id">
                        @foreach ($categories as $item)
                            <option value="{{ $item->id }}" @if ($item->active != 1) class="inactive" @endif @if ($item->id == $post->category_id) selected @endif>{{ $item->name }}@if ($item->active != 1) [{{ __('basic::elf.inactive') }}] @endif</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="name">{{ __('basic::elf.name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" autocomplete="off" value="{{ $post->name }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="slug">{{ __('basic::elf.slug') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="slug" id="slug" autocomplete="off" value="{{ $post->slug }}">
                    </div>
                    <div class="input-wrapper">
                        <div class="autoslug-wrapper">
                            <input type="checkbox" data-text-id="name" data-slug-id="slug" class="autoslug" checked>
                            <div class="autoslug-button"></div>
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="desctiption">{{ __('basic::elf.description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="description" id="description" cols="30" rows="10">{{ $post->description }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="text">{{ __('basic::elf.text') }}</label>
                    <div class="input-wrapper">
                        <textarea name="text" id="text" cols="30" rows="10">{{ $post->text }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="preview">{{ __('basic::elf.preview') }}</label>
                    <div class="input-wrapper">
                        <input type="hidden" name="preview_path" id="preview_path" value="{{$post->preview}}">
                        <div class="image-button">
                            <div class="delete-image @if (empty($post->preview)) hidden @endif">&#215;</div>
                            <div class="image-button-img">
                            @if (!empty($post->preview))
                                <img src="{{ asset($post->preview) }}" alt="Preview">
                            @else
                                <img src="{{ asset('/vendor/elfcms/blog/admin/images/icons/upload.png') }}" alt="Upload file">
                            @endif
                            </div>
                            <div class="image-button-text">
                            @if (!empty($post->preview))
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
                        <input type="hidden" name="image_path" id="image_path" value="{{$post->image}}">
                        <div class="image-button">
                            <div class="delete-image @if (empty($post->image)) hidden @endif">&#215;</div>
                            <div class="image-button-img">
                            @if (!empty($post->image))
                                <img src="{{ asset($post->image) }}" alt="Image">
                            @else
                                <img src="{{ asset('/vendor/elfcms/blog/admin/images/icons/upload.png') }}" alt="Upload file">
                            @endif
                            </div>
                            <div class="image-button-text">
                            @if (!empty($post->image))
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
                        <input type="date" name="public_time[]" id="public_time" autocomplete="off" value="{{ $post->public_time }}">
                        <input type="time" name="public_time[]" id="public_time_time" autocomplete="off" value="{{ $post->public_time_time }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="end_time">{{ __('basic::elf.end_time') }}</label>
                    <div class="input-wrapper">
                        <input type="date" name="end_time[]" id="end_time" autocomplete="off" value="{{ $post->end_time }}">
                        <input type="time" name="end_time[]" id="end_time_time" autocomplete="off" value="{{ $post->end_time_time }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="tags">{{ __('basic::elf.tags') }}</label>
                    <div class="input-wrapper">
                        <div class="tag-form-wrapper">
                            <div class="tag-list-box">
                                @foreach ($post->tags as $tag)
                                <div class="tag-item-box" data-id="{{ $tag->id }}">
                                    <span class="tag-item-name">{{ $tag->name }}</span>
                                    <span class="tag-item-remove" onclick="removeTagFromList(this)">&#215;</span>
                                    <input type="hidden" name="tags[]" value="{{ $tag->id }}">
                                </div>
                                @endforeach
                            </div>
                            <div class="tag-input-box">
                                <input type="text" class="tag-input" autocomplete="off">
                                <button type="button" class="default-btn tag-add-button">Add</button>
                                <div class="tag-prompt-list"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_keywords">{{ __('basic::elf.meta_keywords') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_keywords" id="meta_keywords" cols="30" rows="3">{{ $post->meta_keywords }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_description">{{ __('basic::elf.meta_description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_description" id="meta_description" cols="30" rows="3">{{ $post->meta_description }}</textarea>
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

    tagFormInit()

    //add editor
    runEditor('#description')
    runEditor('#text')
    </script>

@endsection
