import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['checkbox']

    toggle(e) {
        this.checkboxTargets.forEach(ch => {
            ch.checked = e.target.checked
        })
    }
}
