document.addEventListener("DOMContentLoaded", () => {
  const modal       = document.getElementById("modalEliminar");
  const modalNombre = document.getElementById("modalNombreProducto");
  const btnCancelar = document.getElementById("modalCancelar");
  const btnConfirmar = document.getElementById("modalConfirmar");

  let idProductoAEliminar = null;

  // Abrir modal al pulsar botón eliminar
  document.querySelectorAll(".btn-eliminar[data-id]").forEach((btn) => {
    btn.addEventListener("click", () => {
      idProductoAEliminar = btn.dataset.id;
      modalNombre.textContent = btn.dataset.nombre;
      modal.classList.add("visible");
    });
  });

  // Cerrar modal
  btnCancelar.addEventListener("click", () => {
    modal.classList.remove("visible");
    idProductoAEliminar = null;
  });

  modal.addEventListener("click", (e) => {
    if (e.target === modal) {
      modal.classList.remove("visible");
      idProductoAEliminar = null;
    }
  });

  // Confirmar eliminación
  btnConfirmar.addEventListener("click", async () => {
    if (!idProductoAEliminar) return;

    btnConfirmar.disabled = true;
    btnConfirmar.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Eliminando...';

    try {
      const formData = new FormData();
      formData.append("accion", "eliminar");
      formData.append("id_producto", idProductoAEliminar);

      const res = await fetch(BASE_URL + "admin/api_productos.php", {
        method: "POST",
        body: formData,
      });

      const data = await res.json();

      if (data.ok) {
        window.location.reload();
      } else {
        alert(data.error || "Error al eliminar el producto.");
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
