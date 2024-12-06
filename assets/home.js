const books = await fetch(`http://localhost:8000/book`, {
    method: "GET",
    headers: {
        "Content-Type": "application/json"
    }
});

const booksData = await books.json();

console.log(booksData);