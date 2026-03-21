$(document).ready(function () {

  $(".tarjeta, .tarjeta-tipos, .feria-card").css("opacity", 0);

  if ("IntersectionObserver" in window) {
    const observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
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
    $(".tarjeta, .tarjeta-tipos, .feria-card").css("opacity", 1);
  }


  $(".feria-card .feria-desc").hide();

  $(".feria-card").on("click", function () {
    $(this).find(".feria-desc").slideToggle(400);

    $(this).toggleClass("feria-expandida");
  });


  $(".tarjeta-tipos .btn").on("mouseenter", function () {
    $(this).stop(true).fadeOut(150, function () {
      $(this).fadeIn(300);
    });
  });

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
    $("html, body").animate({ scrollTop: 0 }, 600);
  });
});
