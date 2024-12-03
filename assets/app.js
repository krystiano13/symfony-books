import './styles/app.css';

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