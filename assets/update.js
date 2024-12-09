import { handleFormSubmit } from "./utils/form.js";

const form = document.querySelector("#create-form");
const bookId = form.getAttribute("data-book");

form.addEventListener('submit', (e) => {
    e.preventDefault();
    handleFormSubmit("PATCH", form, bookId)
})