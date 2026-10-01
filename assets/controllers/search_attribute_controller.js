import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['add', 'checkbox', 'radio'];

    static values = {
        url: String,
        fieldUrl: String
    }

    search(e) {
        clearTimeout(this.timeout);
        const { value } = e.target;
        this.timeout = setTimeout(() => {

            Turbo.visit(this.urlValue + '?q=' + value, {
                frame: 'search-result'
            });

        }, 500);
    }


    toggleAdd() {
        let hasChecked = false;

        for (let e of this.checkboxTargets) {
            if (e.checked) {
                hasChecked = true;
                break;
            }
        }

        if (hasChecked) {
            this.addTarget.classList.remove('d-none');

        } else {
            this.addTarget.classList.add('d-none');
        }
    }
    showAdd() {
        this.addTarget.classList.remove('d-none');
    }





    addAttribute() {
        const $inputParent = document.getElementById('selectedParent');
        const selectedIds = [];
        const selectedAttributes = document.querySelectorAll('input[name="positionAttributes[]"]');
        const template = document.getElementById('template-attribute');

        for (let e of selectedAttributes) {
            selectedIds.push(e.value);
        }

        for (let e of this.checkboxTargets) {
            if (e.checked && !selectedIds.includes(e.value)) {

                let newTemplate = template.cloneNode(true);
                newTemplate.removeAttribute('id');
                newTemplate.setAttribute('id', `selected-attribute-row-${e.value}`);
                newTemplate.classList.remove('d-none');
                newTemplate.querySelector('input[type="hidden"]').setAttribute('value', e.value);
                newTemplate.querySelector('.attribute-name').textContent = e.dataset.searchAttributeNameParam;
                newTemplate.querySelector('.attribute-category').textContent = e.dataset.searchAttributeCategoryParam;
                newTemplate.querySelector('.attribute-type').textContent = e.dataset.searchAttributeTypeParam;
                newTemplate.querySelector('input').setAttribute('value', e.value);
                $inputParent.appendChild(newTemplate);
                selectedIds.push(e.value);
            }
        }
    }
    addUserAttribute() {

        const selectedIds = [];

        // for (let e of selectedAttributes) {
        //     selectedIds.push(e.value);
        // }

        for (let e of this.checkboxTargets) {
            if (e.checked) {
                selectedIds.push(e.value);
            }
        }

        Turbo.visit()
        console.log(selectedIds);
        1
    }
}
