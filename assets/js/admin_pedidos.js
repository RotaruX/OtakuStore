document.addEventListener("DOMContentLoaded", () => {
  const btnActualizar = document.getElementById("btnActualizarEstado");
  const selectEstado  = document.getElementById("nuevoEstado");
  const mensajeDiv    = document.getElementById("mensajeEstado");

  if (!btnActualizar || !selectEstado) return;

  btnActualizar.addEventListener("click", async () => {
    const id = btnActualizar.dataset.id;
    const nuevoEstado = selectEstado.value;

    btnActualizar.disabled = true;
    btnActualizar.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Actualizando...';
    mensajeDiv.textContent = "";
    mensajeDiv.className = "pedido-estado-mensaje";

    try {
      const res = await fetch(
        BASE_URL + "admin/actualizar_pedido.php?id=" + id + "&estado=" + encodeURIComponent(nuevoEstado)
      );
      const data = await res.json();

      if (data.ok) {
        mensajeDiv.textContent = "Estado actualizado a: " + nuevoEstado.charAt(0).toUpperCase() + nuevoEstado.slice(1);
        mensajeDiv.classList.add("mensaje-ok");

        // Recargar para actualizar la barra de progreso y badge
        setTimeout(() => {
          window.location.reload();
        }, 800);
      } else {
        mensajeDiv.textContent = data.error || "Error al actualizar el estado.";
        mensajeDiv.classList.add("mensaje-error");
        btnActualizar.disabled = false;
        btnActualizar.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Actualizar';
      }
    } catch (err) {
      console.error("Error:", err);
      mensajeDiv.textContent = "Error de conexión al actualizar.";
      mensajeDiv.classList.add("mensaje-error");
      btnActualizar.disabled = false;
      btnActualizar.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Actualizar';
    }
  });
});
