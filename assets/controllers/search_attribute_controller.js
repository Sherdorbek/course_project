import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static values = {
        url: String,
    }

    search(e) {
        const { value } = e.target;

        Turbo.visit(this.urlValue + '?q=' + value, {
            frame: 'search-result'
        });
    }
}
