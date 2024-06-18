const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.getElementById('container');

signUpButton.addEventListener('click', () => {
    container.classList.add("right-panel-active");
});

signInButton.addEventListener('click', () => {
    container.classList.remove("right-panel-active");
});

// Cek hash di URL untuk menentukan panel mana yang ditampilkan
document.addEventListener('DOMContentLoaded', () => {
    if (window.location.hash === '#sign-up-container') {
        container.classList.add("right-panel-active");
    } else if (window.location.hash === '#sign-in-container') {
        container.classList.remove("right-panel-active");
    }
});