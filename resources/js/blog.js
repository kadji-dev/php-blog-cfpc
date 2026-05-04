// Gestion du menu responsive
const toggleMenu = document.querySelector('.toggle-menu');
const navMenu = document.querySelector('.nav-menu');

toggleMenu.addEventListener('click', function () {
    navMenu.classList.toggle('active');
});

// Gestion des menus déroulants
const allDropdown = document.querySelectorAll('.nav-dropdown');
const allNavLinks = document.querySelectorAll('.nav-menu li a');

allNavLinks.forEach((link, index) => {
    if (link.nextElementSibling && link.nextElementSibling.classList.contains('nav-dropdown')) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            allDropdown.forEach((dropdown) => {
                if (dropdown !== link.nextElementSibling) {
                    dropdown.style.display = 'none';
                }
            });
            link.nextElementSibling.style.display = link.nextElementSibling.style.display === 'block' ? 'none' : 'block';
        });
    }
});

// Fermer les menus déroulants au clic à l'extérieur
window.addEventListener('click', function (e) {
    if (!e.target.matches('.nav-menu li a')) {
        allDropdown.forEach((dropdown) => {
            dropdown.style.display = 'none';
        });
    }
});

// Gestion du profil
const profile = document.querySelector('.profile');
const profileLink = document.querySelector('.profile-link');

profile.addEventListener('click', function (e) {
    e.stopPropagation();
    profileLink.style.display = profileLink.style.display === 'block' ? 'none' : 'block';
});

// Fermer le menu profil au clic à l'extérieur
window.addEventListener('click', function () {
    profileLink.style.display = 'none';
});
