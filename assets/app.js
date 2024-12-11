import './styles/app.css';
import { jwtDecode } from "jwt-decode";

const token = localStorage.getItem("token");

if(!token && window.location.pathname !== "/register" && window.location.pathname !== "/login") {
    window.location.href = "/login";
}

const tokenDecoded = jwtDecode(token);

if(!tokenDecoded.username && window.location.pathname !== "/register" && window.location.pathname !== "/login") {
    window.location.href = "/login";
}

if(tokenDecoded.username) {
    const usernames = document.querySelectorAll(".username");
    usernames.forEach(element => {
        element.innerHTML = tokenDecoded.username;
    })
}

let mobileNavShown = false;
const mobileNav = document.querySelector("#mobile-nav");
const hamburgerButton = document.querySelector("#hamburger");

function checkNav() {
    if(mobileNavShown) {
        mobileNav.style.transform = "translateY(0vh)";
    } else {
        mobileNav.style.transform = "translateY(-100vh)";
    }
}

checkNav();
hamburgerButton.addEventListener("click", () => {
    mobileNavShown = !mobileNavShown;
    checkNav();
});