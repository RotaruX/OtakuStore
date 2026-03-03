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

  // Gestiona los botones + y - de cantidad
  grid.addEventListener("click", async (e) => {
    const btnCantidad = e.target.closest(".btn-cantidad");
    if (!btnCantidad) return;

    const card      = btnCantidad.closest(".tarjeta-carrito");
    const idProd    = card.dataset.id;
    const stock     = parseInt(card.dataset.stock) || 99;
    const precio    = parseFloat(card.dataset.precio) || 0;
    const spanVal   = card.querySelector(".cantidad-valor");
    const spanTotal = card.querySelector(".total-linea");
    let actual      = parseInt(spanVal.textContent) || 1;

    const esMas   = btnCantidad.classList.contains("btn-mas");
    const esMenos = btnCantidad.classList.contains("btn-menos");

    let nueva = esMas ? actual + 1 : actual - 1;
    if (nueva < 1) return;          // no bajar de 1 (usar eliminar para eso)
    if (nueva > stock) {
      alert(`⚠️ Solo quedan ${stock} unidades disponibles.`);
      return;
    }

    btnCantidad.disabled = true;
    try {
      const fd = new FormData();
      fd.append("accion", "actualizar");
      fd.append("id_producto", idProd);
      fd.append("cantidad", nueva);
      const res  = await fetch("./includes/carrito_api.php", { method: "POST", body: fd });
      const data = await res.json();

      if (data.ok) {
        const nuevoTotal = nueva * precio;
        spanVal.textContent             = nueva;
        spanTotal.textContent           = nuevoTotal.toLocaleString("es-ES", { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + " €";
        card.dataset.total              = nuevoTotal.toFixed(2);
        actualizarTotal();
      } else {
        alert("⚠️ " + (data.mensaje || data.error || "No se pudo actualizar la cantidad."));
      }
    } catch (err) {
      console.error("Error al actualizar cantidad:", err);
    } finally {
      btnCantidad.disabled = false;
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
        fd.append("accion", "eliminar");
        fd.append("id_producto", idProd);
        const res  = await fetch("./includes/carrito_api.php", { method: "POST", body: fd });
        const data = await res.json();
        if (data.ok) {
          animarYEliminarCard(card);
        }
      } catch (err) {
        console.error("Error al eliminar:", err);
        btnEliminar.disabled = false;
      }
    }
  });

  // Compra únicamente el producto de la tarjeta pulsada
  grid.addEventListener("click", async (e) => {
    const btnUno = e.target.closest(".btn-comprar-uno");
    if (btnUno) {
      const card   = btnUno.closest(".tarjeta-carrito");
      const idProd = card.dataset.id;
      await procesarCompra([idProd], [card]);
    }
  });

  // Compra solo los productos con el checkbox marcado
  btnSeleccion?.addEventListener("click", async () => {
    const seleccionadas = [...document.querySelectorAll(".tarjeta-carrito")]
      .filter((c) => c.querySelector(".check-item")?.checked);

    if (!seleccionadas.length) {
      alert("⚠️ No tienes ningún producto seleccionado.");
      return;
    }
    await procesarCompra(seleccionadas.map((c) => c.dataset.id), seleccionadas);
  });

  // Compra todos los productos del carrito
  btnTodo?.addEventListener("click", async () => {
    const todas = [...document.querySelectorAll(".tarjeta-carrito")];
    await procesarCompra(todas.map((c) => c.dataset.id), todas);
  });

  // Envía la petición de compra, anima las tarjetas compradas y muestra confirmación
  async function procesarCompra(ids, cards) {
    if (!ids.length) return;

    try {
      const fd = new FormData();
      fd.append("accion", "comprar");
      fd.append("ids_productos", ids.join(","));
      const res  = await fetch("./includes/carrito_api.php", { method: "POST", body: fd });
      const data = await res.json();

      if (data.logueado === false) {
        window.location.href = "./login.php";
        return;
      }

      if (data.ok) {
        cards.forEach((card) => animarYEliminarCard(card));

        // Pequeña espera para que termine la animación antes de mostrar el banner
        setTimeout(() => mostrarConfirmacion(data.id_pedido), 400);
      } else {
        alert("❌ " + (data.error ?? "No se pudo procesar el pedido."));
      }
    } catch (err) {
      console.error("Error al realizar pedido:", err);
      alert("❌ Error de conexión al procesar el pedido.");
    }
  }

  // Anima la desaparición de una tarjeta y recarga si el grid queda vacío
  function animarYEliminarCard(card) {
    card.classList.add("eliminando");
    card.addEventListener("animationend", () => {
      card.remove();
      actualizarTotal();
      const quedan = grid.querySelectorAll(".tarjeta-carrito").length;
      if (quedan === 0) {
        setTimeout(() => location.reload(), 300);
      }
    }, { once: true });
  }

  // Muestra un banner de confirmación con un enlace a Mis Pedidos
  function mostrarConfirmacion(idPedido) {
    const resumen = document.getElementById("carritoResumen");

    const banner = document.createElement("div");
    banner.className = "banner-confirmacion";
    banner.innerHTML = `
      <i class="fa-solid fa-circle-check"></i>
      <div>
        <p>¡Pedido <strong>#${idPedido}</strong> realizado con éxito!</p>
        <a href="./pedidos.php" class="btn-ver-pedidos">Ver mis pedidos</a>
      </div>
    `;

    if (resumen) {
      resumen.replaceWith(banner);
    } else {
      document.querySelector(".carrito-page")?.appendChild(banner);
    }
  }
});
