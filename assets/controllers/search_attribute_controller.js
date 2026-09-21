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

    addAttribute(){
        // $inputParent = document.getElementById('selectedParent');
        // $input
    }
}
