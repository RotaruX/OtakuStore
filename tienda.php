<?php
require_once(__DIR__ . "/includes/conexion.php");
if (session_status() === PHP_SESSION_NONE) session_start();

$tipo    = $_GET['tipo']    ?? '';          
$q       = trim($_GET['q'] ?? '');
$pagina  = max(1, intval($_GET['pagina'] ?? 1));

$limite  = 12;
$offset  = ($pagina - 1) * $limite;

$categoriaFiltro = '';
if ($tipo === 'funkos')  $categoriaFiltro = 'Funko';
if ($tipo === 'comics')  $categoriaFiltro = 'Cómic';

$condiciones = [];
$params      = [];

if ($categoriaFiltro !== '') {
    $condiciones[] = "categoria = :categoria";
    $params[':categoria'] = $categoriaFiltro;
}
if ($q !== '') {
    $condiciones[] = "nombre LIKE :q";
    $params[':q']  = '%' . $q . '%';
}

$where = count($condiciones) ? 'WHERE ' . implode(' AND ', $condiciones) : '';

$stmtTotal = $conexion->prepare("SELECT COUNT(*) FROM productos $where");
$stmtTotal->execute($params);
$totalProductos = (int) $stmtTotal->fetchColumn();
$totalPaginas   = max(1, ceil($totalProductos / $limite));

$sql = "SELECT id_producto, nombre, categoria, imagen, precio, stock
        FROM productos
        $where
        ORDER BY categoria ASC, nombre ASC
        LIMIT :limite OFFSET :offset";

$stmt = $conexion->prepare($sql);
foreach ($params as $k => $v) $stmt->bindValue($k, $v);
$stmt->bindValue(':limite',  $limite, PDO::PARAM_INT);
$stmt->bindValue(':offset',  $offset, PDO::PARAM_INT);
$stmt->execute();
$productos = $stmt->fetchAll();

function urlPagina(int $p, string $tipo, string $q): string {
    $params = ['pagina' => $p];
    if ($tipo !== '') $params['tipo'] = $tipo;
    if ($q   !== '') $params['q']    = $q;
    return 'tienda.php?' . http_build_query($params);
}

require_once(__DIR__ . "/includes/header.php");
?>

