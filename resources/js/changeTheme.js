
const themeStyle = document.getElementById("theme-style");

const themeDark = document.getElementById("theme-dark");
const themeLight = document.getElementById("theme-light");

themeDark.addEventListener("click", () => {
    console.log("dark");
    themeStyle.href = "assets/css/mainDark.css";
    localStorage.setItem("theme", "dark");

});

themeLight.addEventListener("click", () => {
    console.log("light");
    themeStyle.href = "assets/css/main.css";
    localStorage.setItem("theme", "light");

});

// Función para aplicar el tema guardado
function applySavedTheme() {
    console.log("applySavedTheme");
    const savedTheme = localStorage.getItem("theme");
    if (savedTheme === "dark") {
        themeStyle.href = "assets/css/mainDark.css";
    } else {
        // Por defecto: light
        themeStyle.href = "assets/css/main.css";
    }
}

// Aplicar el tema al cargar la página
applySavedTheme();

