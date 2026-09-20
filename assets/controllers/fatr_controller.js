import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['name', 'output','list'];
    static values = { url: String,added: Array };
    static colors = {

        'string': 'primary',
        'text': 'secondary',
        'image': 'info',
        'numeric': 'warning',
        'date': 'success',
        'period': 'dark',
        'boolean': 'danger',
        'one of many': 'secondary',
    };

    connect() {

    }

    async search() {
        try {
            const response = await fetch(this.urlValue, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            this.renderResults(data);
        } catch (error) {
            console.error('Fetch error:', error);
        }


    }

    renderResults(data) {
        this.outputTarget.innerHTML = '';

        if (!data || data.length === 0) {
            this.outputTarget.innerHTML = '<p class="text-muted">No results found</p>';
            return;
        }


        let html = '<ul class="list-group">';

        data.forEach(item => {
            html += `
                <li class="list-group-item d-flex flex-row justify-content-between align-items-center">
                    <strong>${item.name}</strong>
                    <span class="badge bg-${this.constructor.colors[item.type]}">${item.type}</span>
                    <button
                        type="button"
                        data-action="click->fatr#addAttr"
                        data-id="${item.id}"
                        data-type="${item.type}"
                        data-name="${item.name}"
                        class="btn btn-sm btn-outline-primary"
                    ><i class="fa-solid fa-plus"></i></button>
                </li>
            `;
        });

        html += '</ul>';

        this.outputTarget.innerHTML = html;
    }

    addAttr(event) {
        event.preventDefault();

        const { id, type, name } = event.currentTarget.dataset;

        if (this.addedValue.some((item) => item.id === id)) {
            return;
        }

        this.addedValue = [...this.addedValue, { id, name,type }];
        this.renderAddedAttributes();
    }

    moveUp(event) {
        const index = Number(event.currentTarget.dataset.index);
        const attributes = this.addedValue;

        [attributes[index], attributes[index - 1]] = [attributes[index - 1], attributes[index]];
        this.addedValue = attributes;
        this.renderAddedAttributes();
    }

    remove(event) {
        const index = Number(event.currentTarget.dataset.index);
        const attributes = this.addedValue;

        attributes.splice(index, 1);
        this.addedValue = attributes;
        this.renderAddedAttributes();
    }

    renderAddedAttributes() {
        this.listTarget.innerHTML = '';

        if (this.addedValue.length === 0) {
            this.listTarget.innerHTML = '<span class="text-secondary">No attributes yet</span>';
            return;
        }

        let html = '<ul class="list-group">';

        this.addedValue.forEach((item, index) => {
            html += `
                <li class="list-group-item d-flex flex-row justify-content-between align-items-center">
                    <strong>${item.name}</strong>
                    <span class="badge bg-${this.constructor.colors[item.type]}">${item.type}</span>
                    <div>
                    <button type="button" 
                        data-action="click->fatr#moveUp" 
                        data-index="${index}" 
                        class="btn btn-sm border-0" ${index === 0 ? 'disabled' : ''}>
                        <i class="fa-solid fa-chevron-up"></i>
                    </button>
                    <button type="button" 
                        data-action="click->fatr#remove" 
                        data-index="${index}" 
                        class="btn btn-sm border-0">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    </div>
                    <input type="hidden" name="position[attributes][${index}]" value="${item.id}" />
                </li>
            `;
        });

        html += '</ul>';

        this.listTarget.innerHTML = html;
    }


}
