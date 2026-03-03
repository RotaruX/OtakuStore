<?php
require_once("includes/conexion.php");
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php?redir=pedidos");
    exit;
}

$id_usuario     = intval($_SESSION['id_usuario']);
$nombre_usuario = htmlspecialchars($_SESSION['nombre_usuario'] ?? 'Usuario');

$stmt = $conexion->prepare("
    SELECT id_compra, fecha_compra, estado, total
    FROM compras
    WHERE id_usuario = :id_usuario
    ORDER BY fecha_compra DESC
");
$stmt->execute([':id_usuario' => $id_usuario]);
$pedidos = $stmt->fetchAll();

$stmtItems = $conexion->prepare("
    SELECT
        dc.cantidad,
        dc.precio_unitario,
        p.nombre,
        p.imagen,
        (dc.cantidad * dc.precio_unitario) AS subtotal
    FROM detalles_compra dc
    INNER JOIN productos p ON p.id_producto = dc.id_producto
    WHERE dc.id_compra = :id_compra
");

$estadoIcono = [
    'pendiente'  => ['icono' => 'fa-clock',       'clase' => 'estado-pendiente'],
    'en camino'  => ['icono' => 'fa-truck',        'clase' => 'estado-camino'],
    'enviado'    => ['icono' => 'fa-box-open',     'clase' => 'estado-enviado'],
    'entregado'  => ['icono' => 'fa-circle-check', 'clase' => 'estado-entregado'],
];
?>
<?php require_once("includes/header.php") ?>

<main class="pedidos-page">

    <section class="pedidos-cabecera">
        <h1>Mis Pedidos</h1>
        <p class="pedidos-saludo">
            <i class="fa-solid fa-user"></i>
            Hola, <strong><?= $nombre_usuario ?></strong>
        </p>
    </section>

    <?php if (empty($pedidos)): ?>
        <section class="pedidos-vacio">
            <i class="fa-solid fa-box-open"></i>
            <h2>Aún no tienes pedidos</h2>
            <p>Cuando realices una compra, aparecerá aquí.</p>
            <a href="tienda.php" class="btn-pedidos-ir">Ir a la tienda</a>
        </section>
    <?php else: ?>

        <section class="pedidos-lista">
            <?php foreach ($pedidos as $pedido):
                $stmtItems->execute([':id_compra' => $pedido['id_compra']]);
                $items   = $stmtItems->fetchAll();
                $estado  = $pedido['estado'];
                $icono   = $estadoIcono[$estado] ?? ['icono' => 'fa-question', 'clase' => ''];
                $fecha   = date('d/m/Y H:i', strtotime($pedido['fecha_compra']));
            ?>
                <article class="pedido-card">
                    <div class="pedido-header">
                        <div class="pedido-meta">
                            <span class="pedido-id"># <?= $pedido['id_compra'] ?></span>
                            <span class="pedido-fecha">
                                <i class="fa-regular fa-calendar"></i> <?= $fecha ?>
                            </span>
                        </div>
                        <span class="pedido-estado <?= $icono['clase'] ?>">
                            <i class="fa-solid <?= $icono['icono'] ?>"></i>
                            <?= ucfirst($estado) ?>
                        </span>
                    </div>

                    <div class="pedido-progreso">
                        <?php
                        $pasos  = ['pendiente', 'en camino', 'enviado', 'entregado'];
                        $actual = array_search($estado, $pasos);
                        ?>
                        <?php foreach ($pasos as $i => $paso): ?>
                            <div class="paso <?= $i <= $actual ? 'paso-activo' : '' ?>">
                                <div class="paso-circulo"></div>
                                <span><?= ucfirst($paso) ?></span>
                            </div>
                            <?php if ($i < count($pasos) - 1): ?>
                                <div class="paso-linea <?= $i < $actual ? 'paso-linea-activa' : '' ?>"></div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>

                    <div class="pedido-items">
                        <?php foreach ($items as $item): ?>
                            <div class="pedido-item">
                                <?php if (!empty($item['imagen'])): ?>
                                    <img src="./assets/img/<?= htmlspecialchars($item['imagen']) ?>"
                                         alt="<?= htmlspecialchars($item['nombre']) ?>">
                                <?php else: ?>
                                    <div class="item-sin-imagen"><i class="fa-solid fa-image"></i></div>
                                <?php endif; ?>
                                <div class="item-info">
                                    <p class="item-nombre"><?= htmlspecialchars($item['nombre']) ?></p>
                                    <p class="item-detalle">
                                        <?= $item['cantidad'] ?> ud. × <?= number_format($item['precio_unitario'], 2, ',', '.') ?> €
                                    </p>
                                </div>
                                <span class="item-subtotal">
                                    <?= number_format($item['subtotal'], 2, ',', '.') ?> €
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="pedido-footer">
                        <span class="pedido-total-label">Total del pedido</span>
                        <span class="pedido-total-valor">
                            <?= number_format($pedido['total'], 2, ',', '.') ?> €
                        </span>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>

    <?php endif; ?>
</main>

<?php require_once("includes/footer.php") ?>
