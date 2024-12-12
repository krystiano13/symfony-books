import qs from 'qs';
import { debounce } from "./utils/debounce.js";

const data = {
    filters: {
        release_date: undefined,
        title: undefined,
        author: undefined,
    },
    sort: {
        release_date: undefined,
        title: undefined,
        author: undefined,
    }
}

const select = document.querySelector("select");
const titleInput = document.querySelector("#title-filter");
const authorInput = document.querySelector("#author-filter");
const yearInput = document.querySelector("#year-filter");

titleInput.addEventListener("input",debounce(e => {
    data.filters.title = e.target.value;
    getBooks();
}, 500))

authorInput.addEventListener("input",debounce(e => {
    data.filters.author = e.target.value;
    getBooks();
}, 500))

yearInput.addEventListener("input",debounce(e => {
    data.filters.release_date = e.target.value;
    getBooks();
}, 500))

select.addEventListener("change", debounce((e) => {
    console.log(e.target.value)
    switch(e.target.value) {
        case "title-asc":
            data.sort.title = "asc";
            data.sort.release_date = undefined;
            data.sort.author = undefined;
            break;
        case "title-desc":
            data.sort.title = "desc";
            data.sort.release_date = undefined;
            data.sort.author = undefined;
            break;
        case "author-asc":
            data.sort.title = undefined;
            data.sort.release_date = undefined;
            data.sort.author = "asc";
            break;
        case "author-desc":
            data.sort.title = undefined;
            data.sort.release_date = undefined;
            data.sort.author = "desc";
            break;
        case "ry-asc":
            data.sort.title = undefined;
            data.sort.release_date = "asc";
            data.sort.author = undefined;
            break;
        case "ry-desc":
            data.sort.title = undefined;
            data.sort.release_date = "desc";
            data.sort.author = undefined;
            break;
    }
    getBooks();
}, 500))

const booksList = document.querySelector("#books-list");

async function destroy(id) {
    await fetch(`http://127.0.0.1:8000/api/books/${id}`, {
        method: "DELETE",
        headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`
        }
    })
        .then(res => {
            if(res.ok) {
                getBooks();
            }
            else {
                alert("Could not delete this book")
            }
        })
}

async function getBooks() {
    const queryString = qs.stringify(data, { arrayFormat: "brackets" })

    const books = await fetch(`http://localhost:8000/book?${queryString}`, {
        method: "GET",
        headers: {
            "Content-Type": "application/json"
        }
    });

    const booksData = await books.json();

    if(booksData.books.length > 0) {
        booksList.innerHTML = "";

        booksData.books.forEach(item => {
            const listElement = createListElement(item);
            booksList.appendChild(listElement);
        })
    }
}

function createListElement(book) {
    const listElement = document.createElement("li");
    const listText = document.createElement("span");
    const btnSection = document.createElement("section");
    const btnEdit = document.createElement("button");
    const btnDelete = document.createElement("button");

    btnSection.className = "btn-section";
    btnEdit.className = "btn btn-blue";
    btnDelete.className = "btn btn-red";
    listElement.className = "list-item";

    btnEdit.addEventListener('click', () => {
        window.location.href = `/books/edit/${book.id}`;
    })

    btnDelete.addEventListener('click', () => {
        destroy(book.id)
    })

    listElement.id = book.id;
    listText.innerText = `${book.title} - ${book.author}`;
    btnEdit.innerText = "Edit";
    btnDelete.innerText = "Delete";

    if(localStorage.getItem("token"))
    {
        btnSection.appendChild(btnEdit);
        btnSection.appendChild(btnDelete);
    }

    listElement.appendChild(listText);
    listElement.appendChild(btnSection);

    return listElement;
}

getBooks()