import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['selected', 'results'];
    static values = { url: String, selected: Array, };

    connect() {
        
    }

    async search() {
        try {
            const response = await fetch(this.urlValue, { method: 'GET' });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            this.renderResults(data);
        } catch (error) {
            console.error('Fetch error:', error);
        }
    }

    
}
