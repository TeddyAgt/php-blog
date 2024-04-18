const mobileMenuToggler = document.querySelector(".mobile-menu-toggler");
const navigationMenu = document.querySelector(".main-navigation");
isMenuExpanded = false;

mobileMenuToggler.addEventListener("click", toggleMobileMenu);

function toggleMobileMenu() {
  if (isMenuExpanded) {
    isMenuExpanded = false;
    navigationMenu.classList.remove("visible");
    mobileMenuToggler.ariaExpanded = "false";
    mobileMenuToggler.innerHTML = '<i class="fa-solid fa-bars"></i>';
    mobileMenuToggler.ariaLabel = "Ouvrir le menu de navigation";
  } else {
    isMenuExpanded = true;
    navigationMenu.classList.add("visible");
    mobileMenuToggler.ariaExpanded = "true";
    mobileMenuToggler.innerHTML = '<i class="fa-solid fa-xmark"></i>';
    mobileMenuToggler.ariaLabel = "Fermer le menu de navigation";
  }
}
