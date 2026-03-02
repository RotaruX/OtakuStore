document.addEventListener("DOMContentLoaded", () => {
  const grid          = document.getElementById("carritoGrid");
  const precioTotalEl = document.getElementById("precioTotal");
  const btnSeleccion  = document.getElementById("btnComprarSeleccion");
  const btnTodo       = document.getElementById("btnComprarTodo");

  if (!grid) return;

  // Suma los totales de las tarjetas marcadas y actualiza el elemento del precio
  function actualizarTotal() {
    let total = 0;
    document.querySelectorAll(".tarjeta-carrito").forEach((card) => {
      const check = card.querySelector(".check-item");
      if (check && check.checked) {
        total += parseFloat(card.dataset.total) || 0;
      }
    });
    precioTotalEl.textContent =
      total.toLocaleString("es-ES", { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + " €";
  }

  // Al marcar o desmarcar un checkbox, opaca la tarjeta y recalcula el total
  grid.addEventListener("change", (e) => {
    if (e.target.classList.contains("check-item")) {
      const card = e.target.closest(".tarjeta-carrito");
      card.classList.toggle("desactivada", !e.target.checked);
      actualizarTotal();
    }
  });

  // Elimina un producto del carrito con animación y recarga si queda vacío
  grid.addEventListener("click", async (e) => {
    const btnEliminar = e.target.closest(".btn-eliminar");
    if (btnEliminar) {
      const card   = btnEliminar.closest(".tarjeta-carrito");
      const idProd = card.dataset.id;
      const nombre = card.querySelector(".tarjeta-nombre")?.textContent ?? "este producto";

      if (!confirm(`¿Quieres eliminar "${nombre}" del carrito?`)) return;

      btnEliminar.disabled = true;
      try {
        const fd = new FormData();
        fd.append("id_producto", idProd);
        const res  = await fetch("./includes/eliminar_carrito.php", { method: "POST", body: fd });
        const data = await res.json();
        if (data.ok) {
          card.classList.add("eliminando");
          card.addEventListener("animationend", () => {
            card.remove();
            actualizarTotal();
            if (grid.querySelectorAll(".tarjeta-carrito").length === 0) {
              location.reload();
            }
          }, { once: true });
        }
      } catch (err) {
        console.error("Error al eliminar:", err);
        btnEliminar.disabled = false;
      }
    }
  });

  // Compra únicamente el producto de la tarjeta pulsada
  grid.addEventListener("click", (e) => {
    const btnUno = e.target.closest(".btn-comprar-uno");
    if (btnUno) {
      mostrarMensajePago(`Comprando: ${btnUno.dataset.nombre}`);
    }
  });

  // Compra solo los productos con el checkbox marcado
  btnSeleccion?.addEventListener("click", () => {
    const seleccionados = [...document.querySelectorAll(".tarjeta-carrito")]
      .filter((c) => c.querySelector(".check-item")?.checked)
      .map((c) => c.querySelector(".tarjeta-nombre")?.textContent)
      .join(", ");

    if (!seleccionados) {
      alert("⚠️ No tienes ningún producto seleccionado.");
      return;
    }
    mostrarMensajePago(`Comprando seleccionados: ${seleccionados}`);
  });

  // Compra todos los productos del carrito
  btnTodo?.addEventListener("click", () => {
    mostrarMensajePago("Comprando todos los productos del carrito");
  });

  // Placeholder hasta que se implemente la pasarela de pago
  function mostrarMensajePago(detalle) {
    alert(`✅ Pedido iniciado\n\n${detalle}\n\n(Pasarela de pago próximamente)`);
  }
});
