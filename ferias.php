<?php require_once "./includes/header.php"; ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/carousel/carousel.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/carousel/carousel.dots.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/carousel/carousel.arrows.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/carousel/carousel.autoplay.css">

<main class="ferias-main">

    <section class="ferias-hero">
        <div class="ferias-hero-content">
            <h1>PRÓXIMAS FERIAS</h1>
            <p>Las mejores convenciones de manga y anime de España en 2026</p>
        </div>
    </section>

    <section class="ferias-seccion">
        <h2 class="ferias-titulo">Calendario 2026</h2>

        <div class="ferias-grid">

            <article class="feria-card">
                <div class="feria-card-header">
                    <i class="fa-solid fa-map-pin feria-icono"></i>
                    <span class="feria-badge proxima">Próximamente</span>
                </div>
                <div class="feria-card-body">
                    <h3>Manga Barcelona</h3>
                    <p class="feria-lugar"><i class="fa-solid fa-location-dot"></i> Barcelona</p>
                    <p class="feria-direccion"><i class="fa-solid fa-building"></i> Fira de Barcelona, Gran Via — L'Hospitalet de Llobregat</p>
                    <p class="feria-fecha"><i class="fa-regular fa-calendar"></i> 23 – 26 Abr · 2026</p>
                    <p class="feria-desc">El mayor salón del manga y la animación japonesa de España. Cuatro días de exposiciones, cosplay, torneos y actividades.</p>
                </div>
            </article>

            <article class="feria-card">
                <div class="feria-card-header">
                    <i class="fa-solid fa-map-pin feria-icono"></i>
                    <span class="feria-badge proxima">Próximamente</span>
                </div>
                <div class="feria-card-body">
                    <h3>Salón del Manga de Madrid</h3>
                    <p class="feria-lugar"><i class="fa-solid fa-location-dot"></i> Madrid</p>
                    <p class="feria-direccion"><i class="fa-solid fa-building"></i> IFEMA Madrid, Av. del Partenón, 5 — Madrid</p>
                    <p class="feria-fecha"><i class="fa-regular fa-calendar"></i> 15 – 17 May · 2026</p>
                    <p class="feria-desc">Una de las citas más esperadas del año en la capital, con invitados internacionales, paneles y zona de compra-venta.</p>
                </div>
            </article>

            <article class="feria-card">
                <div class="feria-card-header">
                    <i class="fa-solid fa-map-pin feria-icono"></i>
                    <span class="feria-badge proxima">Próximamente</span>
                </div>
                <div class="feria-card-body">
                    <h3>Japan Weekend Barcelona</h3>
                    <p class="feria-lugar"><i class="fa-solid fa-location-dot"></i> Barcelona</p>
                    <p class="feria-direccion"><i class="fa-solid fa-building"></i> Fira de Barcelona, Gran Via — L'Hospitalet de Llobregat</p>
                    <p class="feria-fecha"><i class="fa-regular fa-calendar"></i> 20 – 21 Jun · 2026</p>
                    <p class="feria-desc">Festival dedicado a la cultura japonesa: anime, manga, gastronomía, música y moda kawaii en el corazón de Barcelona.</p>
                </div>
            </article>

            <article class="feria-card">
                <div class="feria-card-header">
                    <i class="fa-solid fa-map-pin feria-icono"></i>
                    <span class="feria-badge proxima">Próximamente</span>
                </div>
                <div class="feria-card-body">
                    <h3>Expomanga Madrid</h3>
                    <p class="feria-lugar"><i class="fa-solid fa-location-dot"></i> Madrid</p>
                    <p class="feria-direccion"><i class="fa-solid fa-building"></i> Palacio Municipal de Congresos, Av. de la Capital, 7 — Madrid</p>
                    <p class="feria-fecha"><i class="fa-regular fa-calendar"></i> 25 – 27 Sep · 2026</p>
                    <p class="feria-desc">Feria de referencia en el mundo del manga, anime y los videojuegos con concursos de cosplay y exhibiciones exclusivas.</p>
                </div>
            </article>

            <article class="feria-card">
                <div class="feria-card-header">
                    <i class="fa-solid fa-map-pin feria-icono"></i>
                    <span class="feria-badge proxima">Próximamente</span>
                </div>
                <div class="feria-card-body">
                    <h3>Japan Weekend Madrid</h3>
                    <p class="feria-lugar"><i class="fa-solid fa-location-dot"></i> Madrid</p>
                    <p class="feria-direccion"><i class="fa-solid fa-building"></i> IFEMA Madrid, Av. del Partenón, 5 — Madrid</p>
                    <p class="feria-fecha"><i class="fa-regular fa-calendar"></i> 10 – 11 Oct · 2026</p>
                    <p class="feria-desc">La edición madrileña de Japan Weekend reunirá a miles de fans de la cultura japonesa durante un fantástico fin de semana.</p>
                </div>
            </article>

            <article class="feria-card">
                <div class="feria-card-header">
                    <i class="fa-solid fa-map-pin feria-icono"></i>
                    <span class="feria-badge proxima">Próximamente</span>
                </div>
                <div class="feria-card-body">
                    <h3>HeroFest Bilbao</h3>
                    <p class="feria-lugar"><i class="fa-solid fa-location-dot"></i> Bilbao</p>
                    <p class="feria-direccion"><i class="fa-solid fa-building"></i> Bilbao Exhibition Centre, Ronda de Azkue, 1 — Barakaldo</p>
                    <p class="feria-fecha"><i class="fa-regular fa-calendar"></i> 7 – 8 Nov · 2026</p>
                    <p class="feria-desc">Convención nórdica de cómics, manga y cultura pop con exposiciones, charlas con autores y zona de dealers.</p>
                </div>
            </article>

        </div>
    </section>

    <section class="ferias-galeria">
        <h2 class="ferias-titulo">Galería</h2>

        <div class="f-carousel" id="myCarousel">
            <div class="f-carousel__viewport">
                <div class="f-carousel__slide">
                    <img data-lazy-src="./assets/img/ferias/feria1.webp" width="900" height="500" alt="Feria 1">
                    <div class="overlay"></div>
                    <div class="caption">Manga Barcelona</div>
                </div>
                <div class="f-carousel__slide">
                    <img data-lazy-src="./assets/img/ferias/feria2.webp" width="900" height="500" alt="Feria 2">
                    <div class="overlay"></div>
                    <div class="caption">Salón del Manga de Madrid</div>
                </div>
                <div class="f-carousel__slide">
                    <img data-lazy-src="./assets/img/ferias/feria1.webp" width="900" height="500" alt="Feria 3">
                    <div class="overlay"></div>
                    <div class="caption">Japan Weekend Barcelona</div>
                </div>
                <div class="f-carousel__slide">
                    <img data-lazy-src="./assets/img/ferias/feria4.webp" width="900" height="500" alt="Feria 4">
                    <div class="overlay"></div>
                    <div class="caption">Expomanga Madrid</div>
                </div>
                <div class="f-carousel__slide">
                    <img data-lazy-src="./assets/img/ferias/feria2.webp" width="900" height="500" alt="Feria 5">
                    <div class="overlay"></div>
                    <div class="caption">Japan Weekend Madrid</div>
                </div>
                <div class="f-carousel__slide">
                    <img data-lazy-src="./assets/img/ferias/feria6.webp" width="900" height="500" alt="Feria 6">
                    <div class="overlay"></div>
                    <div class="caption">HeroFest Bilbao</div>
                </div>
            </div>
        </div>
    </section>

</main>

<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/carousel/carousel.umd.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/carousel/carousel.dots.umd.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/carousel/carousel.arrows.umd.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/carousel/carousel.lazyload.umd.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/carousel/carousel.autoplay.umd.js"></script>

<script>
    Carousel(
        document.getElementById("myCarousel"),
        {
            Autoplay: {
                autoStart: true,
                timeout: 6000,
                showProgressbar: false,
                pauseOnHover: false,
            },
            setTransform: (_ref, slide, state) => {
                if (slide.el) {
                    slide.el.style.opacity = `${1 - Math.abs(state.xPercent) / 100 || 0}`;
                }
            },
        },
        {
            Arrows,
            Dots,
            Autoplay,
            Lazyload,
        },
    ).init();
</script>

<?php require_once "./includes/footer.php"; ?>