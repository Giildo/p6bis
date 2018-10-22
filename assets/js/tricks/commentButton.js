let button = document.getElementById("commentButton");
let buttonText = document.getElementById("commentButtonText");
let newButtonText = document.createElement("span");
newButtonText.innerText = "Modifier le commentaire";
newButtonText.setAttribute("id", "commentButtonText");

window.location.search.substr(1).split('&').forEach(function (item) {
    let itemSplitted = item.split('=');

    if (itemSplitted[0] === 'action') {
        if (itemSplitted[1] === 'modifier') {
            buttonText.innerText = "Modifier le commentaire";
            button.replaceChild(newButtonText, buttonText);
        }
    }
});
