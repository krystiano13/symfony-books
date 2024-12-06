const booksList = document.querySelector("#books-list");

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
        const listElement = document.createElement("li");
        listElement.innerText = `${item.title} - ${item.author}`;
        booksList.appendChild(listElement);
    })
}