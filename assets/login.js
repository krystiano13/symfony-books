import { renderErrors } from "./utils/form.js";

if(localStorage.getItem("token")) {
    window.location.href = "/";
}

const form = document.querySelector("#user-form");

async function login() {
    const formData = new FormData(form);

    const formBody = {
        username: formData.get("username"),
        password: formData.get("password"),
    }

    await fetch("http://127.0.0.1:8000/api/login_check", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(formBody)
    })
        .then(res => {
            if(res.status === 401) {
                renderErrors(["Invalid Credentials"])
            }

            if(res.ok) {
                return res.json();
            }
        })
        .then(data => {
            localStorage.setItem("token", data.token);
            window.location.href = "/";
        })
}

form.addEventListener('submit', (e) => {
    e.preventDefault();
    login();
})