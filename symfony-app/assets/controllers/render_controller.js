import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    static targets = ["content"];

    async render(event) {
        event.preventDefault();
        const url = event.currentTarget.dataset.url;
        try {
            // alert(url);
            const response = await fetch(url);
            if (!response.ok) throw new Error("Error en la respuesta");
            const html = await response.text();
            this.contentTarget.innerHTML = html;
        } catch (error) {
            this.contentTarget.innerHTML = "Error al obtener los datos.";
            console.error(error);
        }
    }

}
