let index = 2;

let button = document.getElementById("moreTricks");
let pageNumber = button.dataset.pagingmax;

button.addEventListener("click", function (e) {
    e.preventDefault();

    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            let section = document.getElementById("section-home");
            section.innerHTML += this.responseText;
        }
    };
    xmlhttp.open("GET", "/tricks/" + index);
    xmlhttp.send();

    index += 1;

    if (index > pageNumber) {
        button.style.display = "none";
    }
});
