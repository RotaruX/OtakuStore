<?php
require_once(__DIR__ . '/../includes/conexion.php');
require_once(__DIR__ . '/../includes/header_admin.php');

if (session_status() === PHP_SESSION_NONE) session_start();

// Solo admins
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ' . BASE_URL);
    exit;
}

// Obtener ID por GET
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: ' . BASE_URL . 'admin/pedidos.php');
    exit;
}

// Buscar el pedido con datos del usuario
$stmt = $conexion->prepare("
    SELECT c.*, u.nombre_usuario, u.email
    FROM compras c
    INNER JOIN usuarios u ON u.id_usuario = c.id_usuario
    WHERE c.id_compra = :id
");
$stmt->execute([':id' => $id]);
$pedido = $stmt->fetch();

if (!$pedido) {
    header('Location: ' . BASE_URL . 'admin/pedidos.php');
    exit;
}

// Obtener los productos del pedido
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
$stmtItems->execute([':id_compra' => $id]);
$items = $stmtItems->fetchAll();

$estadoIcono = [
    'pendiente'  => ['icono' => 'fa-clock',       'clase' => 'estado-pendiente'],
    'en camino'  => ['icono' => 'fa-truck',        'clase' => 'estado-camino'],
    'enviado'    => ['icono' => 'fa-box-open',     'clase' => 'estado-enviado'],
    'entregado'  => ['icono' => 'fa-circle-check', 'clase' => 'estado-entregado'],
];

$estados = ['pendiente', 'en camino', 'enviado', 'entregado'];
$estadoActual = $pedido['estado'];
$icono = $estadoIcono[$estadoActual] ?? ['icono' => 'fa-question', 'clase' => ''];
$fecha = date('d/m/Y H:i', strtotime($pedido['fecha_compra']));
?>

<main>
    <section class="admin-bienvenida">
        <h1><i class="fa-solid fa-receipt"></i> Pedido #<?= intval($pedido['id_compra']) ?></h1>
        <p>Consulta el detalle del pedido y actualiza su estado.</p>
    </section>

    <section class="admin-form-wrapper">

        <!-- Información del pedido -->
        <div class="pedido-detalle-grid">

            <!-- Columna izquierda: info + productos -->
            <div class="pedido-detalle-principal">

                <!-- Info del cliente y pedido -->
                <div class="pedido-info-card">
                    <h3><i class="fa-solid fa-circle-info"></i> Información del Pedido</h3>
                    <div class="pedido-info-datos">
                        <div class="info-item">
                            <i class="fa-solid fa-user"></i>
                            <span>Cliente: <strong><?= htmlspecialchars($pedido['nombre_usuario']) ?></strong></span>
                        </div>
                        <div class="info-item">
                            <i class="fa-solid fa-envelope"></i>
                            <span>Email: <strong><?= htmlspecialchars($pedido['email']) ?></strong></span>
                        </div>
                        <div class="info-item">
                            <i class="fa-regular fa-calendar"></i>
                            <span>Fecha: <strong><?= $fecha ?></strong></span>
                        </div>
                        <div class="info-item">
                            <i class="fa-solid fa-euro-sign"></i>
                            <span>Total: <strong><?= number_format($pedido['total'], 2, ',', '.') ?> €</strong></span>
                        </div>
                    </div>
                </div>

                <!-- Productos del pedido -->
                <div class="pedido-productos-card">
                    <h3><i class="fa-solid fa-bag-shopping"></i> Productos (<?= count($items) ?>)</h3>
                    <div class="pedido-productos-lista">
                        <?php foreach ($items as $item): ?>
                            <div class="pedido-producto-item">
                                <?php if (!empty($item['imagen'])): ?>
                                    <img src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($item['imagen']) ?>"
                                         alt="<?= htmlspecialchars($item['nombre']) ?>"
                                         class="pedido-producto-img">
                                <?php else: ?>
                                    <div class="pedido-producto-sin-img">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="pedido-producto-info">
                                    <p class="pedido-producto-nombre"><?= htmlspecialchars($item['nombre']) ?></p>
                                    <p class="pedido-producto-detalle">
                                        <?= $item['cantidad'] ?> ud. × <?= number_format($item['precio_unitario'], 2, ',', '.') ?> €
                                    </p>
                                </div>
                                <span class="pedido-producto-subtotal">
                                    <?= number_format($item['subtotal'], 2, ',', '.') ?> €
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="pedido-productos-total">
                        <span>Total del pedido</span>
                        <strong><?= number_format($pedido['total'], 2, ',', '.') ?> €</strong>
                    </div>
                </div>
            </div>

            <!-- Columna derecha: estado -->
            <div class="pedido-detalle-lateral">
                <div class="pedido-estado-card">
                    <h3><i class="fa-solid fa-arrows-rotate"></i> Estado del Pedido</h3>

                    <div class="pedido-estado-actual">
                        <span class="admin-badge <?= $icono['clase'] ?>" id="badgeEstado">
                            <i class="fa-solid <?= $icono['icono'] ?>"></i> <?= ucfirst($estadoActual) ?>
                        </span>
                    </div>

                    <!-- Barra de progreso -->
                    <div class="pedido-progreso-admin">
                        <?php foreach ($estados as $i => $paso):
                            $actual = array_search($estadoActual, $estados);
                        ?>
                            <div class="paso <?= $i <= $actual ? 'paso-activo' : '' ?>">
                                <div class="paso-circulo"></div>
                                <span><?= ucfirst($paso) ?></span>
                            </div>
                            <?php if ($i < count($estados) - 1): ?>
                                <div class="paso-linea <?= $i < $actual ? 'paso-linea-activa' : '' ?>"></div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>

                    <!-- Selector de nuevo estado -->
                    <div class="pedido-cambiar-estado">
                        <label for="nuevoEstado">Cambiar estado:</label>
                        <select id="nuevoEstado">
                            <?php foreach ($estados as $e): ?>
                                <option value="<?= $e ?>" <?= $e === $estadoActual ? 'selected' : '' ?>>
                                    <?= ucfirst($e) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button class="admin-btn btn-pedidos" id="btnActualizarEstado" data-id="<?= $id ?>">
                            <i class="fa-solid fa-floppy-disk"></i> Actualizar
                        </button>
                    </div>

                    <div class="pedido-estado-mensaje" id="mensajeEstado"></div>
                </div>

                <a href="<?= BASE_URL ?>admin/pedidos.php" class="admin-btn btn-cancelar pedido-volver-desktop">
                    <i class="fa-solid fa-arrow-left"></i> Volver a Pedidos
                </a>
            </div>

        </div>

        <a href="<?= BASE_URL ?>admin/pedidos.php" class="admin-btn btn-cancelar pedido-volver-mobile">
            <i class="fa-solid fa-arrow-left"></i> Volver a Pedidos
        </a>

    </section>
</main>

<script>
    const BASE_URL = "<?= BASE_URL ?>";
</script>
<script src="<?= BASE_URL ?>assets/js/admin_pedidos.js"></script>
<?php require_once(__DIR__ . '/../includes/footer.php'); ?>
