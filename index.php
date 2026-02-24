<?php require_once("includes/header.php") ?>

<main>
    <section class="carrusel">
        <div class="bienvenida">
            <h1>¡BIENVENIDO A OTAKUSTORE, TU TIENDA ONLINE DE CONFIANZA!</h1>
            <a href="./tienda.php"><button>VER TIENDA</button></a>
        </div>
    </section>

    <section class="mas-vendidos">
        <h2>Más Vendidos</h2>
        <div class="contenedor-tarjetas">
            <article class="tarjeta">
                <img src="./assets/img/funko_zoro.jpg" alt="Funko Roronoa Zoro">
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
                <img src="./assets/img/katana_zoro.jpg" alt="Katana Enma de Zoro">
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
</main>

<?php require_once("includes/footer.php") ?>