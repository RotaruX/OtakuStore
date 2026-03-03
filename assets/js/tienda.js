

document.addEventListener("DOMContentLoaded", () => {

  const botonesAnadir = document.querySelectorAll(".btn-anadir[data-id]");

  botonesAnadir.forEach((btn) => {
    btn.addEventListener("click", async () => {
      // Verificar sesión (variable inyectada por PHP)
      if (!SESION_ACTIVA) {
        window.location.href = "./login.php?redir=tienda";
        return;
      }

      if (btn.disabled) return;
      btn.disabled = true;

      const idProducto = btn.dataset.id;
      const textoOriginal = btn.innerHTML;

      try {
        const formData = new FormData();
        formData.append("accion", "añadir");
        formData.append("id_producto", idProducto);

        const res = await fetch("./includes/carrito_api.php", {
          method: "POST",
          body: formData,
        });

        const data = await res.json();

        if (data.logueado === false) {
          window.location.href = "./login.php?redir=tienda";
          return;
        }

        if (data.ok) {
          btn.innerHTML = '<i class="fa-solid fa-check"></i> Añadido';
          btn.classList.add("btn-añadido");

          setTimeout(() => {
            btn.innerHTML = textoOriginal;
            btn.classList.remove("btn-añadido");
            btn.disabled = false;
          }, 1800);
        } else if (data.mensaje) {
          btn.innerHTML = data.mensaje;
          btn.classList.add("btn-sin-stock");

          setTimeout(() => {
            btn.innerHTML = textoOriginal;
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

  /* 2. LÍMITE DE TARJETAS POR BREAKPOINT
      • Móvil  (< 768px)  → 6 tarjetas visibles
      • Tablet (768–1023) → 10 tarjetas visibles
      • Desktop(≥ 1024)   → 12 tarjetas (todas)*/
  const tarjetas = Array.from(document.querySelectorAll(".tienda-tarjeta"));

  function aplicarLimite() {
    const ancho = window.innerWidth;
    let maximo;

    if (ancho < 768) maximo = 6;
    else if (ancho < 1024) maximo = 10;
    else maximo = 12; 

    tarjetas.forEach((t, i) => {
      if (i < maximo) {
        t.classList.remove("oculta-tablet");
      } else {
        t.classList.add("oculta-tablet");
      }
    });
  }

  aplicarLimite();
  let resizeTimer;
  window.addEventListener("resize", () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(aplicarLimite, 120);
  });
});
