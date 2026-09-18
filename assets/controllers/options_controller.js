import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['list', 'typeSelect', 'wrapper'];
    static values = { index: { type: Number, default: 0 }, prototype: String };

    connect() {
        this.indexValue = this.listTarget.querySelectorAll('input').length;

        this._toggle();
    }

    typeChanged() {
        this._toggle();
    }

    addOption() {
        const row = document.createElement('div');
        row.classList.add('input-group', 'mb-2');

        const formName = this.listTarget.dataset.formName;

        row.innerHTML = `
            <input type="text"
                   class="form-control"
                   name="${formName}[${this.indexValue}]"
                   required />
            <button type="button"
                    class="btn btn-outline-secondary"
                    data-action="options#removeOption">
                <i class="bi bi-trash"></i>
            </button>
        `;

        this.listTarget.appendChild(row);
        this.indexValue++;
    }

    removeOption(event) {
        event.currentTarget.closest('.input-group').remove();
    }

    _toggle() {
        const isOneOfMany = this.typeSelectTarget.value === 'one_of_many';
        this.wrapperTarget.style.display = isOneOfMany ? '' : 'none';

        if (!isOneOfMany) {
            this.listTarget.innerHTML = '';
            this.indexValue = 0;
        }
    }
}
