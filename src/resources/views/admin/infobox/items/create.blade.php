@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="item-form">
        <h2>{{ __('infobox::default.create_item') }}</h2>
        <form action="{{ route('admin.infobox.items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <label for="active">
                        {{ __('elfcms::default.active') }}
                    </label>
                    <x-elfcms::ui.checkbox.switch name="active" id="active" checked="true" />
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
                                    <option value="{{ $infobox->id }}" data-id="{{ $infobox->id }}">{{ $infobox->title }}
                                    </option>
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
                                <option value="{{ $item->id }}" @class([
                                    'inactive' => $item->active != 1,
                                    'hidden' => $item->infobox->id != $firstInfobox->id,
                                ])
                                    data-id="{{ $item->infobox->id }}" @if (!empty($category_id) && $item->id == $category_id) selected @endif>
                                    {{ $item->title }}@if ($item->active != 1)
                                        [{{ __('elfcms::default.inactive') }}]
                                    @endif
                                </option>
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
                        <x-elfcms::ui.checkbox.autoslug textid="title" slugid="slug" checked />
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
                                <button type="button" class="button tag-add-button">Add</button>
                                <div class="tag-prompt-list"></div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
            @if ($properties->count())
                <div class="colored-rows-box">
                    <h4> {{ __('infobox::default.properties') }} </h4>
                    @foreach ($properties as $property)
                        <div class="input-box colored">
                            <label>{{ $property->name }}</label>
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
                                        id="property_{{ $property->id }}" />
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
                <a href="{{ route('admin.infobox.nav', ['infobox' => $currentInfobox, 'category' => $curentCategory]) }}"
                    class="button color-text-button">{{ __('elfcms::default.cancel') }}</a>
            </div>
        </form>
    </div>
    <script>
        const infobox = document.querySelector('select[name="infobox_id"]');
        const parents = document.querySelector('select[name="category_id"]');
        if (infobox && parents) {
            const options = parents.querySelectorAll('option');
            if (options) {
                infobox.addEventListener('change', function() {
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
                            } else {
                                option.classList.add('hidden');
                            }
                        });
                    }
                });
            }
        }
        //tagFormInit()
        //add editor
        runEditor('#description')
    </script>

@endsection
