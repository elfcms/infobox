@if (!empty($moduleItemList))
    <div class="input-box colored" data-module="infobox">
        <label for="module_id">{{ __('infobox::default.infobox') }}</label>
        <div class="input-wrapper">
            @if (!empty($pageData) && !empty($pageData['module_id']))
                @foreach ($moduleItemList as $infobox)
                    @if ($pageData['module_id'] == $infobox->id)
                        <a href="{{ route('admin.infobox.nav',['infobox'=>$infobox]) }}">{{ $infobox->title }}</a>
                    @else
                        @continue
                    @endif
                @endforeach
            @else
                <select name="module_id" id="module_id" onchange="infoboxPageOptionInit(this)">
                    <option value=""> {{ __('elfcms::default.none') }} </option>
                    @foreach ($moduleItemList as $infobox)
                        <option value="{{ $infobox->id }}" data-slug="{{ $infobox->slug }}" @selected(!empty($pageData) && !empty($pageData['module_id']) && $pageData['module_id'] == $infobox->id)>
                            {{ $infobox->title }} </option>
                    @endforeach
                </select>
            @endif
        </div>
    </div>
@endif

@if (!empty($moduleTemplates))
    <datalist id="module_templates">
        @foreach ($moduleTemplates as $item)
            <option value="{{ $item }}"></option>
        @endforeach
    </datalist>
@endif

<div class="input-box colored" data-module="infobox">
    <label for="module_options_show_categories">{{ __('infobox::default.show_categories') }}</label>
    <div class="input-wrapper">
        <x-elfcms::ui.checkbox.switch name="module_options[show_categories]" id="module_options_show_categories"
            checked="{{ old('module_options.show_categories', $module_options['show_categories'] ?? false) ? true : false }}" />
    </div>
</div>

<div class="input-box colored" data-module="infobox">
    <label for="module_options_show_items">{{ __('infobox::default.show_items') }}</label>
    <div class="input-wrapper">
        <x-elfcms::ui.checkbox.switch name="module_options[show_items]" id="module_options_show_items"
            checked="{{ old('module_options.show_items', $module_options['show_items'] ?? false) ? true : false }}" />
    </div>
</div>

<div class="input-box colored" data-module="infobox">
    <label for="module_options_categories_depth">{{ __('infobox::default.nesting_depth_of_categories') }}</label>
    <div class="input-wrapper">
        <input type="number" name="module_options[categories_depth]" id="module_options_categories_depth"
            class="form-control"
            value="{{ old('module_options.categories_depth', $module_options['categories_depth'] ?? 1) }}"
            min="1" max="10">
    </div>
</div>

<div class="input-box colored" data-module="infobox">
    <label for="module_options_main_template">{{ __('infobox::default.main_page_template') }}</label>
    <div class="input-wrapper">
        <input list="module_templates" type="text" name="module_options[main_template]" id="module_options_main_template"
            class="form-control"
            value="{{ old('module_options.main_template', $module_options['main_template'] ?? 'elfcms.public.infobox.main') }}">
    </div>
</div>

<div class="input-box colored" data-module="infobox">
    <label for="module_options_category_template">{{ __('infobox::default.category_template') }}</label>
    <div class="input-wrapper">
        <input list="module_templates" type="text" name="module_options[category_template]" id="module_options_category_template"
            class="form-control"
            value="{{ old('module_options.category_template', $module_options['category_template'] ?? 'elfcms.public.infobox.category') }}">
    </div>
</div>

<div class="input-box colored" data-module="infobox">
    <label for="module_options_category_path">{{ __('infobox::default.category_page_path') }}</label>
    <div class="input-wrapper">
        <input type="text" name="module_options[category_path]" id="module_options_category_path"
            oninput="setCategoryPath(this)" class="form-control"
            value="{{ old('module_options.module_options_category_path', $module_options['category_path'] ?? '') }}">
    </div>
    <div class="input-wrapper" id="category_path_example">
        {infobox}/{{ !empty($module_options['category_path']) ? trim($module_options['category_path'], '/') . '/' : '' }}{category}
    </div>
</div>

<div class="input-box colored" data-module="infobox">
    <label for="module_options_item_template">{{ __('infobox::default.item_template') }}</label>
    <div class="input-wrapper">
        <input list="module_templates" type="text" name="module_options[item_template]" id="module_options_item_template"
            class="form-control"
            value="{{ old('module_options.item_template', $module_options['item_template'] ?? 'elfcms.public.infobox.item') }}">
    </div>
</div>

<div class="input-box colored" data-module="infobox">
    <label for="module_options_item_path">{{ __('infobox::default.item_page_path') }}</label>
    <div class="input-wrapper">
        <x-elfcms::ui.checkbox.small name="module_options[use_category_path]" id="module_options_use_category_path"
            oninput="setItemPath()" color="var(--info-color)" :checked="!empty($module_options['use_category_path'])" />
    </div>
    <span>{{ __('infobox::default.use_category_path') }}</span>
    <div class="input-wrapper">
        <input type="text" name="module_options[item_path]" id="module_options_item_path" oninput="setItemPath()"
            class="form-control"
            value="{{ old('module_options.module_options_item_path', $module_options['item_path'] ?? '') }}">
    </div>
    <div class="input-wrapper" id="item_path_example">
        {infobox}/{{ !empty($module_options['item_path']) ? trim($module_options['item_path'], '/') . '/' : (!empty($module_options['use_category_path']) ? (!empty($module_options['category_path']) ? trim($module_options['category_path'], '/') . '/' : '') . '{category}/' : 'items/') }}{item}
    </div>
</div>
