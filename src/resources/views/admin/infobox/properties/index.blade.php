@extends('elfcms::admin.layouts.main')
@section('pagecontent')
<div class="pagenav">
    <a href="{{ route('admin.infobox.infoboxes.edit', $infobox) }}" class="button round-button theme-button">
        {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/arrow_back.svg', svg: true) !!}
        <span class="button-collapsed-text">{{ __('infobox::default.infobox') . ' "' . $infobox->title . '"' }}</span>
    </a>
</div>
<form name="propertyform" class="data-table-box" method="post" action="{{ route('admin.ajax.infobox.property.'.$type.'.fullsave',$infobox) }}">
    @csrf
    <input type="hidden" name="infobox_id" value="{{ $infobox->id }}">
    <div class="grid-table-wrapper option-table-wrapper">
        <table class="grid-table filestorage-group-table table-cols infobox-property-table" style="--first-col:60px; --last-col:7.5rem; --minw:50rem; --cols-count:7;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>{{ __('elfcms::default.name') }}</th>
                    <th></th>
                    <th>{{ __('elfcms::default.code') }}</th>
                    <th>{{ __('infobox::default.data_type') }}</th>
                    <th>{{ __('elfcms::default.description') }}</th>
                    {{-- <th>{{ __('infobox::default.is_filter') }}</th> --}}
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @include('elfcms::admin.infobox.properties.content.list')
            </tbody>
        </table>
    </div>
    <div class="dynamic-table-buttons">
        <button class="button round-button theme-button" title="{{__('infobox::default.add_property')}}" data-action="additem">
            {!! iconHtmlLocal('elfcms/admin/images/icons/plus.svg', svg: true) !!}
            <span class="button-collapsed-text">{{ __('infobox::default.add_property') }}</span>
        </button>
        {{-- <button class="button" title="{{__('elfcms::default.reset_button')}}" data-action="reset">{{ __('elfcms::default.reset_button') }}</button> --}}
        <button type="submit" class="button color-text-button success-button" disabled="" data-action="save">{{ __('elfcms::default.save') }}</button>
    </div>
</form>
    <script>
        const inputs = document.querySelectorAll('tr[data-id] td [data-name]');
        const addButton = document.querySelector('button[data-action="additem"]');
        const saveButton = document.querySelector('button[data-action="save"]');
        const form = document.querySelector('form[name="propertyform"]');
        let emptyItem;
        let unitListData;
        let controlData = {};
        let newItemId = 0;

        async function getEmptyItem() {
            let response = await fetch('{{ route("admin.ajax.infobox.property.".$type.".empty-item") }}',{headers: {'X-Requested-With': 'XMLHttpRequest'}});
            emptyItem = await response.text();
            return emptyItem;
        }

        async function getPropertyList () {
            if (unitListData !== null && typeof unitListData == 'object') {
                return unitListData;
            }
            let response = await fetch('{{ route("admin.ajax.infobox.property.".$type.".list",true) }}',{headers: {'X-Requested-With': 'XMLHttpRequest'}});
            unitListData = await response.json();
            return unitListData;
        }

        function showOptions(element) {
            if (typeof element === 'string') {
                element = document.querySelector(element);
            }
            if (!element || !(element instanceof HTMLSelectElement)) {
                return false;
            }
            const row = element.closest('tr[data-id="' + element.dataset.id + '"]');
            console.log(row)
            if (row) {
                const subrow = row.querySelector('.table-subrow');
                if (subrow) {
                    if (element.options[element.selectedIndex].dataset.code == 'list') {
                        subrow.classList.add('showed');
                    }
                    else {
                        subrow.classList.remove('showed');
                    }
                }
            }
        }

        function addOption(button,isnew=true) {
            if (typeof button === 'string') {
                button = document.querySelector(button);
            }
            if (!button || !(button instanceof HTMLElement)) {
                return false;
            }
            let newprop = 'new';
            if (!isnew) {
                newprop = '';
            }
            const table = button.closest('.infobox-option-table');
            //const parent = button.closest('tr[data-id]');
            if (table) {
                const box = table.querySelector('.infobox-option-table-body');
                if (box) {
                    let i = 0;
                    const rows = box.querySelectorAll('.infobox-option-table-row');
                    if (rows && rows.length && rows.length > 0) {
                        i = rows.length;
                    }
                    const rowString = `
                    <div class="infobox-option-table-row">
                        <div class="infobox-option-table-column">
                            <input type="text" name="${newprop}property[${button.dataset.id}][options][${i}][key]" value="" data-loop="${i}" data-name="key">
                        </div>
                        <div class="infobox-option-table-column">
                            <input type="text" name="${newprop}property[${button.dataset.id}][options][${i}][value]" value="" data-loop="${i}" data-name="value">
                        </div>
                        <div class="infobox-option-table-column">
                            <div class="small-checkbox" style="--switch-color: var(--danger-color)">
                                <input type="checkbox" name="${newprop}property[${button.dataset.id}][options][${i}][delete]" value="1" data-loop="${i}" data-name="delete">
                                <i></i>
                            </div>
                        </div>
                    </div>
                    `;
                    box.insertAdjacentHTML('beforeend',rowString);
                    setIBSaveEnabled(true)
                }
            }
        }

        let startPreload = preloadSet('form[name="propertyform"]');

        let dataLoadInterval = setInterval(() => {
            if (typeof unitListData === 'object') {
                for (let key in unitListData.data) {
                    let subdata = {};
                    for (let subkey in unitListData.data[key]){
                        subdata[subkey] = unitListData.data[key][subkey];
                    }
                    controlData[key] = subdata;
                }
                clearInterval(dataLoadInterval);
                preloadUnset(startPreload);
            }
        }, 1000);

        if (inputs) {
            inputs.forEach(input => {
                input.addEventListener('input',function(){
                    checkParamChange(this,true);
                });
                /* input.addEventListener('change',function(){
                    checkUnitChange(this);
                }); */
            });
        }

        if (addButton) {
            addButton.addEventListener('click',addIBPropertyItem);
        }

        if (form) {
            form.addEventListener('submit',function(e){
                e.preventDefault();

            });
        }

        if (saveButton) {
            saveButton.addEventListener('click',function(e){
                e.preventDefault();
                popup({
                    title: '{{__("infobox::default.are_you_sure")}}',
                    content: '{{__("infobox::default.do_you_want_to_save_your_changes")}}',
                    buttons:[
                        {
                            title:'OK',
                            class:'button color-text-button info-button',
                            callback: [
                                saveForm,
                                'close'
                            ]
                        },
                        {
                            title:'{{__("elfcms::default.cancel")}}',
                            class:'button color-text-button',
                            callback:'close'
                        }
                    ],
                    class:'submit'
                });
                function saveForm() {
                    const formData = new FormData(form);
                    const itemsBox = form.querySelector('tbody');
                    let preloader = preloadSet('.big-container');
                    fetch(form.action,{
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        credentials: 'same-origin',
                        body: formData
                    }).then(
                        (result) => result.json()
                    ).then (
                        (data) => {
                            if (data.result && data.result == 'success') {
                                itemsBox.innerHTML = '';
                                itemsBox.insertAdjacentHTML('beforeend',data.data);
                                const reInputs = itemsBox.querySelectorAll('tr[data-id] td [data-name]');
                                if (reInputs) {
                                    reInputs.forEach(input => {
                                        input.addEventListener('input',function(){
                                            checkParamChange(this,true);
                                        });
                                        /* input.addEventListener('change',function(){
                                            checkUnitChange(this);
                                        }); */
                                        autoSlug('.autoslug');
                                    });
                                }
                                popup({
                                    title: '{{__("infobox::default.done")}}',
                                    content: data.message,
                                    buttons:[
                                        {
                                            title:'OK',
                                            class:'button color-text-button info-button',
                                            callback:'close'
                                        }
                                    ],
                                    class:'submit'
                                });
                            }
                            else {
                                if ((data.error || (data.result && data.result == 'error'))) {
                                    if (!data.message) {
                                        data.message = '{{ __("infobox::default.error") }}';
                                    }
                                    popup({
                                        title:'{{__("infobox::default.error")}}',
                                        content:data.message,
                                        buttons:[
                                            {
                                                title:'OK',
                                                class:'button color-text-button danger-button',
                                                callback:'close'
                                            }
                                        ],
                                        class:'danger'
                                    });
                                }
                            }
                            preloadUnset(preloader);
                        }
                    ).catch(error => {
                        preloadUnset(preloader);
                    });
                }
            });
        }

        getPropertyList();
        getEmptyItem();
        autoSlug('.autoslug');

    </script>

@endsection
