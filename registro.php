<?php
require_once("./includes/conexion.php");
session_start();

// Si ya está logueado, redirigir
if (isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit;
}

$error = '';
$nombre = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmar = $_POST['confirmar_password'] ?? '';

    if (empty($nombre) || empty($email) || empty($password) || empty($confirmar)) {
        $error = 'Por favor, completa todos los campos.';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } elseif ($password !== $confirmar) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        $stmt = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);

        if ($stmt->fetch()) {
            $error = 'Ya existe una cuenta con ese email.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conexion->prepare(
                "INSERT INTO usuarios (nombre_usuario, email, contraseña, rol)
                 VALUES (:nombre, :email, :password, 'cliente')"
            );
            $stmt->execute([
                ':nombre'   => $nombre,
                ':email'    => $email,
                ':password' => $hash
            ]);

            header("Location: login.php?registro=ok");
            exit;
        }
    }
}
?>

<?php require_once("includes/header.php") ?>

<main class="login-page">
    <section class="login-container">
        <h2>Crear Cuenta</h2>

        <?php if (!empty($error)): ?>
            <div class="mensaje-error">
                <i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="registro.php">
            <div class="campo">
                <label for="nombre">Nombre de usuario</label>
                <input type="text" id="nombre" name="nombre" placeholder="Tu nombre de usuario" required
                    value="<?= htmlspecialchars($nombre) ?>">
            </div>

            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="tu@email.com" required
                    value="<?= htmlspecialchars($email) ?>">
            </div>

            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Mínimo 6 caracteres" required>
            </div>

            <div class="campo">
                <label for="confirmar_password">Confirmar Contraseña</label>
                <input type="password" id="confirmar_password" name="confirmar_password" placeholder="Repite tu contraseña" required>
            </div>

            <button type="submit" class="btn">REGISTRARSE</button>
        </form>

        <p class="enlace-alterno">¿Ya tienes cuenta? <a href="login.php">Inicia Sesión</a></p>
    </section>
</main>

<script src="./assets/js/validacion.js"></script>
<?php require_once("includes/footer.php") ?>
