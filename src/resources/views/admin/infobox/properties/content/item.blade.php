<tr data-id="{{ $property->id }}">
    <td>
        <span>{{ $property->id }}</span>
        <input type="hidden" name="property[{{$property->id}}][edited]" value="0">
    </td>
    <td>
        <input type="text" name="property[{{$property->id}}][name]" id="property_{{$property->id}}_name" value="{{ $property->name }}" data-name="name">
    </td>
    <td>
        <div class="input-wrapper">
            <div class="autoslug-wrapper">
                <input type="checkbox" data-text-id="property_{{$property->id}}_name" data-slug-id="property_{{$property->id}}_code" class="autoslug" checked>
                <div class="autoslug-button"></div>
            </div>
        </div>
    </td>
    <td>
        <input type="text" name="property[{{$property->id}}][code]" id="property_{{$property->id}}_code" value="{{ $property->code }}" data-name="code">
    </td>
    <td>
        <select name="property[{{$property->id}}][data_type_id]" id="property_{{$property->id}}_data_type_id" data-name="data_type_id" data-id="{{$property->id}}" onchange="showOptions(this)">
            @foreach ($data_types as $data_type)
            <option value="{{ $data_type->id }}" data-code="{{ $data_type->code }}" @if ($property->data_type_id==$data_type->id) selected @endif>{{ $data_type->name }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <input type="text" name="property[{{$property->id}}][description]" id="property_{{$property->id}}_description" value="{{ $property->description }}" data-name="description">
    </td>
    {{-- <td>
        <div class="checkbox-switch green">
            <input type="checkbox" name="property[{{$property->id}}][is_filter]" id="property_{{$property->id}}_is_filter" value="1" data-name="is_filter" @if($property->is_filter) checked @endif>
            <i></i>
        </div>
    </td> --}}
    <td class="button-column non-text-buttons">
        <div class="check-delete-wrapper">
            <input type="checkbox" name="property[{{$property->id}}][delete]" id="property_{{$property->id}}_delete" value="1" data-id="{{ $property->id }}" title="{{ __('elfcms::default.delete') }}" onclick="setDynamicUnitRowDelete(this)">
            <i></i>
        </div>
    </td>

    <td @class(['table-subrow','showed' => $property->data_type->code=='list'])>
        <div class="infobox-option-box">
            <div class="infobox-option-multiple-line">
                <div class="checkbox-switch green" data-column="multiple">
                    <input type="checkbox" name="property[{{$property->id}}][multiple]" id="property_{{$property->id}}_multiple" value="1" data-name="multiple" @if($property->multiple) checked @endif>
                    <i></i>
                </div>
                <label for="property_{{$property->id}}_multiple">{{ __('infobox::default.multiple') }}</label>
            </div>
            <div class="infobox-option-box-label">
                {{ __('infobox::default.options') }}
            </div>
            <div class="infobox-option-table">
                <div class="infobox-option-table-head">
                    <div class="infobox-option-table-column">
                        {{ __('infobox::default.key') }}
                    </div>
                    <div class="infobox-option-table-column">
                        {{ __('infobox::default.value') }}
                    </div>
                    <div class="infobox-option-table-column">
                        {{ __('elfcms::default.delete') }}
                    </div>
                </div>
                <div class="infobox-option-table-body">
                @if (!empty($property->options))
                    @foreach ($property->options as $key => $value)
                    <div class="infobox-option-table-row">
                        <div class="infobox-option-table-column">
                            <input type="text" name="property[{{$property->id}}][options][{{$loop->index}}][key]" value="{{ $key }}" oninput="checkOptionChange(this)" data-loop="{{$loop->index}}" data-name="key">
                        </div>
                        <div class="infobox-option-table-column">
                            <input type="text" name="property[{{$property->id}}][options][{{$loop->index}}][value]" value="{{ $value }}" oninput="checkOptionChange(this)" data-loop="{{$loop->index}}" data-name="value">
                        </div>
                        <div class="infobox-option-table-column">
                            <div class="checkbox-switch red">
                                <input type="checkbox" name="property[{{$property->id}}][options][{{$loop->index}}][delete]" value="1" oninput="checkOptionChange(this)" data-loop="{{$loop->index}}" data-name="delete">
                                <i></i>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
                </div>
                <div class="infobox-option-table-add">
                    <button class="default-btn" data-id="{{$property->id}}" onclick="addOption(this{{!$property->code ? '' : ',false' }})">{{ __('elfcms::default.add_option') }}</button>
                </div>
            </div>
        </div>
    </td>
</tr>
