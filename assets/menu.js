const loginButton = document.querySelector("#login");
const registerButton = document.querySelector("#register");
const logoutButton = document.querySelector("#logout");
const logo = document.querySelector("#logo");

function logout() {
    localStorage.removeItem("token");
    localStorage.removeItem("username");
    window.location.href = "/login";
}

if(localStorage.getItem("token")) {
    registerButton.style.display = "none";
    loginButton.style.display = "none";
}

if(!localStorage.getItem("token")) {
    logoutButton.style.display = "none";
}

logoutButton.addEventListener("click", () => logout());
loginButton.addEventListener("click", () => window.location.href = "/login");
registerButton.addEventListener("click", () => window.location.href = "/register");
logo.addEventListener("click", () => window.location.href = "/");