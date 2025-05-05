let elfIBDataTypes = null;
async function getSBDataTypes() {
    if (elfIBDataTypes !== null && typeof elfIBDataTypes == "object") {
        return elfIBDataTypes;
    }
    let elfIBDataTypesResponse = await fetch(
        "/admin/ajax/json/infobox/datatypes",
        { headers: { "X-Requested-With": "XMLHttpRequest" } }
    );
    elfIBDataTypes = await elfIBDataTypesResponse.json();
    return elfIBDataTypes;
}
getSBDataTypes();

function infoboxOptionInit(addSelector = "#addoptionline", line = 0) {
    optionNextLine = line;

    const addButton = document.querySelector(addSelector);
    if (addButton) {
        addButton.addEventListener("click", function () {
            const lastLine = document.querySelector(
                '.options-table-string-line[data-line="' + optionNextLine + '"]'
            );
            optionNextLine++;
            let optionList = "";
            elfIBDataTypes.forEach((option) => {
                optionList += `<option value="${option.id}">${option.name}</option>`;
            });
            const htmlLine = `
            <div class="options-table-string-line" data-line="${optionNextLine}">
                <div class="options-table-string">
                    <select name="options_new[${optionNextLine}][type]" id="option_new_type_${optionNextLine}" data-option-type>
                        ${optionList}
                    </select>
                </div>
                <div class="options-table-string">
                    <input type="text" name="options_new[${optionNextLine}][name]" id="option_new_name_${optionNextLine}" data-option-name data-isslug>
                </div>
                <div class="options-table-string">
                    <input type="text" name="options_new[${optionNextLine}][value]" id="option_new_value_${optionNextLine}" data-option-value>
                </div>
                <div class="options-table-string">
                    <input type="checkbox" name="options_new[${optionNextLine}][deleted]" id="option_new_disabled_${optionNextLine}" data-option-deleted>
                </div>
            </div>
            `;
            if (lastLine) {
                lastLine.insertAdjacentHTML("afterend", htmlLine);
            }
        });
    }
}

function isIBEditedUnits() {
    const editedRows = document.querySelectorAll("tr[data-id].edited");
    if (editedRows && editedRows.length) {
        return true;
    }
    return false;
}

function isIBDeletableUnits() {
    const editedRows = document.querySelectorAll("tr[data-id].deletable");
    if (editedRows && editedRows.length) {
        return true;
    }
    return false;
}

function setIBSaveEnabled(enable = null) {
    const saveButton = document.querySelector('button[data-action="save"]');
    if (saveButton) {
        if (isIBDeletableUnits() || isIBEditedUnits() || enable === true) {
            saveButton.disabled = false;
        } else {
            saveButton.disabled = true;
        }
    }
}

function addIBPropertyItem() {
    if (!emptyItem) return false;
    if (!newItemId && newItemId !== 0) return false;
    const container = document.querySelector(
        "table.infobox-property-table tbody"
    );
    if (container) {
        let itemString = emptyItem
            .replaceAll(
                "property[newproperty]",
                "newproperty[new_" + newItemId + "]"
            )
            .replaceAll(
                'data-id="newproperty"',
                'data-id="new_' + newItemId + '"'
            )
            .replaceAll('id="newproperty"', 'id="new_' + newItemId + '"')
            .replaceAll("property_newproperty", "newproperty_new_" + newItemId)
            .replaceAll("<span>newproperty</span>", "");
        container.insertAdjacentHTML("beforeend", itemString);
        const newRow = container.lastElementChild;
        if (newRow) {
            newRow.classList.add("edited");
        }
        newItemId++;
        autoSlug(newRow.querySelectorAll(".autoslug"));
        setIBSaveEnabled();
    }
}

function infoboxPageOptionInit(select) {
    const nameInput = document.querySelector('input[name="name"]');
    const slugInput = document.querySelector('input[name="slug"]');
    if (select) {
        /* if (select.value && slugInput) {
            slugInput.value = select.value;
        } */
        if (select.selectedOptions.length && select.selectedOptions[0]) {
            if (nameInput) nameInput.value = select.selectedOptions[0].label;
            if (slugInput && select.selectedOptions[0].dataset.slug) {
                slugInput.value = select.selectedOptions[0].dataset.slug;
            }
        }
    }
}

function setCategoryPath(input) {
    const exampleBox = document.getElementById("category_path_example");
    if (exampleBox) {
        let value = input.value;
        const infobox = '{infobox}/';
        const category = '{category}';
        value = value.replace(/^\/|\/$/g, '');
        if (value !== '') value += '/';
        exampleBox.innerText = infobox + value + category;
        setItemPath()
    }
}


function setItemPath() {
    const exampleBox = document.getElementById("item_path_example");
    const pathInput = document.getElementById("module_options_item_path");
    const catInput = document.getElementById("module_options_use_category_path");
    const catPathInput = document.getElementById("module_options_category_path");
    if (exampleBox && pathInput) {
        let value = pathInput.value;
        let catValue = catPathInput.value;
        const infobox = '{infobox}/';
        const category = '{category}/';
        const item = '{item}';
        value = value.replace(/^\/|\/$/g, '');
        if (value !== '') value += '/';
        catValue = catValue.replace(/^\/|\/$/g, '');
        if (catValue !== '') catValue += '/';
        let result = infobox;
        if (catInput && catInput.checked) {
            result += catValue + category;
        }
        else if (value === '') value = 'items/';
        result += value + item;
        exampleBox.innerText = result;
    }
}
