import { Controller } from '@hotwired/stimulus';
import * as FilePond from 'filepond';
import 'filepond/dist/filepond.min.css';

export default class extends Controller {
    static targets = ['input']

    connect() {
        this.ponds = this.inputTargets.map(input => {
            return FilePond.create(input, {
                allowMultiple: false,
                instantUpload: false,
                storeAsFile: true,
            });
        });
    }

    disconnect() {
        this.ponds?.forEach(pond => pond.destroy());
    }
}
