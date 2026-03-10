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

// Actualiza la cantidad de un producto en el carrito de un usuario (mínimo 1).
function actualizarCantidadCarrito(PDO $conexion, int $id_usuario, int $id_producto, int $nueva_cantidad): array
{
    if ($id_producto <= 0 || $nueva_cantidad < 1) {
        return ['error' => 'Datos no válidos'];
    }

    // Verificar stock disponible
    $stmt = $conexion->prepare("SELECT stock FROM productos WHERE id_producto = :id LIMIT 1");
    $stmt->execute([':id' => $id_producto]);
    $producto = $stmt->fetch();

    if (!$producto) {
        return ['error' => 'Producto no encontrado'];
    }

    if ($nueva_cantidad > $producto['stock']) {
        return ['ok' => false, 'mensaje' => "Stock insuficiente (disponible: {$producto['stock']})"];
    }

    $stmt = $conexion->prepare("
        UPDATE carrito SET cantidad = :cantidad
        WHERE id_usuario = :id_usuario AND id_producto = :id_producto
    ");
    $stmt->execute([
        ':cantidad'    => $nueva_cantidad,
        ':id_usuario'  => $id_usuario,
        ':id_producto' => $id_producto,
    ]);

    return ['ok' => true, 'cantidad' => $nueva_cantidad];
}

// Crea una compra con los productos indicados y los elimina del carrito. Devuelve el id_compra.
function realizarPedido(PDO $conexion, int $id_usuario, array $ids_productos): array
{
    if (empty($ids_productos)) {
        return ['error' => 'No hay productos seleccionados'];
    }

    $in   = implode(',', array_fill(0, count($ids_productos), '?'));
    $sql  = "SELECT c.id_producto, c.cantidad, p.precio, p.nombre, p.stock
             FROM carrito c
             INNER JOIN productos p ON p.id_producto = c.id_producto
             WHERE c.id_usuario = ? AND c.id_producto IN ($in)";

    $stmt = $conexion->prepare($sql);
    $stmt->execute(array_merge([$id_usuario], $ids_productos));
    $items = $stmt->fetchAll();

    if (empty($items)) {
        return ['error' => 'Productos no encontrados en el carrito'];
    }

    $total = array_sum(array_map(fn($i) => $i['cantidad'] * $i['precio'], $items));

    foreach ($items as $item) {
        if ($item['stock'] < $item['cantidad']) {
            return ['error' => "Stock insuficiente para: {$item['nombre']} (disponible: {$item['stock']})"];
        }
    }

    $conexion->beginTransaction();
    try {
        $stmt = $conexion->prepare("
            INSERT INTO compras (id_usuario, total) VALUES (:id_usuario, :total)
        ");
        $stmt->execute([':id_usuario' => $id_usuario, ':total' => $total]);
        $id_compra = (int) $conexion->lastInsertId();

        $stmtItem = $conexion->prepare("
            INSERT INTO detalles_compra (id_compra, id_producto, cantidad, precio_unitario)
            VALUES (:id_compra, :id_producto, :cantidad, :precio)
        ");
        $stmtStock = $conexion->prepare("
            UPDATE productos SET stock = stock - :cantidad WHERE id_producto = :id_producto
        ");
        foreach ($items as $item) {
            $stmtItem->execute([
                ':id_compra'   => $id_compra,
                ':id_producto' => $item['id_producto'],
                ':cantidad'    => $item['cantidad'],
                ':precio'      => $item['precio'],
            ]);
            $stmtStock->execute([
                ':cantidad'    => $item['cantidad'],
                ':id_producto' => $item['id_producto'],
            ]);
        }

        $stmtDel = $conexion->prepare("
            DELETE FROM carrito WHERE id_usuario = ? AND id_producto = ?
        ");
        foreach ($items as $item) {
            $stmtDel->execute([$id_usuario, $item['id_producto']]);
        }

        $conexion->commit();
        return ['ok' => true, 'id_pedido' => $id_compra];

    } catch (Exception $e) {
        $conexion->rollBack();
        return ['error' => 'Error al procesar el pedido: ' . $e->getMessage()];
    }
}

// Genera la URL de paginación para la tienda, conservando los filtros activos.
function urlPagina(int $p, string $tipo, string $q): string {
    $params = ['pagina' => $p];
    if ($tipo !== '') $params['tipo'] = $tipo;
    if ($q   !== '') $params['q']    = $q;
    return 'tienda.php?' . http_build_query($params);
}
