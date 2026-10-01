import { Controller } from '@hotwired/stimulus';



export default class extends Controller {

    scrollTo(event) {
        event.preventDefault();

        const targetId = event.currentTarget.getAttribute('href');
        const target = document.querySelector(targetId);

        target?.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

}
