<?php
require_once(__DIR__ . "/conexion.php");
require_once(__DIR__ . "/funciones.php");
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['logueado' => false]);
    exit;
}

$id_producto = intval($_POST['id_producto'] ?? 0);
$id_usuario  = intval($_SESSION['id_usuario']);

$resultado = eliminarDelCarrito($conexion, $id_usuario, $id_producto);
echo json_encode($resultado);
