document.addEventListener("DOMContentLoaded", () => {
  const modal       = document.getElementById("modalEliminar");
  const modalNombre = document.getElementById("modalNombreUsuario");
  const btnCancelar = document.getElementById("modalCancelar");
  const btnConfirmar = document.getElementById("modalConfirmar");

  let idUsuarioAEliminar = null;

  // Abrir modal al pulsar botón eliminar
  document.querySelectorAll(".btn-eliminar[data-id]").forEach((btn) => {
    btn.addEventListener("click", () => {
      idUsuarioAEliminar = btn.dataset.id;
      modalNombre.textContent = btn.dataset.nombre;
      modal.classList.add("visible");
    });
  });

  // Cerrar modal
  btnCancelar.addEventListener("click", () => {
    modal.classList.remove("visible");
    idUsuarioAEliminar = null;
  });

  modal.addEventListener("click", (e) => {
    if (e.target === modal) {
      modal.classList.remove("visible");
      idUsuarioAEliminar = null;
    }
  });

  // Confirmar eliminación
  btnConfirmar.addEventListener("click", async () => {
    if (!idUsuarioAEliminar) return;

    btnConfirmar.disabled = true;
    btnConfirmar.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Eliminando...';

    try {
      const res = await fetch(BASE_URL + "admin/borrar_usuario.php?id=" + idUsuarioAEliminar);

      const data = await res.json();

      if (data.ok) {
        window.location.reload();
      } else {
        alert(data.error || "Error al eliminar el usuario.");
        modal.classList.remove("visible");
        btnConfirmar.disabled = false;
        btnConfirmar.innerHTML = "Eliminar";
      }
    } catch (err) {
      console.error("Error:", err);
      alert("Error de conexión al eliminar.");
      modal.classList.remove("visible");
      btnConfirmar.disabled = false;
      btnConfirmar.innerHTML = "Eliminar";
    }
  });
});
