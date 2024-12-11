import qs from 'qs';

let releaseDateSort = undefined;
let titleSort = undefined;
let authorSort = undefined;

const data = {
    filters: [

    ],
    sort: {
        release_date: releaseDateSort,
        title: titleSort,
        author: authorSort,
    }
}

const queryString = qs.stringify(data, { arrayFormat: "brackets" })

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
    const books = await fetch(`http://localhost:8000/book?${queryString}`, {
        method: "GET",
        headers: {
            "Content-Type": "application/json"
        }
    });

    const booksData = await books.json();

    console.log(booksData)

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