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
    header('Location: ' . BASE_URL . 'admin/usuarios.php');
    exit;
}

// Buscar el usuario
$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id_usuario = :id");
$stmt->execute([':id' => $id]);
$usuario = $stmt->fetch();

if (!$usuario) {
    header('Location: ' . BASE_URL . 'admin/usuarios.php');
    exit;
}

// Procesar formulario de edición (POST)
$mensaje = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $rol    = trim($_POST['rol'] ?? '');
    $nueva_password = $_POST['nueva_password'] ?? '';

    if ($nombre === '' || $email === '') {
        $error = 'Los campos nombre y email son obligatorios.';
    } elseif (!in_array($rol, ['admin', 'cliente'])) {
        $error = 'El rol seleccionado no es válido.';
    } else {
        try {
            // Comprobar que el email no esté en uso por otro usuario
            $stmtCheck = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE email = :email AND id_usuario != :id LIMIT 1");
            $stmtCheck->execute([':email' => $email, ':id' => $id]);
            if ($stmtCheck->fetch()) {
                $error = 'Ya existe otro usuario con ese email.';
            } else {
                // Si se proporcionó nueva contraseña, actualizarla también
                if ($nueva_password !== '') {
                    if (strlen($nueva_password) < 6) {
                        $error = 'La contraseña debe tener al menos 6 caracteres.';
                    } else {
                        $hash = password_hash($nueva_password, PASSWORD_DEFAULT);
                        $stmt = $conexion->prepare("UPDATE usuarios SET nombre_usuario = :nombre, email = :email, rol = :rol, contraseña = :password WHERE id_usuario = :id");
                        $stmt->execute([
                            ':nombre'   => $nombre,
                            ':email'    => $email,
                            ':rol'      => $rol,
                            ':password' => $hash,
                            ':id'       => $id
                        ]);
                        $mensaje = 'Usuario actualizado correctamente (contraseña incluida).';
                    }
                } else {
                    $stmt = $conexion->prepare("UPDATE usuarios SET nombre_usuario = :nombre, email = :email, rol = :rol WHERE id_usuario = :id");
                    $stmt->execute([
                        ':nombre' => $nombre,
                        ':email'  => $email,
                        ':rol'    => $rol,
                        ':id'     => $id
                    ]);
                    $mensaje = 'Usuario actualizado correctamente.';
                }

                if (empty($error)) {
                    // Recargar datos actualizados
                    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id_usuario = :id");
                    $stmt->execute([':id' => $id]);
                    $usuario = $stmt->fetch();
                }
            }
        } catch (PDOException $e) {
            $error = 'Error al actualizar: ' . $e->getMessage();
        }
    }
}
?>

<main>
    <section class="admin-bienvenida">
        <h1><i class="fa-solid fa-user-pen"></i> Editar Usuario #<?= intval($usuario['id_usuario']) ?></h1>
        <p>Modifica los datos del usuario y guarda los cambios.</p>
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

            <div class="form-grid-layout">
                <div class="form-columna-principal">
                    <div class="admin-form-grupo">
                        <label for="nombre">Nombre de Usuario</label>
                        <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre_usuario']) ?>" placeholder="Ej: otaku_fan" required>
                    </div>

                    <div class="admin-form-grupo">
                        <label for="email">Email</label>
                        <div class="input-con-icono">
                            <i class="fa-solid fa-envelope"></i>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" placeholder="usuario@email.com" required>
                        </div>
                    </div>

                    <div class="admin-form-grupo">
                        <label for="nueva_password">Nueva Contraseña <small>(dejar vacío para no cambiar)</small></label>
                        <div class="input-con-icono">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" id="nueva_password" name="nueva_password" placeholder="Mínimo 6 caracteres">
                        </div>
                    </div>
                </div>

                <div class="form-columna-lateral">
                    <div class="admin-form-grupo">
                        <label for="rol">Rol</label>
                        <select id="rol" name="rol" required>
                            <option value="cliente" <?= $usuario['rol'] === 'cliente' ? 'selected' : '' ?>>Cliente</option>
                            <option value="admin" <?= $usuario['rol'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select>
                    </div>

                    <div class="admin-form-grupo info-usuario">
                        <label>Información</label>
                        <div class="usuario-info-card">
                            <div class="info-item">
                                <i class="fa-solid fa-fingerprint"></i>
                                <span>ID: <strong><?= intval($usuario['id_usuario']) ?></strong></span>
                            </div>
                            <div class="info-item">
                                <i class="fa-solid fa-shield-halved"></i>
                                <span>Rol actual: <strong><?= htmlspecialchars($usuario['rol']) ?></strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-form-acciones">
                <button type="submit" class="admin-btn btn-usuarios">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar cambios
                </button>
                <a href="<?= BASE_URL ?>admin/usuarios.php" class="admin-btn btn-cancelar">
                    <i class="fa-solid fa-arrow-left"></i> Volver
                </a>
            </div>

        </form>
    </section>
</main>

<script src="<?= BASE_URL ?>assets/js/admin_validacion.js"></script>
<?php require_once(__DIR__ . '/../includes/footer.php'); ?>
