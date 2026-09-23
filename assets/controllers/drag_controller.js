import { Controller } from '@hotwired/stimulus';
import Sortable from 'sortablejs';


/* stimulusFetch: 'lazy' */
export default class extends Controller {
    // static targets = ['container']

    connect() {
        // console.log(this.containerTarget);
        // // Initialize SortableJS on the target element
        // this.sortable = Sortable.create(this.containerTarget, {
        //     handle: '.handle', // the drag handle class
        //     animation: 150,
        // });
    }

    disconnect() {
        // // Always clean up Sortable instance when the controller disconnects
        // if (this.sortable) {
        //     this.sortable.destroy();
        // }
    }
}
