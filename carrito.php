<?php
require_once(__DIR__ . "/includes/conexion.php");
session_start();

// Redirigir al login si no está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php?redir=carrito");
    exit;
}

$id_usuario     = intval($_SESSION['id_usuario']);
$nombre_usuario = htmlspecialchars($_SESSION['nombre_usuario'] ?? 'Usuario');

// Obtener productos del carrito con datos del producto
$stmt = $conexion->prepare("
    SELECT
        c.id_producto,
        c.cantidad,
        p.nombre,
        p.precio,
        p.imagen,
        p.stock,
        (c.cantidad * p.precio) AS total_linea
    FROM carrito c
    INNER JOIN productos p ON p.id_producto = c.id_producto
    WHERE c.id_usuario = :id_usuario
    ORDER BY c.id_producto ASC
");
$stmt->execute([':id_usuario' => $id_usuario]);
$items = $stmt->fetchAll();

$total_general = array_sum(array_column($items, 'total_linea'));
?>
<?php require_once(__DIR__ . "/includes/header.php") ?>

<main class="carrito-page">

    <section class="carrito-cabecera">
        <h1>Mi Carrito</h1>
        <p class="carrito-saludo">
            <i class="fa-solid fa-user"></i>
            Hola, <strong><?= $nombre_usuario ?></strong>
        </p>
        <a href="pedidos.php" class="btn-mis-pedidos">
            <i class="fa-solid fa-box-open"></i> Mis pedidos
        </a>
    </section>


    <?php if (empty($items)): ?>
        <section class="carrito-vacio">
            <i class="fa-solid fa-cart-xmark"></i>
            <h2>Tu carrito está vacío</h2>
            <p>Aún no has añadido ningún producto al carrito.</p>
            <a href="tienda.php" class="btn-carrito-ir">Ir a la tienda</a>
        </section>
    <?php else: ?>

        <section class="carrito-grid" id="carritoGrid">
            <?php foreach ($items as $item): ?>
                <?php
                    $imagen     = htmlspecialchars($item['imagen'] ?? '');
                    $nombre     = htmlspecialchars($item['nombre']);
                    $cantidad   = intval($item['cantidad']);
                    $precio     = floatval($item['precio']);
                    $total_item = floatval($item['total_linea']);
                    $id_prod    = intval($item['id_producto']);
                    $stock      = intval($item['stock'] ?? 99);
                ?>
                <article class="tarjeta-carrito"
                         data-id="<?= $id_prod ?>"
                         data-total="<?= number_format($total_item, 2, '.', '') ?>"
                         data-precio="<?= number_format($precio, 2, '.', '') ?>"
                         data-stock="<?= $stock ?>">

                    <label class="carrito-checkbox" title="Seleccionar">
                        <input type="checkbox" class="check-item" checked>
                        <span class="check-custom"><i class="fa-solid fa-check"></i></span>
                    </label>

                    <div class="tarjeta-imagen">
                        <?php if (!empty($imagen)): ?>
                            <img src="<?= BASE_URL ?>assets/img/<?= $imagen ?>" alt="<?= $nombre ?>">
                        <?php else: ?>
                            <div class="sin-imagen"><i class="fa-solid fa-image"></i></div>
                        <?php endif; ?>
                    </div>

                    <div class="tarjeta-info">
                        <h3 class="tarjeta-nombre"><?= $nombre ?></h3>

                        <div class="tarjeta-detalles">
                            <div class="cantidad-control">
                                <button class="btn-cantidad btn-menos" data-id="<?= $id_prod ?>" title="Reducir cantidad">
                                    <i class="fa-solid fa-minus"></i>
                                </button>
                                <span class="cantidad-valor"><?= $cantidad ?></span>
                                <button class="btn-cantidad btn-mas" data-id="<?= $id_prod ?>" title="Aumentar cantidad">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                            <span class="tarjeta-precio-unit">
                                <?= number_format($precio, 2, ',', '.') ?> € / ud.
                            </span>
                        </div>

                        <p class="tarjeta-total">
                            Total: <strong class="total-linea"><?= number_format($total_item, 2, ',', '.') ?> €</strong>
                        </p>
                    </div>

                    <div class="tarjeta-acciones">
                        <button class="btn-comprar-uno"
                                data-id="<?= $id_prod ?>"
                                data-nombre="<?= $nombre ?>">
                            <i class="fa-solid fa-bolt"></i> Comprar este
                        </button>
                        <button class="btn-eliminar"
                                data-id="<?= $id_prod ?>"
                                title="Eliminar del carrito">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </article>

            <?php endforeach; ?>
        </section>

        <!-- Resumen final -->
        <section class="carrito-resumen" id="carritoResumen">
            <div class="resumen-info">
                <span class="resumen-label">Total seleccionado:</span>
                <span class="resumen-total" id="precioTotal">
                    <?= number_format($total_general, 2, ',', '.') ?> €
                </span>
            </div>
            <div class="resumen-botones">
                <button class="btn-comprar-seleccion" id="btnComprarSeleccion">
                    <i class="fa-solid fa-check-double"></i> Comprar seleccionados
                </button>
                <button class="btn-comprar-todo" id="btnComprarTodo">
                    <i class="fa-solid fa-bag-shopping"></i> Comprar todo
                </button>
            </div>
        </section>

    <?php endif; ?>
</main>

<script src="<?= BASE_URL ?>assets/js/carrito_page.js"></script>
<?php require_once(__DIR__ . "/includes/footer.php") ?>
