import { handleFormSubmit } from "./utils/form.js";

const form = document.querySelector("#create-form");

form.addEventListener('submit', (e) => {
    e.preventDefault();
    handleFormSubmit("POST", form)
})