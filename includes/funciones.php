<?php
// Añade una unidad de un producto al carrito del usuario. Si ya existe, incrementa la cantidad.
function añadirAlCarrito(PDO $conexion, int $id_usuario, int $id_producto): array
{
    if ($id_producto <= 0) {
        return ['error' => 'Producto no válido'];
    }

    $stmt = $conexion->prepare(
        "SELECT id_producto, stock FROM productos WHERE id_producto = :id LIMIT 1"
    );
    $stmt->execute([':id' => $id_producto]);
    $producto = $stmt->fetch();

    if (!$producto) {
        return ['error' => 'Producto no encontrado'];
    }

    if ($producto['stock'] <= 0) {
        return ['ok' => false, 'mensaje' => 'Sin stock disponible'];
    }

    $stmt = $conexion->prepare("
        INSERT INTO carrito (id_usuario, id_producto, cantidad)
        VALUES (:id_usuario, :id_producto, 1)
        ON DUPLICATE KEY UPDATE cantidad = cantidad + 1
    ");
    $stmt->execute([
        ':id_usuario'  => $id_usuario,
        ':id_producto' => $id_producto,
    ]);

    return ['ok' => true];
}

// Elimina un producto del carrito de un usuario.
function eliminarDelCarrito(PDO $conexion, int $id_usuario, int $id_producto): array
{
    if ($id_producto <= 0) {
        return ['error' => 'Producto no válido'];
    }

    $stmt = $conexion->prepare("
        DELETE FROM carrito
        WHERE id_usuario = :id_usuario AND id_producto = :id_producto
    ");
    $stmt->execute([
        ':id_usuario'  => $id_usuario,
        ':id_producto' => $id_producto,
    ]);

    return ['ok' => true];
}
