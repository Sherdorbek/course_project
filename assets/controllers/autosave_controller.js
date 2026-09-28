import { Controller } from '@hotwired/stimulus';


export default class extends Controller {
    static targets = ['form']

    connect() {
        this.timeout = null;
        this.element.addEventListener('input', this.onInput);
        this.element.addEventListener('change', this.onInput);
    }

    disconnect() {
        clearTimeout(this.timeout);
        this.element.removeEventListener('input', this.onInput);
        this.element.removeEventListener('change', this.onInput);
    }

    onInput = (event) => {
        if (event.target.matches('input[data-controller="csrf-protection"], input[name="_csrf_token"]')) {
            return;
        }
        clearTimeout(this.timeout);
        this.timeout = setTimeout(() => this.save(), 2000);
    };

    save() {
        this.formTarget.requestSubmit();
    }
}
