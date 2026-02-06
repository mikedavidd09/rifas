const button = document.getElementById("toggle-theme");
const themeStyle = document.getElementById("theme-style");
const themeText = document.getElementById("theme-text");
const themeIcon = button.querySelector(".fa");

// Función para actualizar el botón (texto e icono)
function updateButton() {
    if (themeStyle.href.includes("assets/css/mainDark.css")) {
        themeIcon.classList.replace("fa-moon-o", "fa-sun-o"); // Cambia ícono
        themeText.textContent = "Modo Claro"; // Cambia texto
    } else {
        themeIcon.classList.replace("fa-sun-o", "fa-moon-o");
        themeText.textContent = "Modo Oscuro";
    }
}

if (localStorage.getItem("theme") === "dark") {
    themeStyle.href = "assets/css/mainDark.css";
}
updateButton(); // Actualizar texto del botón al cargar la página

button.addEventListener("click", () => {
    if (themeStyle.href.includes("assets/css/main.css")) {
        themeStyle.href = "assets/css/mainDark.css";
        localStorage.setItem("theme", "dark");
    } else {
        themeStyle.href = "assets/css/main.css";
        localStorage.setItem("theme", "light");
    }

    updateButton(); // Cambiar el texto del botón al cambiar el tema

});
