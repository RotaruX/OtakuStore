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
if ($id <= 0) {
    echo json_encode(['error' => 'ID no válido']);
    exit;
}

// No permitir que el admin se elimine a sí mismo
if ($id === intval($_SESSION['id_usuario'])) {
    echo json_encode(['error' => 'No puedes eliminar tu propia cuenta']);
    exit;
}

try {
    $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id_usuario = :id");
    $stmt->execute([':id' => $id]);
    echo json_encode(['ok' => true]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al eliminar: ' . $e->getMessage()]);
}
