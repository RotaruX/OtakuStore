<?php
require_once("includes/conexion.php");
session_start();

// Si ya está logueado, redirigir
if (isset($_SESSION['id_usuario'])) {
    if ($_SESSION['rol'] === 'admin') {
        header("Location: admin/index.php");
    } else {
        header("Location: index.php");
    }
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identificador = trim($_POST['usuario_o_email'] ?? '');
    $password      = $_POST['password'] ?? '';

    if (empty($identificador) || empty($password)) {
        $error = 'Por favor, completa todos los campos.';
    } else {
        $stmt = $conexion->prepare(
            "SELECT * FROM usuarios
             WHERE email = :id OR nombre_usuario = :id2
             LIMIT 1"
        );
        $stmt->execute([':id' => $identificador, ':id2' => $identificador]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($password, $usuario['contraseña'])) {
            $_SESSION['id_usuario']     = $usuario['id_usuario'];
            $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
            $_SESSION['rol']            = $usuario['rol'];

            if ($usuario['rol'] === 'admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = 'Usuario/email o contraseña incorrectos.';
        }
    }
}
?>

<?php require_once("includes/header.php") ?>

<main class="login-page">
    <section class="login-container">
        <h2>Iniciar Sesión</h2>

        <?php if (!empty($error)): ?>
            <div class="mensaje-error">
                <i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['registro']) && $_GET['registro'] === 'ok'): ?>
            <div class="mensaje-exito">
                <i class="fa-solid fa-circle-check"></i> ¡Registro exitoso! Ya puedes iniciar sesión.
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="campo">
                <label for="usuario_o_email">Usuario o Email</label>
                <input type="text" id="usuario_o_email" name="usuario_o_email"
                    placeholder="Tu usuario o email"
                    value="<?= htmlspecialchars($_POST['usuario_o_email'] ?? '') ?>" required>
            </div>

            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Tu contraseña" required>
            </div>

            <button type="submit" class="btn">INICIAR SESIÓN</button>
        </form>

        <p class="enlace-alterno">¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
    </section>
</main>

<script src="assets/js/validacion.js"></script>
<?php require_once("includes/footer.php") ?>
