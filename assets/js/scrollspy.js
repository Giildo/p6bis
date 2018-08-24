document.getElementById("a_scrollspy").addEventListener("click", function (link) {
    link.preventDefault();
    let hash = this.hash;
    let regex = new RegExp("/accueil");

    if (document.location.href.search(regex) === -1) {
        document.location.href = "/accueil";
    }

    if (hash !== "") {
        let navHeight = document.getElementById("navbar");

        let top = document.getElementById(hash).offsetTop - navHeight.offsetHeight;

        window.scrollTo({
            top: top,
            behavior: "smooth"
        });
    }
});