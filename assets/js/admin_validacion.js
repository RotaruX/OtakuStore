document.addEventListener("DOMContentLoaded", function () {
  // Detectar formulario admin activo
  const formNuevo = document.querySelector(".caja-formulario");
  const formEditar = document.querySelector(".admin-form");
  const form = formNuevo || formEditar;
  if (!form) return;

  // Detectar página
  const pagina = location.pathname;
  const esNuevoProducto = pagina.includes("nuevo_producto");
  const esEditarProducto = pagina.includes("editar_producto");
  const esEditarUsuario = pagina.includes("editar_usuario");

  form.addEventListener("submit", function (e) {
    limpiarErrores();
    let valido = true;

    if (esNuevoProducto) {
      valido = validarNuevoProducto();
    } else if (esEditarProducto) {
      valido = validarEditarProducto();
    } else if (esEditarUsuario) {
      valido = validarEditarUsuario();
    }

    if (!valido) {
      e.preventDefault();
      // Scroll al primer error
      const primerError = form.querySelector(".input-error-admin, .archivo-error-admin");
      if (primerError) {
        primerError.scrollIntoView({ behavior: "smooth", block: "center" });
      }
    }
  });

  // === VALIDACIÓN: Nuevo Producto ===
  function validarNuevoProducto() {
    let valido = true;

    const nombre = document.getElementById("nombre");
    const descripcion = document.getElementById("descripcion");
    const precio = document.getElementById("precio");
    const stock = document.getElementById("stock");
    const archivo = document.getElementById("imagen_archivo");

    if (!nombre.value.trim()) {
      mostrarError(nombre, "El nombre del producto es obligatorio.");
      valido = false;
    }

    if (!descripcion.value.trim()) {
      mostrarError(descripcion, "La descripción es obligatoria.");
      valido = false;
    }

    if (!precio.value || parseFloat(precio.value) <= 0) {
      mostrarError(precio, "El precio debe ser mayor que 0.");
      valido = false;
    }

    if (!stock.value || parseInt(stock.value) < 1) {
      mostrarError(stock, "El stock debe ser al menos 1.");
      valido = false;
    }

    // Validar imagen obligatoria y formato
    if (archivo && archivo.files.length === 0) {
      mostrarErrorArchivo(archivo, "Es obligatorio adjuntar una imagen.");
      valido = false;
    } else if (archivo && archivo.files.length > 0) {
      if (!validarFormatoImagen(archivo)) {
        valido = false;
      }
    }

    return valido;
  }

  // === VALIDACIÓN: Editar Producto ===
  function validarEditarProducto() {
    let valido = true;

    const nombre = document.getElementById("nombre");
    const descripcion = document.getElementById("descripcion");
    const precio = document.getElementById("precio");
    const archivo = document.getElementById("imagen_archivo");

    if (!nombre.value.trim()) {
      mostrarError(nombre, "El nombre del producto es obligatorio.");
      valido = false;
    }

    if (!descripcion.value.trim()) {
      mostrarError(descripcion, "La descripción es obligatoria.");
      valido = false;
    }

    if (!precio.value || parseFloat(precio.value) <= 0) {
      mostrarError(precio, "El precio debe ser mayor que 0.");
      valido = false;
    }

    // Si se seleccionó archivo, validar formato
    if (archivo && archivo.files.length > 0) {
      if (!validarFormatoImagen(archivo)) {
        valido = false;
      }
    }

    return valido;
  }

  // === VALIDACIÓN: Editar Usuario ===
  function validarEditarUsuario() {
    let valido = true;

    const nombre = document.getElementById("nombre");
    const email = document.getElementById("email");
    const password = document.getElementById("nueva_password");

    if (!nombre.value.trim()) {
      mostrarError(nombre, "El nombre de usuario es obligatorio.");
      valido = false;
    }

    if (!email.value.trim()) {
      mostrarError(email, "El email es obligatorio.");
      valido = false;
    } else if (!validarEmail(email.value.trim())) {
      mostrarError(email, "Introduce un email válido.");
      valido = false;
    }

    // Solo validar contraseña si el usuario escribió algo
    if (password && password.value.length > 0 && password.value.length < 6) {
      mostrarError(password, "La contraseña debe tener al menos 6 caracteres.");
      valido = false;
    }

    return valido;
  }

  // === UTILIDADES ===

  function validarEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  function validarFormatoImagen(fileInput) {
    const archivo = fileInput.files[0];
    const nombre = archivo.name;
    const extension = nombre.split(".").pop().toLowerCase();
    const permitidos = ["jpg", "jpeg", "png", "webp"];

    if (!permitidos.includes(extension)) {
      mostrarErrorArchivo(fileInput, "Solo se permiten imágenes (JPG, PNG, WEBP).");
      return false;
    }
    return true;
  }

  function mostrarError(input, mensaje) {
    // Marcar input con borde rojo
    input.classList.add("input-error-admin");

    const span = document.createElement("span");
    span.className = "campo-error-admin";
    span.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> ' + mensaje;

    // Insertar después del contenedor padre adecuado
    const padre = input.closest(".entrada-con-icono") || input.closest(".input-con-icono") || input;
    padre.parentElement.appendChild(span);
  }

  function mostrarErrorArchivo(fileInput, mensaje) {
    const span = document.createElement("span");
    span.className = "campo-error-admin archivo-error-admin";
    span.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> ' + mensaje;

    // Insertar en el grupo-campo / admin-form-grupo que contiene el file input
    const grupo = fileInput.closest(".grupo-campo") || fileInput.closest(".admin-form-grupo");
    if (grupo) {
      grupo.appendChild(span);
    }
  }

  function limpiarErrores() {
    form.querySelectorAll(".campo-error-admin").forEach(function (el) {
      el.remove();
    });
    form.querySelectorAll(".input-error-admin").forEach(function (el) {
      el.classList.remove("input-error-admin");
    });
  }

  // === Limpiar error individual al escribir ===
  form.querySelectorAll("input, textarea, select").forEach(function (input) {
    input.addEventListener("input", function () {
      this.classList.remove("input-error-admin");
      const grupo = this.closest(".grupo-campo") || this.closest(".admin-form-grupo");
      if (grupo) {
        const error = grupo.querySelector(".campo-error-admin");
        if (error) error.remove();
      }
    });
  });

  // Limpiar error de archivo al seleccionar nuevo archivo
  const fileInput = document.getElementById("imagen_archivo");
  if (fileInput) {
    fileInput.addEventListener("change", function () {
      const grupo = this.closest(".grupo-campo") || this.closest(".admin-form-grupo");
      if (grupo) {
        const error = grupo.querySelector(".campo-error-admin");
        if (error) error.remove();
      }

      // Re-validar formato al cambiar archivo
      if (this.files.length > 0) {
        if (!validarFormatoImagen(this)) {
          // Error ya mostrado por validarFormatoImagen
        }
      }
    });
  }
});
