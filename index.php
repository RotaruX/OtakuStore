<?php require_once("includes/header.php") ?>

<main>
    <section class="carrusel">
        <div class="bienvenida">
            <h1>¡BIENVENIDO A OTAKUSTORE, TU TIENDA ONLINE DE CONFIANZA!</h1>
            <a href="./tienda.php"><button class="btn-comprar">VER TIENDA</button></a>
        </div>
    </section>

    <section class="mas-vendidos">
        <h2>Más Vendidos</h2>
        <div class="contenedor-tarjetas">
            <article class="tarjeta">
                <img src="./assets/img/funko_zoro.png" alt="Funko Roronoa Zoro">
                <div class="info">
                    <span class="categoria">Funko</span>
                    <h3>Funko Roronoa Zoro</h3>
                    <p class="precio">15.99€</p>
                    <button class="btn-comprar">Añadir al carrito</button>
                </div>
            </article>

            <article class="tarjeta">
                <img src="./assets/img/funko_tanjiro.png" alt="Funko Tanjiro Kamado">
                <div class="info">
                    <span class="categoria">Funko</span>
                    <h3>Funko Tanjiro Kamado</h3>
                    <p class="precio">15.99€</p>
                    <button class="btn-comprar">Añadir al carrito</button>
                </div>
            </article>

            <article class="tarjeta">
                <img src="./assets/img/katana_zoro.png" alt="Katana Enma de Zoro">
                <div class="info">
                    <span class="categoria">Funko</span>
                    <h3>Katana Enma de Zoro</h3>
                    <p class="precio">45.50€</p>
                    <button class="btn-comprar">Añadir al carrito</button>
                </div>
            </article>

            <article class="tarjeta">
                <img src="./assets/img/manga1_onepiece.png" alt="Manga One Piece Vol. 1">
                <div class="info">
                    <span class="categoria">Cómic</span>
                    <h3>Manga One Piece - Vol. 1</h3>
                    <p class="precio">7.95€</p>
                    <button class="btn-comprar">Añadir al carrito</button>
                </div>
            </article>
        </div>
    </section>

    <section class="grid-tipos">
        <h2>NUESTRAS SECCIONES</h2>
        <div class="tarjeta-tipos">
            <span>FUNKOS</span>
            <a href="./tienda.php?tipo=funkos"><button class="btn-comprar">VER MAS</button></a>
        </div>

        <div class="tarjeta-tipos">
            <span>MANGAS</span>
            <a href="./tienda.php?tipo=comics"><button class="btn-comprar">VER MAS</button></a>
        </div>
    </section>

    <section class="ferias">
        <h3>¿QUIERES SABER CUALES SON LAS PROXIA FERIAS?</h3>
        <a href="./ferias.php"><button class="btn-comprar">VER FERIAS</button></a>
    </section>
</main>

<?php require_once("includes/footer.php") ?>