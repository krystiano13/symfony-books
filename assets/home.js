const booksList = document.querySelector("#books-list");

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

    listElement.id = book.id;
    listText.innerText = `${book.title} - ${book.author}`;
    btnEdit.innerText = "Edit";
    btnDelete.innerText = "Delete";

    btnSection.appendChild(btnEdit);
    btnSection.appendChild(btnDelete);

    listElement.appendChild(listText);
    listElement.appendChild(btnSection);

    return listElement;
}

const books = await fetch(`http://localhost:8000/book`, {
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