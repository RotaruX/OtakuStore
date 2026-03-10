<?php
require_once(__DIR__ . '/../includes/conexion.php');
require_once(__DIR__ . '/../includes/header_admin.php');

// Obtener todos los productos
$stmt = $conexion->query("SELECT id_producto, nombre, categoria, imagen, precio, stock, fecha_incorporacion FROM productos ORDER BY id_producto ASC");
$productos = $stmt->fetchAll();
?>

<main>
    <section class="admin-bienvenida">
        <h1><i class="fa-solid fa-tags"></i> Gestión de Productos</h1>
        <p>Consulta, añade, edita y elimina productos del catálogo.</p>
    </section>

    <section class="admin-tabla-wrapper">

        <div class="admin-tabla-top">
            <p class="admin-tabla-contador">
                <strong><?= count($productos) ?></strong> productos en total
            </p>
            <a href="<?= BASE_URL ?>admin/nuevo_producto.php" class="admin-btn btn-productos btn-sm">
                <i class="fa-solid fa-plus"></i> Añadir producto
            </a>
        </div>

        <div class="admin-tabla-scroll">
            <table class="admin-tabla" id="tablaProductos">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($productos)): ?>
                        <tr>
                            <td colspan="7" class="admin-tabla-vacia">No hay productos registrados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($productos as $p):
                            $id      = intval($p['id_producto']);
                            $nombre  = htmlspecialchars($p['nombre']);
                            $cat     = htmlspecialchars($p['categoria']);
                            $imagen  = htmlspecialchars($p['imagen'] ?? '');
                            $precio  = number_format(floatval($p['precio']), 2, ',', '.');
                            $stock   = intval($p['stock']);
                        ?>
                            <tr>
                                <td data-label="ID"><?= $id ?></td>
                                <td data-label="Imagen">
                                    <?php if ($imagen !== ''): ?>
                                        <a href="<?= BASE_URL ?>assets/img/<?= $imagen ?>" target="_blank" class="admin-img-link" title="Ver imagen en grande">
                                            <?= $imagen ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="admin-sin-imagen">Sin imagen</span>
                                    <?php endif; ?>
                                </td>
                                <td data-label="Nombre"><?= $nombre ?></td>
                                <td data-label="Categoría">
                                    <span class="admin-badge <?= $cat === 'Funko' ? 'badge-funko' : 'badge-comic' ?>">
                                        <?= $cat === 'Cómic' ? 'Manga' : 'Funko' ?>
                                    </span>
                                </td>
                                <td data-label="Precio"><?= $precio ?> €</td>
                                <td data-label="Stock">
                                    <span class="admin-stock <?= $stock <= 0 ? 'stock-agotado' : ($stock <= 5 ? 'stock-bajo' : '') ?>">
                                        <?= $stock ?>
                                    </span>
                                </td>
                                <td data-label="Acciones">
                                    <div class="admin-acciones-btns">
                                        <a href="<?= BASE_URL ?>admin/editar_producto.php?id=<?= $id ?>" class="admin-btn-accion btn-editar" title="Editar producto">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <button class="admin-btn-accion btn-eliminar" data-id="<?= $id ?>" data-nombre="<?= $nombre ?>" title="Eliminar producto">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
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

<!-- Modal de confirmación de eliminación -->
<div class="admin-modal-overlay" id="modalEliminar">
    <div class="admin-modal">
        <div class="admin-modal-header">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <h3>Confirmar eliminación</h3>
        </div>
        <p class="admin-modal-texto">
            ¿Seguro que quieres eliminar <strong id="modalNombreProducto"></strong>?
        </p>
        <div class="admin-modal-botones">
            <button class="admin-btn btn-cancelar" id="modalCancelar">Cancelar</button>
            <button class="admin-btn btn-confirmar-eliminar" id="modalConfirmar">Eliminar</button>
        </div>
    </div>
</div>

<script>
    const BASE_URL = "<?= BASE_URL ?>";
</script>
<script src="<?= BASE_URL ?>assets/js/admin_productos.js"></script>
<?php require_once(__DIR__ . '/../includes/footer.php'); ?>
