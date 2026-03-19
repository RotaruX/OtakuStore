<?php
require_once(__DIR__ . '/../includes/conexion.php');
if (session_status() === PHP_SESSION_NONE) session_start();

header('Content-Type: application/json; charset=utf-8');

// Solo admins
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

$id = intval($_GET['id'] ?? 0);
$estado = trim($_GET['estado'] ?? '');

if ($id <= 0) {
    echo json_encode(['error' => 'ID no válido']);
    exit;
}

$estadosValidos = ['pendiente', 'en camino', 'enviado', 'entregado'];
if (!in_array($estado, $estadosValidos)) {
    echo json_encode(['error' => 'Estado no válido']);
    exit;
}

try {
    $stmt = $conexion->prepare("UPDATE compras SET estado = :estado WHERE id_compra = :id");
    $stmt->execute([':estado' => $estado, ':id' => $id]);
    echo json_encode(['ok' => true, 'estado' => $estado]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al actualizar: ' . $e->getMessage()]);
}
