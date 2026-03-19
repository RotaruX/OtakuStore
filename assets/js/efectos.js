/**
 * efectos.js
 * Efectos jQuery para mejorar la experiencia visual en la página de inicio.
 * Utiliza jQuery para animaciones Fade, Slide, y mostrar/ocultar contenido.
 */
$(document).ready(function () {

  /* ──────────────────────────────────────────────
     1. FADE-IN de las tarjetas de productos al
        entrar en el viewport (IntersectionObserver
        + jQuery fadeIn)
     ────────────────────────────────────────────── */
  $(".tarjeta, .tarjeta-tipos, .feria-card").css("opacity", 0);

  // Usamos IntersectionObserver nativo + jQuery para el efecto
  if ("IntersectionObserver" in window) {
    const observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            // Efecto fadeIn con jQuery
            $(entry.target).delay($(entry.target).index() * 100).fadeIn(600).css("display", "");
            $(entry.target).animate({ opacity: 1 }, 600);
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.15 }
    );

    $(".tarjeta, .tarjeta-tipos, .feria-card").each(function () {
      observer.observe(this);
    });
  } else {
    // Fallback: mostrar todo si no hay soporte
    $(".tarjeta, .tarjeta-tipos, .feria-card").css("opacity", 1);
  }

  /* ──────────────────────────────────────────────
     2. SLIDE-TOGGLE para mostrar/ocultar información
        extra en la sección Ferias (solo si hay ferias)
     ────────────────────────────────────────────── */
  $(".feria-card .feria-desc").hide();

  $(".feria-card").on("click", function () {
    // SlideToggle jQuery sobre la descripción de la feria
    $(this).find(".feria-desc").slideToggle(400);

    // Alternar clase visual
    $(this).toggleClass("feria-expandida");
  });

  /* ──────────────────────────────────────────────
     3. HOVER con fadeIn/fadeOut para los botones
        de la sección "Nuestras Secciones"
     ────────────────────────────────────────────── */
  $(".tarjeta-tipos .btn").on("mouseenter", function () {
    $(this).stop(true).fadeOut(150, function () {
      $(this).fadeIn(300);
    });
  });

  /* ──────────────────────────────────────────────
     4. SMOOTH SCROLL-TO-TOP con jQuery animate
     ────────────────────────────────────────────── */
  const $btnTop = $('<button id="btnScrollTop" title="Volver arriba"><i class="fa-solid fa-arrow-up"></i></button>');
  $("body").append($btnTop);

  // Mostrar/ocultar el botón con fadeIn/fadeOut
  $(window).on("scroll", function () {
    if ($(this).scrollTop() > 400) {
      $btnTop.fadeIn(300);
    } else {
      $btnTop.fadeOut(300);
    }
  });

  $btnTop.on("click", function () {
    // Animación jQuery para scroll suave
    $("html, body").animate({ scrollTop: 0 }, 600);
  });
});
