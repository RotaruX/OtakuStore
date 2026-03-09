<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . '/../config/parametros.php');

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ' . BASE_URL . 'login.php');
    exit;
}

$paginaAdmin = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OtakuStore - Admin</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/estilos.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>

<body>
    <header>
        <a href="<?= BASE_URL ?>admin/index.php"><img src="<?= BASE_URL ?>assets/img/Logo.png"></a>

        <section class="menu" id="menu">
            <ul>
                <li><a href="<?= BASE_URL ?>admin/index.php" class="<?= $paginaAdmin == 'index.php' ? 'activo' : '' ?>">Panel</a></li>
                <li><a href="<?= BASE_URL ?>admin/alta_producto.php" class="<?= $paginaAdmin == 'alta_producto.php' ? 'activo' : '' ?>">Productos</a></li>
                <li><a href="<?= BASE_URL ?>admin/usuarios.php" class="<?= $paginaAdmin == 'usuarios.php' ? 'activo' : '' ?>">Usuarios</a></li>
            </ul>
            <section class="usuarios">
                <a href="<?= BASE_URL ?>index.php" title="Ir a la Tienda"><i class="fa-solid fa-store"></i></a>
                <a href="<?= BASE_URL ?>logout.php" title="Cerrar Sesión"><i class="fa-solid fa-right-from-bracket"></i></a>
            </section>
        </section>

        <button class="hamburguesa" id="botonHamburguesa">
            <i class="fa-solid fa-bars"></i>
        </button>

    </header>
