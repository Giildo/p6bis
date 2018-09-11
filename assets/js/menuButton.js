let buttonOpen = document.getElementById("menu-icon-open");
let buttonClose = document.getElementById("menu-icon-close");
let menu = document.getElementById("navbar");

buttonOpen.addEventListener("click", function () {
    menu.style.left = "0";
    this.style.display = "none";
    buttonClose.style.display = "block";
});

buttonClose.addEventListener("click", function () {
    menu.style.left = "100vw";
    this.style.display = "none";
    buttonOpen.style.display = "block";
});