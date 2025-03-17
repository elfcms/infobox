const items = Array.from(document.querySelectorAll('.infobox-line-position'));
const lineBoxes = document.querySelectorAll('.infobox-nav-line');

let draggedItem = null;
let draggedItemParent = null;

items.forEach(item => {

    let type = item.dataset.line ?? 'element';

    item.addEventListener('dragstart', () => {
        item.classList.add('dragging');
        itemBox = item.parentNode;
        draggedItem = itemBox;
        draggedItemParent = itemBox.parentNode;
    });

    item.addEventListener('dragend', () => {
        draggedItem = null;
        draggedItemParent = null;
        item.classList.remove('dragging');
        itemPositionSuccess(type);
    });

});

const lineContainers = Array.from(document.querySelectorAll('.infobox-nav-dnd-area'));

lineContainers.forEach(container => {
    container.addEventListener('dragover', e => {
        e.preventDefault();
        const afterElement = getDragAfterItem(container, e.clientY);
        if (draggedItem) {
            if (container.dataset.container == draggedItem.dataset.line) {
                container.insertBefore(draggedItem, afterElement);
            }
        }
    });
});

function getDragAfterItem(container, y) {
    const draggableElements = Array.from(container.querySelectorAll('.infobox-nav-line:not(.dragging)'));

    return draggableElements.reduce((closest, child) => {
        const box = child.getBoundingClientRect();
        const offset = y - box.top - box.height / 2;

        if (offset < 0 && offset > closest.offset) {
        return { offset: offset, element: child };
        } else {
        return closest;
        }
    },
    { offset: Number.NEGATIVE_INFINITY }).element;
}

let linesData = {};
let isChanged = false;

function itemPositionSuccess(type) {

    const preLines = Object.assign({}, linesData);

    let changeCount = 0;

    lineContainers.forEach(box => {

        const boxLines = box.querySelectorAll('.infobox-nav-line');
        let position = 1;
        if (boxLines) {
            boxLines.forEach(line => {
                if (line.dataset.id) {

                    linesData[line.dataset.id] = {
                        'position': position,
                        'new': false
                    };

                    let isNew = false;

                    if (preLines[line.dataset.id] !== undefined && linesData[line.dataset.id]  !== undefined) {
                        if (preLines[line.dataset.id].position != linesData[line.dataset.id].position) {
                            changeCount++;
                            isNew = true;
                        }
                    }
                    else if (preLines[line.dataset.id] === undefined && linesData[line.dataset.id]  !== undefined) {
                        changeCount++;
                        isNew = true;
                    }

                    linesData[line.dataset.id].new = isNew ? 1 : 0;


                    const positionBox = line.querySelector('.infobox-line-position');
                    if (positionBox) {
                        const positionValue = positionBox.querySelector("span");
                        if (positionValue) {
                            positionValue.innerText = position;
                        } else {
                            positionBox.innerHTML = position;
                        }
                    }
                    position++;
                }

            });
        }
    });

    if (changeCount > 0) {
        isChanged = true;
    }
    else {
        isChanged = false;
    }

    if (isChanged) {
        const data = JSON.stringify({
            lines: linesData,
        });
        const token = document.querySelector("input[name='_token']").value;

        fetch('/elfcms/api/infobox/' + type + '/lineorder',{
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': token,
            },
            credentials: 'same-origin',
            body: data
        }).then(
            (result) => result.json()
        ).then(data => {
            //
        })
        .catch(error => {
            //
        });
    }

}

