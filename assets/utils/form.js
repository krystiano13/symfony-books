const errorsSection = document.querySelector("#errors");

function renderErrors(errors) {
    errors.forEach(item => {
        const paragraph = document.createElement("p");
        paragraph.innerText = item;
        paragraph.className = "error";
        errorsSection.appendChild(paragraph);
    })
}

export async function handleFormSubmit(method, form, bookId = -1) {
    const data = new FormData(form);

    errorsSection.innerHTML = "";

    const formBody = {
        title: data.get("title"),
        author: data.get("author"),
        isbn: data.get("isbn"),
        release_date: Number(data.get("release_date"))
    }

    await fetch(`http://127.0.0.1:8000/books${method === "PATCH" ? `/${bookId}` : ""}`, {
        method: method,
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