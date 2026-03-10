<?php
require_once(__DIR__ . '/../config/parametros.php');
require_once(__DIR__ . '/../includes/header_admin.php');
?>

<main>
    <!-- Bienvenida -->
    <section class="admin-bienvenida">
        <h1><i class="fa-solid fa-shield-halved"></i> Panel de Administración</h1>
        <p>Bienvenido, administrador. Gestiona tu tienda desde aquí.</p>
    </section>

    <!-- Tarjetas de gestión -->
    <section class="admin-secciones">

        <!-- Gestión de Pedidos -->
        <article class="admin-card card-pedidos">
            <div class="admin-card-header">
                <div class="admin-card-icono pedidos">
                    <i class="fa-solid fa-box"></i>
                </div>
                <h2>Gestión de Pedidos <span>Controla el estado de los envíos</span></h2>
            </div>
            <div class="admin-card-body">
                <ul class="admin-acciones">
                    <li>
                        <i class="fa-solid fa-arrows-rotate"></i>
                        Modificar estado de pedidos
                    </li>
                    <li>
                        <i class="fa-solid fa-rotate-left"></i>
                        Gestionar devoluciones
                    </li>
                    <li>
                        <i class="fa-solid fa-magnifying-glass"></i>
                        Consultar historial de compras
                    </li>
                </ul>
            </div>
            <div class="admin-card-footer">
                <a href="<?= BASE_URL ?>admin/pedidos.php" class="admin-btn btn-pedidos">
                    <i class="fa-solid fa-arrow-right"></i> Ir a Pedidos
                </a>
            </div>
        </article>

        <!-- Gestión de Usuarios -->
        <article class="admin-card card-usuarios">
            <div class="admin-card-header">
                <div class="admin-card-icono usuarios">
                    <i class="fa-solid fa-users"></i>
                </div>
                <h2>Gestión de Usuarios <span>Administra las cuentas registradas</span></h2>
            </div>
            <div class="admin-card-body">
                <ul class="admin-acciones">
                    <li>
                        <i class="fa-solid fa-user-slash"></i>
                        Dar de baja usuarios
                    </li>
                    <li>
                        <i class="fa-solid fa-list-check"></i>
                        Ver listado de usuarios
                    </li>
                    <li>
                        <i class="fa-solid fa-user-shield"></i>
                        Gestionar roles y permisos
                    </li>
                </ul>
            </div>
            <div class="admin-card-footer">
                <a href="<?= BASE_URL ?>admin/usuarios.php" class="admin-btn btn-usuarios">
                    <i class="fa-solid fa-arrow-right"></i> Ir a Usuarios
                </a>
            </div>
        </article>

        <!-- Gestión de Productos -->
        <article class="admin-card card-productos">
            <div class="admin-card-header">
                <div class="admin-card-icono productos">
                    <i class="fa-solid fa-tags"></i>
                </div>
                <h2>Gestión de Productos <span>Administra el catálogo de la tienda</span></h2>
            </div>
            <div class="admin-card-body">
                <ul class="admin-acciones">
                    <li>
                        <i class="fa-solid fa-plus"></i>
                        Añadir nuevos productos
                    </li>
                    <li>
                        <i class="fa-solid fa-pen-to-square"></i>
                        Editar productos existentes
                    </li>
                    <li>
                        <i class="fa-solid fa-trash-can"></i>
                        Eliminar productos
                    </li>
                </ul>
            </div>
            <div class="admin-card-footer">
                <a href="<?= BASE_URL ?>admin/productos.php" class="admin-btn btn-productos">
                    <i class="fa-solid fa-arrow-right"></i> Ir a Productos
                </a>
            </div>
        </article>

    </section>
</main>

<?php require_once(__DIR__ . '/../includes/footer.php'); ?>