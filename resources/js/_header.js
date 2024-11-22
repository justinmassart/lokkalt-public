function toggleClass(element, removeClass, addClass) {
    element.classList.remove(removeClass);
    element.classList.add(addClass);
}

const headerTopBurger = document.querySelector(".header__top__burger");
const headerTopMenu = document.querySelector(".header__top__menu");
const headerNavCategories = document.querySelector(
    ".header__bottom__nav__item:first-child"
);
const headerNavCategoriesChevron = headerNavCategories.querySelector(
    ".header__bottom__nav__item__chervron"
);
const headerCategories = document.querySelector(".header__categories");

headerTopBurger.addEventListener("click", () => {
    if (headerTopMenu.classList.contains("open")) {
        toggleClass(headerTopMenu, "open", "closed");
        headerTopBurger.classList.toggle("open");

        setTimeout(() => {
            headerTopMenu.classList.remove("closed");
        }, 500);
    } else {
        toggleClass(headerTopMenu, "closed", "open");
        headerTopBurger.classList.toggle("open");
    }

    if (headerNavCategories.classList.contains("open")) {
        toggleClass(headerNavCategories, "open", "closed");
        toggleClass(headerCategories, "open", "closed");
        toggleClass(headerNavCategoriesChevron, "up", "down");
    }
});
