<?php
require_once(__DIR__ . "/includes/conexion.php");
if (session_status() === PHP_SESSION_NONE) session_start();

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: " . BASE_URL . "tienda.php");
    exit;
}

$stmt = $conexion->prepare("
    SELECT id_producto, nombre, categoria, imagen, precio, stock, descripcion, fecha_incorporacion
    FROM productos
    WHERE id_producto = :id
    LIMIT 1
");
$stmt->execute([':id' => $id]);
$producto = $stmt->fetch();

if (!$producto) {
    header("Location: " . BASE_URL . "tienda.php");
    exit;
}

$nombre      = htmlspecialchars($producto['nombre']);
$categoria   = htmlspecialchars($producto['categoria']);
$imagen      = htmlspecialchars($producto['imagen'] ?? '');
$precio      = number_format(floatval($producto['precio']), 2, ',', '.');
$stock       = intval($producto['stock']);
$descripcion = htmlspecialchars($producto['descripcion'] ?? '');
$catLabel    = $categoria === 'Cómic' ? 'Manga' : 'Funko';
$sesionActiva = isset($_SESSION['id_usuario']) ? 'true' : 'false';

require_once(__DIR__ . "/includes/header.php");
?>

<main class="producto-page">

    <nav class="producto-breadcrumb">
        <a href="<?= BASE_URL ?>tienda.php"><i class="fa-solid fa-store"></i> Tienda</a>
        <span><i class="fa-solid fa-chevron-right"></i></span>
        <span class="actual"><?= $nombre ?></span>
    </nav>

    <article class="producto-card">

        <div class="producto-imagen-wrap">
            <?php if ($imagen !== ''): ?>
                <img src="<?= BASE_URL ?>assets/img/<?= $imagen ?>"
                     alt="<?= $nombre ?>"
                     onerror="this.src='<?= BASE_URL ?>assets/img/placeholder.png'">
            <?php else: ?>
                <i class="fa-solid fa-image" style="font-size:5rem;color:#ddd;"></i>
            <?php endif; ?>
            <span class="producto-categoria-badge"><?= $catLabel ?></span>
        </div>

        <div class="producto-info">

            <h1 class="producto-nombre"><?= $nombre ?></h1>

            <p class="producto-precio"><?= $precio ?> €</p>

            <?php if ($stock > 0): ?>
                <span class="producto-stock disponible">
                    <i class="fa-solid fa-circle-check"></i>
                    En stock (<?= $stock ?> disponible<?= $stock > 1 ? 's' : '' ?>)
                </span>
            <?php else: ?>
                <span class="producto-stock agotado">
                    <i class="fa-solid fa-circle-xmark"></i>
                    Sin stock
                </span>
            <?php endif; ?>

            <hr class="producto-separador">

            <?php if (!empty($descripcion)): ?>
                <p class="producto-descripcion-titulo">Descripción</p>
                <p class="producto-descripcion"><?= nl2br($descripcion) ?></p>
                <hr class="producto-separador">
            <?php endif; ?>

            <div class="producto-acciones">
                <?php if ($stock > 0): ?>
                    <button class="btn-producto-anadir"
                            id="btnAnadirProducto"
                            data-id="<?= $producto['id_producto'] ?>"
                            data-nombre="<?= $nombre ?>">
                        <i class="fa-solid fa-cart-plus"></i> Añadir al carrito
                    </button>
                <?php else: ?>
                    <button class="btn-producto-sin-stock" disabled>
                        <i class="fa-solid fa-ban"></i> Sin stock
                    </button>
                <?php endif; ?>

                <a href="<?= BASE_URL ?>tienda.php" class="btn-volver-tienda">
                    <i class="fa-solid fa-arrow-left"></i> Volver
                </a>
            </div>

            <div class="banner-anadir-confirmacion" id="bannerConfirmacion">
                <i class="fa-solid fa-circle-check"></i>
                <p>¡Añadido al carrito! <a href="<?= BASE_URL ?>carrito.php">Ver carrito →</a></p>
            </div>

        </div>
    </article>

</main>

<script>
const SESION_ACTIVA = <?= $sesionActiva ?>;
const BASE_URL      = "<?= BASE_URL ?>";

document.addEventListener("DOMContentLoaded", () => {
    const btn = document.getElementById("btnAnadirProducto");
    if (!btn) return;

    btn.addEventListener("click", async () => {
        if (!SESION_ACTIVA) {
            window.location.href = BASE_URL + "login.php?redir=tienda";
            return;
        }

        if (btn.disabled) return;
        btn.disabled = true;

        const idProducto    = btn.dataset.id;
        const textoOriginal = btn.innerHTML;

        try {
            const formData = new FormData();
            formData.append("accion", "añadir");
            formData.append("id_producto", idProducto);

            const res  = await fetch(BASE_URL + "includes/carrito_api.php", {
                method: "POST",
                body: formData,
            });
            const data = await res.json();

            if (data.logueado === false) {
                window.location.href = BASE_URL + "login.php?redir=tienda";
                return;
            }

            if (data.ok) {
                btn.innerHTML = '<i class="fa-solid fa-check"></i> ¡Añadido!';
                btn.classList.add("btn-añadido");

                const banner = document.getElementById("bannerConfirmacion");
                if (banner) banner.classList.add("visible");

                setTimeout(() => {
                    btn.innerHTML = textoOriginal;
                    btn.classList.remove("btn-añadido");
                    btn.disabled = false;
                    if (banner) banner.classList.remove("visible");
                }, 3000);
            } else {
                btn.innerHTML = data.mensaje || "Error";
                setTimeout(() => {
                    btn.innerHTML = textoOriginal;
                    btn.disabled = false;
                }, 2000);
            }
        } catch (err) {
            console.error("Error al añadir al carrito:", err);
            btn.disabled = false;
        }
    });
});
</script>
<?php require_once(__DIR__ . "/includes/footer.php") ?>
