let buttons = document.getElementsByClassName("delete");
let modalElement = document.getElementById("modalElement");
let modalWindowFooter = document.getElementById("modalWindowFooter");
let closeModal = document.getElementById("close-modal");

let deleteButton = document.createElement("a");
deleteButton.innerText = "Suppression";
deleteButton.id = "modal-delete";

for (let i = 0; i < buttons.length; i++) {
    buttons[i].addEventListener("click", function (e) {
        e.preventDefault();

        deleteButton.href = this.href;

        modalWindowFooter.appendChild(deleteButton);
        modalElement.style.display = "flex";
    });
}

let cancelButton = document.getElementById("modal-cancel");

let closeFunction = function (e) {
    e.preventDefault();

    let deleteButton = document.getElementById("modal-delete");
    modalElement.style.display = "none";
    modalWindowFooter.removeChild(deleteButton);
};

cancelButton.addEventListener("click", closeFunction);
closeModal.addEventListener("click", closeFunction);

modalElement.addEventListener("click", function (e) {
    e.preventDefault();
    e.stopPropagation();

    if (e.target === modalElement) {
        let deleteButton = document.getElementById("modal-delete");
        modalElement.style.display = "none";
        modalWindowFooter.removeChild(deleteButton);
    }
});

deleteButton.addEventListener("click", function (e) {
    e.preventDefault();

    document.location.href = this.href;
});
