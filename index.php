<?php
require_once(__DIR__ . '/config/parametros.php');
require_once(__DIR__ . '/includes/header.php');
?>

<main>
    <section class="carrusel">
        <div class="bienvenida">
            <h1>¡BIENVENIDO A OTAKUSTORE, TU TIENDA ONLINE DE CONFIANZA!</h1>
            <a href="<?= BASE_URL ?>tienda.php"><button class="btn">VER TIENDA</button></a>
        </div>
    </section>

    <section class="mas-vendidos">
        <h2>Más Vendidos</h2>
        <div class="contenedor-tarjetas">
            <article class="tarjeta">
                <img src="<?= BASE_URL ?>assets/img/funko_zoro.png" alt="Funko Roronoa Zoro">
                <div class="info">
                    <span class="categoria">Funko</span>
                    <h3>Funko Roronoa Zoro</h3>
                    <p class="precio">15.99€</p>
                    <button class="btn" data-id="1">Añadir al carrito</button>
                </div>
            </article>

            <article class="tarjeta">
                <img src="<?= BASE_URL ?>assets/img/funko_tanjiro.png" alt="Funko Tanjiro Kamado">
                <div class="info">
                    <span class="categoria">Funko</span>
                    <h3>Funko Tanjiro Kamado</h3>
                    <p class="precio">15.99€</p>
                    <button class="btn" data-id="2">Añadir al carrito</button>
                </div>
            </article>

            <article class="tarjeta">
                <img src="<?= BASE_URL ?>assets/img/katana_zoro.png" alt="Katana Enma de Zoro">
                <div class="info">
                    <span class="categoria">Funko</span>
                    <h3>Katana Enma de Zoro</h3>
                    <p class="precio">45.50€</p>
                    <button class="btn" data-id="3">Añadir al carrito</button>
                </div>
            </article>

            <article class="tarjeta">
                <img src="<?= BASE_URL ?>assets/img/manga1_onepiece.png" alt="Manga One Piece Vol. 1">
                <div class="info">
                    <span class="categoria">Cómic</span>
                    <h3>Manga One Piece - Vol. 1</h3>
                    <p class="precio">7.95€</p>
                    <button class="btn" data-id="4">Añadir al carrito</button>
                </div>
            </article>
        </div>
    </section>

    <section class="grid-tipos">
        <h2>NUESTRAS SECCIONES</h2>
        <div class="tarjeta-tipos">
            <span>FUNKOS</span>
            <a href="<?= BASE_URL ?>tienda.php?tipo=funkos"><button class="btn">VER MAS</button></a>
        </div>

        <div class="tarjeta-tipos">
            <span>MANGAS</span>
            <a href="<?= BASE_URL ?>tienda.php?tipo=comics"><button class="btn">VER MAS</button></a>
        </div>
    </section>

    <section class="ferias">
        <h3>¿QUIERES SABER CUALES SON LAS PROXIA FERIAS?</h3>
        <a href="<?= BASE_URL ?>ferias.php"><button class="btn-comprar">VER FERIAS</button></a>
    </section>
</main>

<script>
    const BASE_URL = "<?= BASE_URL ?>";
</script>
<script src="<?= BASE_URL ?>assets/js/carrito.js"></script>
<?php require_once(__DIR__ . "/includes/footer.php") ?>