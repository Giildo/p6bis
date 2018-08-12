function addTagForm(divParent, labelName) {
    let prototype = divParent.getAttribute("data-prototype");

    let index = divParent.getAttribute("data-index");
    index = parseInt(index) + 1;

    let newForm = prototype;
    newForm = newForm.replace(/__name__/g, index);

    let divInner = document.createElement("div");
    divInner.innerHTML = newForm;
    let label = divInner.getElementsByTagName("label")[0];
    label.textContent = labelName + index;

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
buttonAddVideo.className = "add_picture btn btn-success";
buttonAddVideo.appendChild(contentAddVideo);

let buttonRemoveVideo = document.createElement("button");
let contentRemoveVideo = document.createTextNode("Supprimer une vidéo");
buttonRemoveVideo.className = "remove_picture btn btn-danger";
buttonRemoveVideo.appendChild(contentRemoveVideo);

let divParentVideo = document.getElementById("trick_modification_newVideos");
divParentVideo.appendChild(buttonAddVideo);
divParentVideo.appendChild(buttonRemoveVideo);

divParentVideo.setAttribute("data-index", "0");

buttonAddVideo.addEventListener("click", function (e) {
    e.preventDefault();

    addTagForm(divParentVideo, "URL de la vidéo ");
});

buttonRemoveVideo.addEventListener("click", function (e) {
    e.preventDefault();

    removeTagForm(divParentVideo, "trick_modification_newVideos_");
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
divParentNewPicture.appendChild(buttonAddNewPicture);
divParentNewPicture.appendChild(buttonRemoveNewPicture);

divParentNewPicture.setAttribute("data-index", "0");

buttonAddNewPicture.addEventListener("click", function (e) {
    e.preventDefault();

    addTagForm(divParentNewPicture, "Description de l'image ");
});

buttonRemoveNewPicture.addEventListener("click", function (e) {
    e.preventDefault();

    removeTagForm(divParentNewPicture, "trick_modification_newPictures_");
});
