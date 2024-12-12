const loginButton = document.querySelectorAll(".login");
const registerButton = document.querySelectorAll(".register");
const logoutButton = document.querySelectorAll(".logout");
const logo = document.querySelector("#logo");

function logout() {
    localStorage.removeItem("token");
    localStorage.removeItem("username");
    window.location.href = "/login";
}

if(localStorage.getItem("token")) {
    registerButton.forEach(item => {
        item.style.display = "none"
    })
    loginButton.forEach(item => item.style.display = "none")
}

if(!localStorage.getItem("token")) {
    logoutButton.forEach(item => {
        item.style.display = "none"
    });
}

logoutButton.forEach(item => item.addEventListener("click", () => logout()))
loginButton.forEach(item => item.addEventListener("click", () => window.location.href = "/login"))
registerButton.forEach(item => item.addEventListener("click", () => window.location.href = "/register"))
logo.addEventListener("click", () => window.location.href = "/");