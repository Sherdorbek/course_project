import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['add', 'checkbox'];

    static values = {
        url: String,
    }

    search(e) {
        const { value } = e.target;

        Turbo.visit(this.urlValue + '?q=' + value, {
            frame: 'search-result'
        });
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


    addAttribute() {
        const $inputParent = document.getElementById('selectedParent');
        const selectedIds = [];
        const selectedAttributes = document.querySelectorAll('input[name="positionAttributes[]"]');
        const template = document.getElementById('template-attribute');
        console.log(template);

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
}
