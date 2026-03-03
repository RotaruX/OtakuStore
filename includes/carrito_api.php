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

$id_usuario = intval($_SESSION['id_usuario']);
$accion     = $_POST['accion'] ?? '';

switch ($accion) {

    case 'añadir':
        $id_producto = intval($_POST['id_producto'] ?? 0);
        echo json_encode(añadirAlCarrito($conexion, $id_usuario, $id_producto));
        break;

    case 'eliminar':
        $id_producto = intval($_POST['id_producto'] ?? 0);
        echo json_encode(eliminarDelCarrito($conexion, $id_usuario, $id_producto));
        break;

    case 'actualizar':
        $id_producto    = intval($_POST['id_producto'] ?? 0);
        $nueva_cantidad = intval($_POST['cantidad'] ?? 1);
        echo json_encode(actualizarCantidadCarrito($conexion, $id_usuario, $id_producto, $nueva_cantidad));
        break;

    case 'comprar':
        $ids_raw      = $_POST['ids_productos'] ?? '';
        $ids_productos = array_values(array_filter(array_map('intval', explode(',', $ids_raw))));
        echo json_encode(realizarPedido($conexion, $id_usuario, $ids_productos));
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Acción no válida']);
}
