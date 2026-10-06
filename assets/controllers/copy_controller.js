import { Controller } from '@hotwired/stimulus';


export default class extends Controller {
    static targets = ['text', 'btn', 'btnText']


    copy() {
        navigator.clipboard.writeText(this.textTarget.innerText);

        this.btnTarget.classList.remove('btn-primary');
        this.btnTarget.classList.add('btn-success');
        this.btnTextTarget.innerText = "Copied";
    }

    reset() {
        this.btnTarget.classList.remove('btn-success');
        this.btnTarget.classList.add('btn-primary');
        this.btnTextTarget.innerText = "Copy";
    }

}
