import { Controller } from '@hotwired/stimulus';


export default class extends Controller {
    static targets = ['form']

    connect() {
        this.timeout = null;
        this.element.addEventListener('input', this.onInput);
    }

    disconnect() {
        clearTimeout(this.timeout);
        this.element.removeEventListener('input', this.onInput);
    }

    onInput = () => {
        clearTimeout(this.timeout);
        this.timeout = setTimeout(() => this.save(), 2000);
    };

    save() {
       this.formTarget.requestSubmit();
    }
}
