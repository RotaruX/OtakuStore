document.addEventListener("DOMContentLoaded", () => {

    const boton = document.getElementById("botonHamburguesa");
    const menu = document.getElementById("menu");

    boton.addEventListener("click", () => {
        menu.classList.toggle("activo");
    });

});
