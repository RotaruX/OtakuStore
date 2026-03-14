<?php
require_once(__DIR__ . '/../includes/conexion.php');
require_once(__DIR__ . '/../includes/header_admin.php');

if (session_status() === PHP_SESSION_NONE) session_start();

// Validar que el usuario sea administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ' . BASE_URL);
    exit;
}

$mensaje = '';
$error   = '';

// Lógica para añadir el producto cuando se reciba el POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre      = trim($_POST['nombre'] ?? '');
    $categoria   = trim($_POST['categoria'] ?? '');
    $precio      = floatval($_POST['precio'] ?? 0);
    $stock       = intval($_POST['stock'] ?? 0);
    $descripcion = trim($_POST['descripcion'] ?? '');
    $imagen      = '';

    // Validar campos obligatorios
    if ($nombre === '' || $categoria === '' || $precio <= 0 || $descripcion === '') {
        $error = 'Por favor, rellena todos los campos obligatorios (nombre, categoría, precio y descripción).';
    } else {
        // Manejar subida de imagen nueva a assets/img/
        if (isset($_FILES['imagen_archivo']) && $_FILES['imagen_archivo']['error'] === UPLOAD_ERR_OK) {
            $nombre_archivo = $_FILES['imagen_archivo']['name'];
            $ruta_temporal  = $_FILES['imagen_archivo']['tmp_name'];
            
            $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
            $extension_minusculas = strtolower($extension);

            $formatos_permitidos = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($extension_minusculas, $formatos_permitidos)) {
                $error = "Formato de imagen no válido. Solo se permiten JPG, PNG y WEBP.";
            } else {
                // Generar nombre en base a fecha/hora para no sobreescribir
                $nombre_unico = uniqid('prod_nuevo_') . '.' . $extension;
                $ruta_destino = __DIR__ . '/../assets/img/' . $nombre_unico;

                if (move_uploaded_file($ruta_temporal, $ruta_destino)) {
                    $imagen = $nombre_unico;
                } else {
                    $error = "Fallo al mover la imagen al directorio destino.";
                }
            }
        } else {
            $error = 'Es obligatorio adjuntar una foto para el producto nuevo.';
        }
    }

    // Insertar el registro directamente si no hubo errores de validación o imágenes
    if (empty($error)) {
        try {
            $stmt = $conexion->prepare("INSERT INTO productos (nombre, categoria, descripcion, imagen, precio, stock) VALUES (:nombre, :categoria, :descripcion, :imagen, :precio, :stock)");
            $stmt->execute([
                ':nombre'      => $nombre,
                ':categoria'   => $categoria,
                ':descripcion' => $descripcion,
                ':imagen'      => $imagen,
                ':precio'      => $precio,
                ':stock'       => $stock
            ]);
            $mensaje = 'El producto ha sido creado con éxito.';
            
            // Limpiar las variables del POST (evita repetición si recargan)
            unset($_POST);
            $nombre = $categoria = $descripcion = '';
            $precio = $stock = 0;
        } catch (PDOException $e) {
            $error = 'Error de base de datos al crear el producto: ' . $e->getMessage();
        }
    }
}
?>

<main>
    <section class="admin-bienvenida-nuevo">
        <h1><i class="fa-solid fa-square-plus"></i> Añadir Nuevo Producto</h1>
        <p>Rellena los datos para incorporar un producto al catálogo.</p>
    </section>

    <section class="admin-formulario-nuevo">

        <?php if ($mensaje): ?>
            <div class="admin-aviso aviso-correcto">
                <i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="admin-aviso aviso-error">
                <i class="fa-solid fa-circle-xmark"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="caja-formulario" enctype="multipart/form-data">

            <div class="distribucion-formulario">
                <div class="columna-principal">
                    <div class="grupo-campo">
                        <label for="nombre">Nombre del Producto</label>
                        <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($nombre ?? '') ?>" placeholder="Ej: Funko Pop Naruto" required>
                    </div>

                    <div class="grupo-campo">
                        <label for="descripcion">Descripción</label>
                        <textarea id="descripcion" name="descripcion" rows="6" placeholder="Detalles o sinopsis del producto..." required><?= htmlspecialchars($descripcion ?? '') ?></textarea>
                    </div>

                    <div class="fila-doble">
                        <div class="grupo-campo">
                            <label for="precio">Precio (€)</label>
                            <div class="entrada-con-icono">
                                <i class="fa-solid fa-euro-sign"></i>
                                <input type="number" id="precio" name="precio" step="0.01" min="0.01" value="<?= htmlspecialchars($precio > 0 ? $precio : '') ?>" placeholder="0.00" required>
                            </div>
                        </div>

                        <div class="grupo-campo">
                            <label for="stock">Stock Inicial</label>
                            <div class="entrada-con-icono">
                                <i class="fa-solid fa-boxes-stacked"></i>
                                <input type="number" id="stock" name="stock" min="1" value="<?= htmlspecialchars($stock > 0 ? $stock : '1') ?>" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="columna-secundaria">
                    <div class="grupo-campo">
                        <label for="categoria">Categoría</label>
                        <select id="categoria" name="categoria" required>
                            <option value="Funko" <?= (isset($categoria) && $categoria === 'Funko') ? 'selected' : '' ?>>Funko</option>
                            <option value="Cómic" <?= (isset($categoria) && $categoria === 'Cómic') ? 'selected' : '' ?>>Cómic</option>
                        </select>
                    </div>

                    <div class="grupo-campo">
                        <label>Foto Obligatoria</label>
                        
                        <label for="imagen_archivo" class="etiqueta-subir-archivo" style="margin-top: 5px;">
                            <i class="fa-solid fa-cloud-arrow-up"></i> Seleccionar Imagen
                            <input type="file" id="imagen_archivo" name="imagen_archivo" accept="image/jpeg, image/png, image/webp" class="entrada-subir-archivo" <?= empty($mensaje) ? 'required' : '' ?>>
                        </label>
                        <small class="nota-formulario" style="margin-top: 10px;">Formatos admitidos: JPG, PNG, WEBP. Límite: 2MB.</small>
                    </div>
                </div>
            </div>

            <div class="acciones-formulario">
                <button type="submit" class="boton-admin boton-agregar">
                    <i class="fa-solid fa-plus"></i> Añadir producto
                </button>
                <a href="<?= BASE_URL ?>admin/productos.php" class="boton-admin boton-volver">
                    <i class="fa-solid fa-arrow-left"></i> Volver
                </a>
            </div>

        </form>
    </section>
</main>

<?php require_once(__DIR__ . '/../includes/footer.php'); ?>
