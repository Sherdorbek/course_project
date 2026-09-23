import { Controller } from '@hotwired/stimulus';


export default class extends Controller {
    static targets = ['delete', 'checkbox'];

    connect() {
        console.log(this.checkboxTargets);
    }

    toggleDelete() {
        let hasChecked = false;

        for (let e of this.checkboxTargets) {
            if (e.checked) {
                hasChecked = true;
                break;
            }
        }

        if (hasChecked) {
            this.deleteTarget.classList.remove('d-none');

        } else {
            this.deleteTarget.classList.add('d-none');
        }
    }

    removeAttribute() {

        const inputParent = document.getElementById('selectedParent');
        for (let e of this.checkboxTargets) {
            if (e.checked) {

                inputParent.querySelector(`#selected-attribute-row-${e.value}`).remove();
            }
        }
        this.toggleDelete();
    }
}
