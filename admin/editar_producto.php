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
            // Manejar subida de imagen nueva si se proporcionó
            if (isset($_FILES['imagen_archivo']) && $_FILES['imagen_archivo']['error'] === UPLOAD_ERR_OK) {
                $nombre_archivo = $_FILES['imagen_archivo']['name'];
                $ruta_temporal  = $_FILES['imagen_archivo']['tmp_name'];
                
                // Generar un nombre único para evitar colisiones
                $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
                $nombre_unico = uniqid('prod_') . '.' . $extension;
                $ruta_destino = __DIR__ . '/../assets/img/' . $nombre_unico;

                if (move_uploaded_file($ruta_temporal, $ruta_destino)) {
                    $imagen = $nombre_unico;
                } else {
                    $error = "Error al mover la imagen subida.";
                }
            } else {
                // Si no se subió una nueva imagen, mantener el nombre actual (pasado en input hidden si es necesario, o usando el valor de la BD)
                $imagen = $_POST['imagen_actual'] ?? $producto['imagen'];
            }

            if (empty($error)) {
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
            }
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

        <form method="POST" class="admin-form" enctype="multipart/form-data">

            <div class="form-grid-layout">
                <div class="form-columna-principal">
                    <div class="admin-form-grupo">
                        <label for="nombre">Nombre del Producto</label>
                        <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" placeholder="Ej: Funko Pop Goku" required>
                    </div>

                    <div class="admin-form-grupo">
                        <label for="descripcion">Descripción</label>
                        <textarea id="descripcion" name="descripcion" rows="6" placeholder="Detalles del producto..."><?= htmlspecialchars($producto['descripcion'] ?? '') ?></textarea>
                    </div>

                    <div class="form-fila-doble">
                        <div class="admin-form-grupo">
                            <label for="precio">Precio (€)</label>
                            <div class="input-con-icono">
                                <i class="fa-solid fa-euro-sign"></i>
                                <input type="number" id="precio" name="precio" step="0.01" min="0.01" value="<?= htmlspecialchars($producto['precio']) ?>" required>
                            </div>
                        </div>

                        <div class="admin-form-grupo">
                            <label for="stock">Stock</label>
                            <div class="input-con-icono">
                                <i class="fa-solid fa-boxes-stacked"></i>
                                <input type="number" id="stock" name="stock" min="0" value="<?= intval($producto['stock']) ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-columna-lateral">
                    <div class="admin-form-grupo">
                        <label>Imagen Actual</label>
                        <div class="imagen-preview-container">
                            <?php if (!empty($producto['imagen'])): ?>
                                <img src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($producto['imagen']) ?>" alt="Imagen actual del producto" class="imagen-preview" id="previewImg">
                            <?php else: ?>
                                <div class="imagen-placeholder" id="previewPlaceholder">
                                    <i class="fa-solid fa-image"></i>
                                    <span>Sin imagen</span>
                                </div>
                                <img src="" alt="Previsualización" class="imagen-preview oculta" id="previewImg">
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="admin-form-grupo">
                        <label for="imagen_archivo" class="file-upload-label">
                            <i class="fa-solid fa-cloud-arrow-up"></i> Cambiar Imagen
                            <input type="file" id="imagen_archivo" name="imagen_archivo" accept="image/jpeg, image/png, image/webp" class="file-upload-input" onchange="previewFile()">
                        </label>
                        <input type="hidden" name="imagen_actual" value="<?= htmlspecialchars($producto['imagen'] ?? '') ?>">
                        <small class="form-nota">Formatos: JPG, PNG, WEBP. Max: 2MB.</small>
                    </div>

                    <div class="admin-form-grupo">
                        <label for="categoria">Categoría</label>
                        <select id="categoria" name="categoria" required>
                            <option value="Funko" <?= $producto['categoria'] === 'Funko' ? 'selected' : '' ?>>Funko</option>
                            <option value="Cómic" <?= $producto['categoria'] === 'Cómic' ? 'selected' : '' ?>>Cómic</option>
                        </select>
                    </div>
                </div>
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

<script>
function previewFile() {
    const preview = document.getElementById('previewImg');
    const file = document.getElementById('imagen_archivo').files[0];
    const reader = new FileReader();

    reader.addEventListener("load", function () {
        preview.src = reader.result;
        preview.classList.remove('oculta');
        const placeholder = document.getElementById('previewPlaceholder');
        if (placeholder) {
            placeholder.style.display = 'none';
        }
    }, false);

    if (file) {
        reader.readAsDataURL(file);
    }
}
</script>

<?php require_once(__DIR__ . '/../includes/footer.php'); ?>
