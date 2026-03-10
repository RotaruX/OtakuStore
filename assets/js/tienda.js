document.addEventListener("DOMContentLoaded", () => {
  /* 1. LÍMITE DINÁMICO POR VIEWPORT (cookie)
      • Móvil  (< 768px)  → 6 productos por página
      • Tablet (768–1023) → 10 productos por página
      • Desktop(≥ 1024)   → 12 productos por página */
  function getLimiteViewport() {
    const ancho = window.innerWidth;
    if (ancho < 768) return 6;
    if (ancho < 1024) return 10;
    return 12;
  }

  const limiteVP = getLimiteViewport();

  // Leer cookie actual
  const cookieMatch = document.cookie.match(/productos_por_pagina=(\d+)/);
  const cookieActual = cookieMatch ? parseInt(cookieMatch[1]) : null;

  // Si la cookie no existe o no coincide con el viewport, actualizarla
  if (cookieActual !== limiteVP) {
    document.cookie = "productos_por_pagina=" + limiteVP + ";path=/;max-age=86400;SameSite=Lax";
    // Recargar solo si PHP usó un límite diferente
    if (typeof PRODUCTOS_POR_PAGINA !== "undefined" && PRODUCTOS_POR_PAGINA !== limiteVP) {
      window.location.reload();
      return;
    }
  }

  /* 2. BOTONES AÑADIR AL CARRITO */
  const botonesAnadir = document.querySelectorAll(".btn-anadir[data-id]");

  botonesAnadir.forEach((btn) => {
    btn.addEventListener("click", async () => {
      // Verificar sesión (variable inyectada por PHP)
      if (!SESION_ACTIVA) {
        window.location.href = BASE_URL + "login.php?redir=tienda";
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

        const res = await fetch(BASE_URL + "includes/carrito_api.php", {
          method: "POST",
          body: formData,
        });

        const data = await res.json();

        if (data.logueado === false) {
          window.location.href = BASE_URL + "login.php?redir=tienda";
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
});
