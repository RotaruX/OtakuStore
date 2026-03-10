<?php
require_once(__DIR__ . '/../includes/conexion.php');
if (session_status() === PHP_SESSION_NONE) session_start();

header('Content-Type: application/json; charset=utf-8');

// Solo admins
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

$accion = $_POST['accion'] ?? '';

if ($accion === 'eliminar') {
    $id = intval($_POST['id_producto'] ?? 0);
    if ($id <= 0) {
        echo json_encode(['error' => 'ID no válido']);
        exit;
    }

    try {
        $stmt = $conexion->prepare("DELETE FROM productos WHERE id_producto = :id");
        $stmt->execute([':id' => $id]);
        echo json_encode(['ok' => true]);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al eliminar: ' . $e->getMessage()]);
    }
    exit;
}

echo json_encode(['error' => 'Acción no válida']);
