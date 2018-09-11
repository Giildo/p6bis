let button = document.getElementById("comment-button");
let buttonText = document.getElementById("commentButtonText");
let anchor = window.location.hash;

if (anchor === "#comment-form") {
    buttonText.innerText = "Modifier le commentaire";
    button.replaceChild(buttonText, buttonText);
}