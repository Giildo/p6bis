function addTagForm(divParent, labelName) {
    let prototype = divParent.getAttribute("data-prototype");

    let index = divParent.getAttribute("data-index");
    index = parseInt(index) + 1;

    let newForm = prototype;
    newForm = newForm.replace(/__name__/g, index);

    let divInner = document.createElement("div");
    divInner.innerHTML = newForm;
    let label = divInner.getElementsByTagName("label")[0];

    divParent.setAttribute("data-index", index);

    divParent.appendChild(divInner);
}

function removeTagForm(divParent, divParentId) {
    let index = divParent.getAttribute("data-index");

    if (index > 0) {
        let divRemove = document.getElementById(divParentId + index);

        divRemove = divRemove.parentNode.parentNode;

        divParent.removeChild(divRemove);

        index = parseInt(index) - 1;
        divParent.setAttribute("data-index", index);
    }
}

let buttonAddNewVideo = document.createElement("button");
let contentAddNewVideo = document.createTextNode("Ajouter une vidéo");
buttonAddNewVideo.className = "add_picture btn btn-success";
buttonAddNewVideo.appendChild(contentAddNewVideo);

let buttonRemoveNewVideo = document.createElement("button");
let contentRemoveNewVideo = document.createTextNode("Supprimer une vidéo");
buttonRemoveNewVideo.className = "remove_picture btn btn-danger";
buttonRemoveNewVideo.appendChild(contentRemoveNewVideo);

let divParentNewVideo = document.getElementById("trick_modification_newVideos");
divParentNewVideo.parentElement.parentElement.childNodes[1].appendChild(buttonAddNewVideo);
divParentNewVideo.parentElement.parentElement.childNodes[1].appendChild(buttonRemoveNewVideo);

divParentNewVideo.setAttribute("data-index", "0");

buttonAddNewVideo.addEventListener("click", function (e) {
    e.preventDefault();

    addTagForm(divParentNewVideo, "URL de la vidéo ");
});

buttonRemoveNewVideo.addEventListener("click", function (e) {
    e.preventDefault();

    removeTagForm(divParentNewVideo, "trick_modification_newVideos_");
});

let buttonAddNewPicture = document.createElement("button");
let contentAddNewPicture = document.createTextNode("Ajouter une image");
buttonAddNewPicture.className = "add_picture btn btn-success";
buttonAddNewPicture.appendChild(contentAddNewPicture);

let buttonRemoveNewPicture = document.createElement("button");
let contentRemoveNewPicture = document.createTextNode("Supprimer une image");
buttonRemoveNewPicture.className = "remove_picture btn btn-danger";
buttonRemoveNewPicture.appendChild(contentRemoveNewPicture);

let divParentNewPicture = document.getElementById("trick_modification_newPictures");
divParentNewPicture.parentElement.parentElement.childNodes[1].appendChild(buttonAddNewPicture);
divParentNewPicture.parentElement.parentElement.childNodes[1].appendChild(buttonRemoveNewPicture);

divParentNewPicture.setAttribute("data-index", "0");

buttonAddNewPicture.addEventListener("click", function (e) {
    e.preventDefault();

    addTagForm(divParentNewPicture, "Description de l'image ");
});

buttonRemoveNewPicture.addEventListener("click", function (e) {
    e.preventDefault();

    removeTagForm(divParentNewPicture, "trick_modification_newPictures_");
});
