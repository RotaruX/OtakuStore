document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector(".login-container form");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    // Limpiar errores previos
    limpiarErrores();

    const pagina = form.getAttribute("action");
    let valido = true;

    if (pagina === "registro.php") {
      valido = validarRegistro();
    } else {
      valido = validarLogin();
    }

    if (!valido) {
      e.preventDefault();
    }
  });

  function validarLogin() {
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    let valido = true;

    if (!email.value.trim()) {
      mostrarError(email, "El email es obligatorio.");
      valido = false;
    } else if (!validarEmail(email.value.trim())) {
      mostrarError(email, "Introduce un email válido.");
      valido = false;
    }

    if (!password.value) {
      mostrarError(password, "La contraseña es obligatoria.");
      valido = false;
    }

    return valido;
  }

  function validarRegistro() {
    const nombre = document.getElementById("nombre");
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const confirmar = document.getElementById("confirmar_password");
    let valido = true;

    if (!nombre.value.trim()) {
      mostrarError(nombre, "El nombre de usuario es obligatorio.");
      valido = false;
    } else if (nombre.value.trim().length < 3) {
      mostrarError(nombre, "El nombre debe tener al menos 3 caracteres.");
      valido = false;
    }

    if (!email.value.trim()) {
      mostrarError(email, "El email es obligatorio.");
      valido = false;
    } else if (!validarEmail(email.value.trim())) {
      mostrarError(email, "Introduce un email válido.");
      valido = false;
    }

    if (!password.value) {
      mostrarError(password, "La contraseña es obligatoria.");
      valido = false;
    } else if (password.value.length < 6) {
      mostrarError(password, "La contraseña debe tener al menos 6 caracteres.");
      valido = false;
    }

    if (!confirmar.value) {
      mostrarError(confirmar, "Debes confirmar la contraseña.");
      valido = false;
    } else if (password.value !== confirmar.value) {
      mostrarError(confirmar, "Las contraseñas no coinciden.");
      valido = false;
    }

    return valido;
  }

  // --- Utilidades ---

  function validarEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  function mostrarError(input, mensaje) {
    input.classList.add("input-error");
    const span = document.createElement("span");
    span.className = "campo-error";
    span.textContent = mensaje;
    input.parentElement.appendChild(span);
  }

  function limpiarErrores() {
    document.querySelectorAll(".campo-error").forEach((el) => el.remove());
    document
      .querySelectorAll(".input-error")
      .forEach((el) => el.classList.remove("input-error"));
  }

  // Limpiar error individual al escribir
  document.querySelectorAll(".campo input").forEach((input) => {
    input.addEventListener("input", function () {
      this.classList.remove("input-error");
      const error = this.parentElement.querySelector(".campo-error");
      if (error) error.remove();
    });
  });
});