<main class="tienda-main">

    <section class="tienda-hero">
        <div class="tienda-hero-content">
            <h1>NUESTRA TIENDA</h1>
            <p>Explora nuestra colección de Funkos y Mangas</p>
        </div>
    </section>

    <section class="tienda-filtros">
        <div class="filtros-categorias">
            <a href="tienda.php<?= $q !== '' ? '?q=' . urlencode($q) : '' ?>"
               class="btn-filtro <?= $tipo === '' ? 'activo' : '' ?>">Todos</a>
            <a href="tienda.php?tipo=funkos<?= $q !== '' ? '&q=' . urlencode($q) : '' ?>"
               class="btn-filtro <?= $tipo === 'funkos' ? 'activo' : '' ?>">
                <i class="fa-solid fa-star"></i> Funkos
            </a>
            <a href="tienda.php?tipo=comics<?= $q !== '' ? '&q=' . urlencode($q) : '' ?>"
               class="btn-filtro <?= $tipo === 'comics' ? 'activo' : '' ?>">
                <i class="fa-solid fa-book"></i> Mangas
            </a>
        </div>

        <form class="filtros-busqueda" method="GET" action="tienda.php">
            <?php if ($tipo !== ''): ?>
                <input type="hidden" name="tipo" value="<?= htmlspecialchars($tipo) ?>">
            <?php endif; ?>
            <input type="search"
                   name="q"
                   id="buscador"
                   placeholder="Buscar producto..."
                   value="<?= htmlspecialchars($q) ?>"
                   autocomplete="off">
            <button type="submit" class="btn-buscar" aria-label="Buscar">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </section>

    <section class="tienda-info-resultados">
        <?php if ($q !== '' || $tipo !== ''): ?>
            <p class="resultados-texto">
                <?= $totalProductos ?> resultado<?= $totalProductos !== 1 ? 's' : '' ?>
                <?php if ($categoriaFiltro !== ''): ?>
                    en <strong><?= $categoriaFiltro === 'Cómic' ? 'Mangas' : 'Funkos' ?></strong>
                <?php endif; ?>
                <?php if ($q !== ''): ?>
                    para "<em><?= htmlspecialchars($q) ?></em>"
                <?php endif; ?>
            </p>
        <?php endif; ?>
    </section>

    <section class="tienda-grid-wrapper">
        <?php if (empty($productos)): ?>
            <div class="tienda-sin-resultados">
                <i class="fa-solid fa-box-open"></i>
                <h2>No se encontraron productos</h2>
                <p>Prueba con otra búsqueda o categoría.</p>
                <a href="tienda.php" class="btn">Ver todos los productos</a>
            </div>
        <?php else: ?>
            <div class="tienda-grid" id="tiendaGrid">
                <?php foreach ($productos as $i => $p):
                    $nombre   = htmlspecialchars($p['nombre']);
                    $imagen   = htmlspecialchars($p['imagen'] ?? '');
                    $precio   = number_format(floatval($p['precio']), 2, ',', '.');
                    $id       = intval($p['id_producto']);
                    $stock    = intval($p['stock']);
                    $catLabel = $p['categoria'] === 'Cómic' ? 'Manga' : 'Funko';
                ?>
                    <a href="<?= BASE_URL ?>producto.php?id=<?= $id ?>"
                       class="tarjeta tienda-tarjeta tarjeta-link"
                       data-index="<?= $i ?>"
                       data-id="<?= $id ?>">
                        <div class="tarjeta-imagen-wrap">
                            <?php if ($imagen !== ''): ?>
                                <img src="<?= BASE_URL ?>assets/img/<?= $imagen ?>"
                                     alt="<?= $nombre ?>"
                                     onerror="this.src='<?= BASE_URL ?>assets/img/placeholder.png'">
                            <?php else: ?>
                                <div class="sin-imagen">
                                    <i class="fa-solid fa-image"></i>
                                </div>
                            <?php endif; ?>
                            <span class="categoria-badge"><?= $catLabel ?></span>
                        </div>
                        <div class="info">
                            <h3 title="<?= $nombre ?>"><?= $nombre ?></h3>
                            <p class="precio"><?= $precio ?>€</p>
                            <?php if ($stock > 0): ?>
                                <button class="btn btn-anadir"
                                        data-id="<?= $id ?>"
                                        data-nombre="<?= $nombre ?>"
                                        onclick="event.preventDefault(); event.stopPropagation();">
                                    <i class="fa-solid fa-cart-plus"></i> Añadir al carrito
                                </button>
                            <?php else: ?>
                                <button class="btn btn-sin-stock" disabled
                                        onclick="event.preventDefault(); event.stopPropagation();">
                                    Sin stock
                                </button>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <?php if ($totalPaginas > 1): ?>
                <nav class="tienda-paginacion" aria-label="Paginación de productos">
                    <?php if ($pagina > 1): ?>
                        <a href="<?= urlPagina($pagina - 1, $tipo, $q) ?>"
                           class="pag-btn pag-prev" aria-label="Anterior">
                            <i class="fa-solid fa-chevron-left"></i>
                        </a>
                    <?php else: ?>
                        <span class="pag-btn pag-prev disabled">
                            <i class="fa-solid fa-chevron-left"></i>
                        </span>
                    <?php endif; ?>

                    <div class="pag-numeros">
                        <?php
                        $rango = 2;
                        $inicio = max(1, $pagina - $rango);
                        $fin    = min($totalPaginas, $pagina + $rango);

                        if ($inicio > 1): ?>
                            <a href="<?= urlPagina(1, $tipo, $q) ?>" class="pag-num">1</a>
                            <?php if ($inicio > 2): ?><span class="pag-dots">…</span><?php endif; ?>
                        <?php endif; ?>

                        <?php for ($n = $inicio; $n <= $fin; $n++): ?>
                            <a href="<?= urlPagina($n, $tipo, $q) ?>"
                               class="pag-num <?= $n === $pagina ? 'activo' : '' ?>">
                                <?= $n ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($fin < $totalPaginas): ?>
                            <?php if ($fin < $totalPaginas - 1): ?><span class="pag-dots">…</span><?php endif; ?>
                            <a href="<?= urlPagina($totalPaginas, $tipo, $q) ?>"
                               class="pag-num"><?= $totalPaginas ?></a>
                        <?php endif; ?>
                    </div>

                    <?php if ($pagina < $totalPaginas): ?>
                        <a href="<?= urlPagina($pagina + 1, $tipo, $q) ?>"
                           class="pag-btn pag-next" aria-label="Siguiente">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    <?php else: ?>
                        <span class="pag-btn pag-next disabled">
                            <i class="fa-solid fa-chevron-right"></i>
                        </span>
                    <?php endif; ?>
                </nav>

                <p class="pag-info">
                    Página <?= $pagina ?> de <?= $totalPaginas ?>
                    &nbsp;·&nbsp; <?= $totalProductos ?> productos en total
                </p>
            <?php endif; ?>
        <?php endif; ?>
    </section>

</main>

<?php
$sesionActiva  = isset($_SESSION['id_usuario']) ? 'true' : 'false';
?>
<script>
    const SESION_ACTIVA = <?= $sesionActiva ?>;
</script>
<script src="<?= BASE_URL ?>assets/js/tienda.js"></script>
<?php require_once(__DIR__ . "/includes/footer.php"); ?>