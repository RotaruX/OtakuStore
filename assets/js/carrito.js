document.addEventListener("DOMContentLoaded", () => {
  const botonesCarrito = document.querySelectorAll(
    ".mas-vendidos .btn[data-id]",
  );

  botonesCarrito.forEach((btn) => {
    btn.addEventListener("click", async () => {
      const idProducto = btn.dataset.id;

      // Evitar doble clic mientras se procesa
      if (btn.disabled) return;
      btn.disabled = true;

      try {
        const formData = new FormData();
        formData.append("id_producto", idProducto);

        const res = await fetch("./includes/añadir_carrito.php", {
          method: "POST",
          body: formData,
        });

        const data = await res.json();

        if (data.logueado === false) {
          // No logueado → redirigir a login
          window.location.href = "./login.php";
          return;
        }

        if (data.ok) {
          // Feedback visual de éxito
          const textoOriginal = btn.textContent;
          btn.textContent = "✓ Añadido";
          btn.classList.add("btn-añadido");

          setTimeout(() => {
            btn.textContent = textoOriginal;
            btn.classList.remove("btn-añadido");
            btn.disabled = false;
          }, 1800);
        } else if (data.mensaje) {
          // Sin stock u otro aviso
          const textoOriginal = btn.textContent;
          btn.textContent = data.mensaje;
          btn.classList.add("btn-sin-stock");

          setTimeout(() => {
            btn.textContent = textoOriginal;
            btn.classList.remove("btn-sin-stock");
            btn.disabled = false;
          }, 2000);
        } else {
          btn.disabled = false;
        }
      } catch (err) {
        console.error("Error al añadir al carrito:", err);
        btn.disabled = false;
      }
    });
  });
});
