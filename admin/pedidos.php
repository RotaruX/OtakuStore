<?php
require_once(__DIR__ . '/../includes/conexion.php');
require_once(__DIR__ . '/../includes/header_admin.php');

// Obtener todos los pedidos con el nombre del usuario
$stmt = $conexion->query("
    SELECT c.id_compra, c.fecha_compra, c.estado, c.total, u.nombre_usuario
    FROM compras c
    INNER JOIN usuarios u ON u.id_usuario = c.id_usuario
    ORDER BY c.fecha_compra DESC
");
$pedidos = $stmt->fetchAll();

$estadoIcono = [
    'pendiente'  => ['icono' => 'fa-clock',       'clase' => 'estado-pendiente'],
    'en camino'  => ['icono' => 'fa-truck',        'clase' => 'estado-camino'],
    'enviado'    => ['icono' => 'fa-box-open',     'clase' => 'estado-enviado'],
    'entregado'  => ['icono' => 'fa-circle-check', 'clase' => 'estado-entregado'],
];
?>

<main>
    <section class="admin-bienvenida">
        <h1><i class="fa-solid fa-box"></i> Gestión de Pedidos</h1>
        <p>Consulta y gestiona el estado de todos los pedidos.</p>
    </section>

    <section class="admin-tabla-wrapper">

        <div class="admin-tabla-top">
            <p class="admin-tabla-contador">
                <strong><?= count($pedidos) ?></strong> pedidos en total
            </p>
        </div>

        <div class="admin-tabla-scroll">
            <table class="admin-tabla" id="tablaPedidos">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pedidos)): ?>
                        <tr>
                            <td colspan="6" class="admin-tabla-vacia">No hay pedidos registrados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pedidos as $p):
                            $id      = intval($p['id_compra']);
                            $cliente = htmlspecialchars($p['nombre_usuario']);
                            $fecha   = date('d/m/Y H:i', strtotime($p['fecha_compra']));
                            $total   = number_format(floatval($p['total']), 2, ',', '.');
                            $estado  = $p['estado'];
                            $icono   = $estadoIcono[$estado] ?? ['icono' => 'fa-question', 'clase' => ''];
                        ?>
                            <tr>
                                <td data-label="ID"><?= $id ?></td>
                                <td data-label="Cliente"><?= $cliente ?></td>
                                <td data-label="Fecha">
                                    <span class="pedido-fecha-tabla">
                                        <i class="fa-regular fa-calendar"></i> <?= $fecha ?>
                                    </span>
                                </td>
                                <td data-label="Total"><?= $total ?> €</td>
                                <td data-label="Estado">
                                    <span class="admin-badge <?= $icono['clase'] ?>">
                                        <i class="fa-solid <?= $icono['icono'] ?>"></i> <?= ucfirst($estado) ?>
                                    </span>
                                </td>
                                <td data-label="Acciones">
                                    <div class="admin-acciones-btns">
                                        <a href="<?= BASE_URL ?>admin/ver_pedido.php?id=<?= $id ?>" class="admin-btn-accion btn-ver" title="Ver detalle del pedido">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </section>
</main>

<?php require_once(__DIR__ . '/../includes/footer.php'); ?>
