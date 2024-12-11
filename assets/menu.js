const logoutButton = document.querySelector("#logout");
const logo = document.querySelector("#logo");

function logout() {
    localStorage.removeItem("token");
    localStorage.removeItem("username");
    window.location.href = "/login";
}

logoutButton.addEventListener("click", () => logout());
logo.addEventListener("click", () => window.location.href = "/");