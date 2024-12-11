const logoutButton = document.querySelector("#logout");

function logout() {
    localStorage.removeItem("token");
    localStorage.removeItem("username");
    window.location.href = "/login";
}

logoutButton.addEventListener("click", () => logout());

