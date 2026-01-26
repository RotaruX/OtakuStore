<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OtakuStore</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <?php
        $paginaActual = basename($_SERVER['PHP_SELF']);
        if($paginaActual == 'index.php'):?>
    <link rel="stylesheet" href="./assets/css/inicio.css">
    <?php
        elseif($paginaActual == 'tienda.php'):?>
        <link rel="stylesheet" href="./assets/css/tienda.css">
    <?php
        elseif($paginaActual == 'contacto.php'):?>
    <link rel="stylesheet" href="./assets/css/contacto.css">
    <?php
        elseif($paginaActual == 'ferias.php'):?>
    <link rel="stylesheet" href="./assets/css/ferias.css">
    <?php endif;?>
</head>
<body>
    <header>
        <a href="index.php"><img src="./assets/img/Logo.png"></a>

        <section class="menu">
            <ul>
                <li><a href="index.php" class="<?= $paginaActual == 'index.php' ? 'activo' : '' ?>">Inicio</a></li>
                <li><a href="contacto.php" class="<?= $paginaActual == 'contacto.php' ? 'activo' : '' ?>">Contactanos</a></li>
                <li><a href="tienda.php" class="<?= $paginaActual == 'tienda.php' ? 'activo' : '' ?>">Tienda</a></li>
                <li><a href="ferias.php" class="<?= $paginaActual == 'ferias.php' ? 'activo' : '' ?>">Ferias</a></li>
            </ul>
            <section class="usuarios">
                <a href="./login.php"><i class="fa-solid fa-user"></i></a>
                <a href="./carrito.php"><i class="fa-solid fa-cart-plus"></i></a>
            </section>
        </section>
    </header>