let footer = document.getElementById("footer");

let footerBottomPosition = footer.offsetHeight + footer.offsetTop;
let heightWindows = window.innerHeight;

if (footerBottomPosition <= heightWindows) {
    footer.classList = "footer-bottom";
}
