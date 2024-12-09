import { renderErrors } from "./utils/form.js";

const form = document.querySelector("#user-form");

async function createAccount() {
    const data = new FormData(form);

    const formBody = {
        username: data.get("username"),
        password: data.get("password"),
        password_confirmation: data.get("password_confirmation")
    }

    await fetch("http://127.0.0.1:8000/api/register", {
        method: "POST",
        body: JSON.stringify(formBody)
    })
        .then(res => res.json())
        .then(data => {
            console.log(data)
        })
}

form.addEventListener('submit', (e) => {
    e.preventDefault();
    createAccount();
})