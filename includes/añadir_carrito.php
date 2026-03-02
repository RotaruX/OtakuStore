<?php
require_once(__DIR__ . "/conexion.php");
session_start();

header('Content-Type: application/json');

// Solo acepta POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

// Verificar sesión
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['logueado' => false]);
    exit;
}

$id_producto = intval($_POST['id_producto'] ?? 0);
$id_usuario  = intval($_SESSION['id_usuario']);

if ($id_producto <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Producto no válido']);
    exit;
}

// Verificar que el producto existe y tiene stock
$stmt = $conexion->prepare("SELECT id_producto, stock FROM productos WHERE id_producto = :id LIMIT 1");
$stmt->execute([':id' => $id_producto]);
$producto = $stmt->fetch();

if (!$producto) {
    echo json_encode(['error' => 'Producto no encontrado']);
    exit;
}

if ($producto['stock'] <= 0) {
    echo json_encode(['ok' => false, 'mensaje' => 'Sin stock disponible']);
    exit;
}

// Inserta o actualiza la cantidad si ya está en el carrito
$stmt = $conexion->prepare("
    INSERT INTO carrito (id_usuario, id_producto, cantidad)
    VALUES (:id_usuario, :id_producto, 1)
    ON DUPLICATE KEY UPDATE cantidad = cantidad + 1
");
$stmt->execute([
    ':id_usuario'  => $id_usuario,
    ':id_producto' => $id_producto
]);

echo json_encode(['ok' => true]);
