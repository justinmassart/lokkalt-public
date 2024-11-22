const goBackUpLink = document.querySelector(".footer__go-back-up__link");

goBackUpLink.addEventListener("click", function (event) {
    event.preventDefault();
    window.scrollTo(0, 0);
});
