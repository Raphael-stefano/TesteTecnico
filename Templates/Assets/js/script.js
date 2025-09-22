document.addEventListener('DOMContentLoaded', function() {
    const menuButton = document.getElementById('mobile-menu-button');
    const headerRight = document.getElementById('mobile-hidden-menu');
    
    menuButton.addEventListener('click', function() {
        headerRight.classList.toggle('mobile-hidden');
        headerRight.classList.toggle('mobile-shown');
    });
});