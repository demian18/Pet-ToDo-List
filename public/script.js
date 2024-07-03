document.addEventListener('DOMContentLoaded', function() {
    const button = document.getElementById('user-menu-button');

    if (button) {
        button.addEventListener('click', function() {
            window.location.href = '/profile';
        });
    }
});
