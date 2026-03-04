<?php
require_once(__DIR__ . "/includes/conexion.php");
require_once(__DIR__ . "/includes/header.php");

$exito = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre  = trim($_POST['nombre']  ?? '');
    $email   = trim($_POST['email']   ?? '');
    $asunto  = trim($_POST['asunto']  ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    if (empty($nombre) || empty($email) || empty($asunto) || empty($mensaje)) {
        $error = 'Por favor, completa todos los campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'El email introducido no es válido.';
    } else {
        $stmt = $conexion->prepare("
            INSERT INTO mensajes_contacto (nombre, email, asunto, mensaje)
            VALUES (:nombre, :email, :asunto, :mensaje)
        ");
        $stmt->execute([
            ':nombre'  => $nombre,
            ':email'   => $email,
            ':asunto'  => $asunto,
            ':mensaje' => $mensaje,
        ]);
        $exito = true;
    }
}
?>

<main>
    <section class="hero-contacto">
        <div class="hero-contenido">
            <h1>Contacta con Nosotros</h1>
            <p>¿Tienes alguna duda o consulta? Escríbenos y te responderemos lo antes posible.</p>
        </div>
    </section>

    <section class="seccion-contacto">

        <?php if ($exito): ?>
            <div class="mensaje-exito">
                <i class="fa-solid fa-circle-check"></i> ¡Mensaje enviado! Te responderemos pronto.
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="mensaje-error">
                <i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if (!$exito): ?>
        <form class="formulario-contacto" action="contacto.php" method="POST">

            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre"
                    placeholder="Tu nombre completo"
                    value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>" required>
            </div>

            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                    placeholder="tu@email.com"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>

            <div class="campo">
                <label for="asunto">Asunto</label>
                <input type="text" id="asunto" name="asunto"
                    placeholder="¿En qué podemos ayudarte?"
                    value="<?= htmlspecialchars($_POST['asunto'] ?? '') ?>" required>
            </div>

            <div class="campo">
                <label for="mensaje">Mensaje</label>
                <textarea id="mensaje" name="mensaje" rows="5"
                    placeholder="Escribe tu mensaje aquí..." required><?= htmlspecialchars($_POST['mensaje'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn">ENVIAR MENSAJE</button>

        </form>
        <?php endif; ?>

    </section>
</main>

<?php require_once(__DIR__ . "/includes/footer.php"); ?>