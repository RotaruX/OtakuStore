<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OtakuStore</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <?php
    $paginaActual = basename($_SERVER['PHP_SELF']);
    if ($paginaActual == 'index.php'): ?>
        <link rel="stylesheet" href="./assets/css/inicio.css">
    <?php
    elseif ($paginaActual == 'tienda.php'): ?>
        <link rel="stylesheet" href="./assets/css/tienda.css">
    <?php
    elseif ($paginaActual == 'contacto.php'): ?>
        <link rel="stylesheet" href="./assets/css/contacto.css">
    <?php
    elseif ($paginaActual == 'ferias.php'): ?>
        <link rel="stylesheet" href="./assets/css/ferias.css">
    <?php
    elseif ($paginaActual == 'login.php' || $paginaActual == 'registro.php'): ?>
        <link rel="stylesheet" href="./assets/css/login.css">
    <?php
    elseif ($paginaActual == 'carrito.php'): ?>
        <link rel="stylesheet" href="./assets/css/carrito.css">
    <?php
    elseif ($paginaActual == 'pedidos.php'): ?>
        <link rel="stylesheet" href="./assets/css/pedidos.css">
    <?php endif; ?>
</head>

<body>
    <header>
        <a href="index.php"><img src="./assets/img/Logo.png"></a>

        <section class="menu" id="menu">
            <ul>
                <li><a href="index.php" class="<?= $paginaActual == 'index.php' ? 'activo' : '' ?>">Inicio</a></li>
                <li><a href="contacto.php" class="<?= $paginaActual == 'contacto.php' ? 'activo' : '' ?>">Contactanos</a></li>
                <li><a href="tienda.php" class="<?= $paginaActual == 'tienda.php' ? 'activo' : '' ?>">Tienda</a></li>
                <li><a href="ferias.php" class="<?= $paginaActual == 'ferias.php' ? 'activo' : '' ?>">Ferias</a></li>
            </ul>
            <section class="usuarios">
                <?php if (isset($_SESSION['id_usuario'])): ?>
                    <?php if ($_SESSION['rol'] === 'admin'): ?>
                        <a href="./admin/index.php" title="Panel Admin"><i class="fa-solid fa-shield-halved"></i></a>
                    <?php endif; ?>
                    <a href="./logout.php" title="Cerrar Sesión"><i class="fa-solid fa-right-from-bracket"></i></a>
                <?php else: ?>
                    <a href="./login.php" title="Iniciar Sesión"><i class="fa-solid fa-user"></i></a>
                <?php endif; ?>
                <a href="./carrito.php"><i class="fa-solid fa-cart-plus"></i></a>
            </section>
        </section>

        <button class="hamburguesa" id="botonHamburguesa">
            <i class="fa-solid fa-bars"></i>
        </button>

    </header>