<?php
require_once(__DIR__ . '/../includes/conexion.php');
require_once(__DIR__ . '/../includes/header_admin.php');

// Obtener todos los usuarios
$stmt = $conexion->query("SELECT id_usuario, nombre_usuario, email, rol FROM usuarios ORDER BY id_usuario ASC");
$usuarios = $stmt->fetchAll();
?>

<main>
    <section class="admin-bienvenida">
        <h1><i class="fa-solid fa-users"></i> Gestión de Usuarios</h1>
        <p>Consulta, edita y elimina las cuentas registradas.</p>
    </section>

    <section class="admin-tabla-wrapper">

        <div class="admin-tabla-top">
            <p class="admin-tabla-contador">
                <strong><?= count($usuarios) ?></strong> usuarios en total
            </p>
        </div>

        <div class="admin-tabla-scroll">
            <table class="admin-tabla" id="tablaUsuarios">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($usuarios)): ?>
                        <tr>
                            <td colspan="5" class="admin-tabla-vacia">No hay usuarios registrados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($usuarios as $u):
                            $id     = intval($u['id_usuario']);
                            $nombre = htmlspecialchars($u['nombre_usuario']);
                            $email  = htmlspecialchars($u['email']);
                            $rol    = htmlspecialchars($u['rol']);
                        ?>
                            <tr>
                                <td data-label="ID"><?= $id ?></td>
                                <td data-label="Nombre"><?= $nombre ?></td>
                                <td data-label="Email"><?= $email ?></td>
                                <td data-label="Rol">
                                    <span class="admin-badge <?= $rol === 'admin' ? 'badge-admin' : 'badge-cliente' ?>">
                                        <?= $rol === 'admin' ? 'Admin' : 'Cliente' ?>
                                    </span>
                                </td>
                                <td data-label="Acciones">
                                    <div class="admin-acciones-btns">
                                        <a href="<?= BASE_URL ?>admin/editar_usuario.php?id=<?= $id ?>" class="admin-btn-accion btn-editar" title="Editar usuario">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <?php if ($id !== intval($_SESSION['id_usuario'])): ?>
                                            <button class="admin-btn-accion btn-eliminar" data-id="<?= $id ?>" data-nombre="<?= $nombre ?>" title="Eliminar usuario">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        <?php endif; ?>
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
            ¿Seguro que quieres eliminar al usuario <strong id="modalNombreUsuario"></strong>?
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
<script src="<?= BASE_URL ?>assets/js/admin_usuarios.js"></script>
<?php require_once(__DIR__ . '/../includes/footer.php'); ?>
