function addTagForm(divParent, labelName) {
    let prototype = divParent.getAttribute("data-prototype");

    let index = divParent.getAttribute("data-index");
    index = parseInt(index) + 1;

    let newForm = prototype;
    newForm = newForm.replace(/__name__/g, index);

    let divInner = document.createElement("div");
    divInner.innerHTML = newForm;
    divInner.className = "collection-block";

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

let buttonAddVideo = document.createElement("button");
let contentAddVideo = document.createTextNode("Ajouter une vidéo");
buttonAddVideo.className = "add_picture";
buttonAddVideo.appendChild(contentAddVideo);

let buttonRemoveVideo = document.createElement("button");
let contentRemoveVideo = document.createTextNode("Supprimer une vidéo");
buttonRemoveVideo.className = "remove_picture";
buttonRemoveVideo.appendChild(contentRemoveVideo);

let divParentVideo = document.getElementById("new_trick_videos");
divParentVideo.parentElement.parentElement.childNodes[1].appendChild(buttonAddVideo);
divParentVideo.parentElement.parentElement.childNodes[1].appendChild(buttonRemoveVideo);

divParentVideo.setAttribute("data-index", "0");

buttonAddVideo.addEventListener("click", function (e) {
    e.preventDefault();

    addTagForm(divParentVideo, "URL de la vidéo ");
});

buttonRemoveVideo.addEventListener("click", function (e) {
    e.preventDefault();

    removeTagForm(divParentVideo, "new_trick_videos_");
});

let buttonAddPicture = document.createElement("button");
let contentAddPicture = document.createTextNode("Ajouter une image");
buttonAddPicture.className = "add_picture btn btn-success";
buttonAddPicture.appendChild(contentAddPicture);

let buttonRemovePicture = document.createElement("button");
let contentRemovePicture = document.createTextNode("Supprimer une image");
buttonRemovePicture.className = "remove_picture btn btn-danger";
buttonRemovePicture.appendChild(contentRemovePicture);

let divParentPicture = document.getElementById("new_trick_pictures");
divParentPicture.parentElement.parentElement.childNodes[1].appendChild(buttonAddPicture);
divParentPicture.parentElement.parentElement.childNodes[1].appendChild(buttonRemovePicture);

divParentPicture.setAttribute("data-index", "0");

buttonAddPicture.addEventListener("click", function (e) {
    e.preventDefault();

    addTagForm(divParentPicture, "Description de l'image ");
});

buttonRemovePicture.addEventListener("click", function (e) {
    e.preventDefault();

    removeTagForm(divParentPicture, "new_trick_pictures_");
});
