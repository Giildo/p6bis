/* Functions definition */


function showSlides(n) {
    let slideIndex = n;
    let i;
    let slides = document.getElementsByClassName("slide");
    let dots = document.getElementsByClassName("thumbnail");

    if (n > slides.length) {
        slideIndex = 1;
    }

    if (n < 1) {
        slideIndex = slides.length
    }

    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }

    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }

    slides[slideIndex-1].style.display = "block";
    dots[slideIndex-1].className += " active";
}

function nextSlide() {
    let index = displayPictureIndex();
    index++;

    showSlides(index);
}

function prevSlide() {
    let index = displayPictureIndex();
    index--;

    showSlides(index);
}

function displayPictureIndex() {
    let displayPictures = document.getElementsByClassName("slide");

    for (let i = 0; i < displayPictures.length; i++) {
        if (displayPictures[i].style.display === "block") {
            return displayPictures[i].getAttribute("data-index");
        }
    }
}

/* Use */
showSlides(1);

let closeButton = document.getElementById("close");
let nextButton = document.getElementById("next");
let prevButton = document.getElementById("prev");

let modalWindow = document.getElementById("modalWindows");
let sectionShow = document.getElementById("section-show");
let sectionCom = document.getElementById("section-com");

closeButton.addEventListener("click", function() {
    modalWindow.style.display = "none";
    sectionShow.style.display = "block";
    sectionCom.style.display = "block";
});

nextButton.addEventListener("click", function() {
    nextSlide();
});

prevButton.addEventListener("click", function() {
    prevSlide();
});

/* Open modal windows */
let pictures = document.getElementsByClassName("thumbnail-pic");

for (let i = 0; i < pictures.length; i++) {
    pictures[i].addEventListener("click", function() {
        modalWindow.style.display = "block";
        sectionShow.style.display = "none";
        sectionCom.style.display = "none";
    });
}

/* Display direct picture */
let thumbnails = document.getElementsByClassName("thumbnail");

for (let i = 0; i < thumbnails.length; i++) {
    thumbnails[i].addEventListener("click", function(e) {
        let index = e.toElement.getAttribute("data-index");

        showSlides(index);
    });
}
