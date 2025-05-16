@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="item-form">
        <h2>{{ __('elfcms::default.edit_category') }}{{ $category->id }}</h2>
        <div class="date-info create-info">
            {{ __('elfcms::default.created_at') }}: {{ $category->created }}
        </div>
        <div class="date-info update-info">
            {{ __('elfcms::default.updated_at') }}: {{ $category->updated }}
        </div>
        <form action="{{ route('admin.infobox.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="colored-rows-box">
                <input type="hidden" name="id" id="id" value="{{ $category->id }}">
                <div class="input-box colored">
                    <label for="active">
                        {{ __('elfcms::default.active') }}
                    </label>
                    <x-elfcms::ui.checkbox.switch name="active" id="active" checked="{{ $category->active }}" />
                </div>
                <div class="input-box colored">
                    <label for="infobox_id">{{ __('infobox::default.infobox') }}</label>
                    <div class="input-wrapper">
                        #{{ $category->infobox->id }} {{ $category->infobox->title }}
                        <input type="hidden" name="infobox_id" value="{{ $category->infobox->id }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="parent_id">{{ __('elfcms::default.parent') }}</label>
                    <div class="input-wrapper">
                        <select name="parent_id" id="parent_id">
                            <option value="">{{ __('elfcms::default.none') }}</option>
                            @foreach ($categories as $item)
                                @if ($item->id != $category->id)
                                    <option value="{{ $item->id }}"
                                        @if ($item->active != 1) class="inactive" @endif
                                        @if ($item->id == $category->parent_id) selected @endif>{{ $item->title }}@if ($item->active != 1)
                                            [{{ __('elfcms::default.inactive') }}]
                                        @endif
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="title">{{ __('elfcms::default.title') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="title" id="title" autocomplete="off"
                            value="{{ $category->title }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="slug">{{ __('elfcms::default.slug') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="slug" id="slug" autocomplete="off"
                            value="{{ $category->slug }}">
                    </div>
                    <div class="input-wrapper">
                        <x-elfcms::ui.checkbox.autoslug textid="title" slugid="slug" checked />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="desctiption">{{ __('elfcms::default.description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="description" id="description" cols="30" rows="10">{{ $category->description }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="position">{{ __('elfcms::default.position') }}</label>
                    <div class="input-wrapper">
                        <input type="number" name="position" id="position" value="{{ $category->position }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_keywords">{{ __('elfcms::default.meta_keywords') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_keywords" id="meta_keywords" cols="30" rows="3">{{ $category->meta_keywords }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_description">{{ __('elfcms::default.meta_description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_description" id="meta_description" cols="30" rows="3">{{ $category->meta_description }}</textarea>
                    </div>
                </div>
            </div>

            @if ($properties->count())
                <div class="colored-rows-box">
                    <h3> {{ __('infobox::default.properties') }} </h3>
                    @foreach ($properties as $property)
                        <div class="input-box colored">
                            <label for="property_{{ $property->id }}">{{ $property->name }}</label>
                            <div class="input-wrapper">
                                @if ($property->data_type->code == 'text' || $property->data_type->code == 'json')
                                    <textarea name="property[{{ $property->id }}]" id="property_{{ $property->id }}">{{ $property->value }}</textarea>
                                    <script>
                                        runEditor('#property_{{ $property->id }}')
                                    </script>
                                @elseif ($property->data_type->code == 'list')
                                    <select @if ($property->multiple) name="property[{{ $property->id }}][]" @else name="property[{{ $property->id }}]" @endif id="property_{{ $property->id }}"
                                        @if ($property->multiple) multiple @endif>
                                        <option value="">{{ __('elfcms::default.none') }}</option>
                                        @if (!empty($property->options))
                                            @foreach ($property->options as $value => $text)
                                                <option value="{{ $value }}"
                                                    @if (is_array($property->value) && in_array($value, $property->value)) selected @endif>{{ $text }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                @elseif ($property->data_type->code == 'image' || $property->data_type->code == 'file')
                                    <x-elf-input-file :params="[
                                        'name' => 'property[' . $property->id . '][image]',
                                        'id' => 'property_' . $property->id . '_path',
                                        'value' => $property->value,
                                        'value_name' => 'property[' . $property->id . '][path]',
                                    ]" />
                                @elseif ($property->data_type->code == 'bool')
                                    <x-elfcms::ui.checkbox.switch name="property[{{ $property->id }}]"
                                        id="property_{{ $property->id }}" checked="{{ $property->value == 1 }}" />
                                @else
                                    <input type="{{ $property->data_type->field[0] }}"
                                        name="property[{{ $property->id }}]" id="property_{{ $property->id }}"
                                        value="{{ $property->value }}">
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="button-box single-box">
                <button type="submit"
                    class="button color-text-button success-button">{{ __('elfcms::default.save') }}</button>
                <button type="submit" name="submit" value="save_and_close"
                    class="button color-text-button info-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.infobox.nav', ['infobox' => $item->category, 'category' => $category->parent]) }}"
                    class="button color-text-button">{{ __('elfcms::default.cancel') }}</a>
            </div>
        </form>
    </div>
    <script>
        //add editor
        runEditor('#description')
    </script>

@endsection
