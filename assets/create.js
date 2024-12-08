const form = document.querySelector("#create-form");
const errorsSection = document.querySelector("#errors");

async function create() {
    const data = new FormData(form);

    errorsSection.innerHTML = "";

    const formBody = {
        title: data.get("title"),
        author: data.get("author"),
        isbn: data.get("isbn"),
        release_date: Number(data.get("release_date"))
    }

    await fetch("http://127.0.0.1:8000/books", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(formBody)
    })
        .then(res => res.json())
        .then(data => {
            if(data.errors) {
                renderErrors(data.errors);
            }

            else {
                window.location.href = "/";
            }
        })
}

function renderErrors(errors) {
    errors.forEach(item => {
        const paragraph = document.createElement("p");
        paragraph.innerText = item;
        paragraph.className = "error";
        errorsSection.appendChild(paragraph);
    })
}

form.addEventListener('submit', (e) => {
    e.preventDefault();
    create();
})