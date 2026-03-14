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
    header('Location: ' . BASE_URL . 'admin/productos.php');
    exit;
}

// Buscar el producto
$stmt = $conexion->prepare("SELECT * FROM productos WHERE id_producto = :id");
$stmt->execute([':id' => $id]);
$producto = $stmt->fetch();

if (!$producto) {
    header('Location: ' . BASE_URL . 'admin/productos.php');
    exit;
}

// Procesar formulario de edición (POST)
$mensaje = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = trim($_POST['nombre'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $precio    = floatval($_POST['precio'] ?? 0);
    $stock     = intval($_POST['stock'] ?? 0);
    $imagen    = trim($_POST['imagen'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');

    if ($nombre === '' || $categoria === '' || $precio <= 0) {
        $error = 'Los campos nombre, categoría y precio son obligatorios.';
    } else {
        try {
            $stmt = $conexion->prepare("UPDATE productos SET nombre = :nombre, categoria = :categoria, precio = :precio, stock = :stock, imagen = :imagen, descripcion = :descripcion WHERE id_producto = :id");
            $stmt->execute([
                ':nombre'      => $nombre,
                ':categoria'   => $categoria,
                ':precio'      => $precio,
                ':stock'       => $stock,
                ':imagen'      => $imagen,
                ':descripcion' => $descripcion,
                ':id'          => $id
            ]);
            $mensaje = 'Producto actualizado correctamente.';

            // Recargar datos actualizados
            $stmt = $conexion->prepare("SELECT * FROM productos WHERE id_producto = :id");
            $stmt->execute([':id' => $id]);
            $producto = $stmt->fetch();
        } catch (PDOException $e) {
            $error = 'Error al actualizar: ' . $e->getMessage();
        }
    }
}
?>

<main>
    <section class="admin-bienvenida">
        <h1><i class="fa-solid fa-pen-to-square"></i> Editar Producto #<?= intval($producto['id_producto']) ?></h1>
        <p>Modifica los datos del producto y guarda los cambios.</p>
    </section>

    <section class="admin-form-wrapper">

        <?php if ($mensaje): ?>
            <div class="admin-alerta admin-alerta-ok">
                <i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="admin-alerta admin-alerta-error">
                <i class="fa-solid fa-circle-xmark"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="admin-form">

            <div class="admin-form-grupo">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
            </div>

            <div class="admin-form-grupo">
                <label for="categoria">Categoría</label>
                <select id="categoria" name="categoria" required>
                    <option value="Funko" <?= $producto['categoria'] === 'Funko' ? 'selected' : '' ?>>Funko</option>
                    <option value="Cómic" <?= $producto['categoria'] === 'Cómic' ? 'selected' : '' ?>>Manga</option>
                </select>
            </div>

            <div class="admin-form-grupo">
                <label for="precio">Precio (€)</label>
                <input type="number" id="precio" name="precio" step="0.01" min="0.01" value="<?= htmlspecialchars($producto['precio']) ?>" required>
            </div>

            <div class="admin-form-grupo">
                <label for="stock">Stock</label>
                <input type="number" id="stock" name="stock" min="0" value="<?= intval($producto['stock']) ?>">
            </div>

            <div class="admin-form-grupo">
                <label for="imagen">Imagen (nombre del archivo)</label>
                <input type="text" id="imagen" name="imagen" value="<?= htmlspecialchars($producto['imagen'] ?? '') ?>">
            </div>

            <div class="admin-form-grupo">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="5"><?= htmlspecialchars($producto['descripcion'] ?? '') ?></textarea>
            </div>

            <div class="admin-form-acciones">
                <button type="submit" class="admin-btn btn-productos">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar cambios
                </button>
                <a href="<?= BASE_URL ?>admin/productos.php" class="admin-btn btn-cancelar">
                    <i class="fa-solid fa-arrow-left"></i> Volver
                </a>
            </div>

        </form>
    </section>
</main>

<?php require_once(__DIR__ . '/../includes/footer.php'); ?>
