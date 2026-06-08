<?php
include "conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha = isset($_POST['fecha']) ? trim($_POST['fecha']) : '';
    $total = isset($_POST['total']) ? trim($_POST['total']) : '';
    $id_cliente = !empty($_POST['id_cliente']) ? intval($_POST['id_cliente']) : 'NULL';
    $id_producto = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : 0;
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;

    // Validación numérica decimal mayor a cero para montos financieros
    if (empty($fecha) || !filter_var($total, FILTER_VALIDATE_FLOAT) || (float)$total <= 0 || $id_producto == 0) {
        $mensaje = "<div class='alert alert-error'>Prueba Fallida: Datos transaccionales o numéricos inválidos.</div>";
    } else {
        try {
            // Iniciamos una transacción segura (Evita descuadres contables)
            $conexion->begin_transaction();

            // 1. REGISTRO EN: movimientos_contables
            $desc_mov = "Venta automatizada de mercancía";
            $conexion->query("INSERT INTO movimientos_contables (fecha, descripcion, total) VALUES ('$fecha', '$desc_mov', '$total')");
            $id_movimiento = $conexion->insert_id;

            // 2. REGISTRO EN: detalle_movimiento (Partida Doble: Débito a Caja [Cuenta 1])
            $conexion->query("INSERT INTO detalle_movimiento (id_movimiento, id_cuenta, tipo, valor) VALUES ($id_movimiento, 1, 'Debito', '$total')");
            
            // 3. REGISTRO EN: detalle_movimiento (Partida Doble: Crédito a Ventas [Cuenta 2])
            $conexion->query("INSERT INTO detalle_movimiento (id_movimiento, id_cuenta, tipo, valor) VALUES ($id_movimiento, 2, 'Credito', '$total')");

            // 4. REGISTRO EN: ventas
            $conexion->query("INSERT INTO ventas (fecha, total, id_movimiento, id_cliente) VALUES ('$fecha', '$total', $id_movimiento, $id_cliente)");
            $id_venta = $conexion->insert_id;

            // 5. REGISTRO EN: detalle_venta y actualización de stock del producto
            $subtotal = $total; 
            $conexion->query("INSERT INTO detalle_venta (id_venta, id_producto, cantidad, subtotal) VALUES ($id_venta, $id_producto, $cantidad, $subtotal)");
            $conexion->query("UPDATE producto SET stock = stock - $cantidad WHERE id_producto = $id_producto");

            $conexion->commit();
            $mensaje = "<div class='alert alert-success'>Prueba Exitosa: Venta, Detalle Logístico y Asiento Contable inyectados en MySQL con éxito.</div>";
        } catch (Exception $e) {
            $conexion->rollback();
            $mensaje = "<div class='alert alert-error'>Transacción abortada: " . $e->getMessage() . "</div>";
        }
    }
}

$productosOpt = $conexion->query("SELECT id_producto, nombre, precio_venta FROM producto WHERE stock > 0");
$clientesOpt = $conexion->query("SELECT id_cliente, nombre FROM clientes");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Procesar Venta - SIMPLEX</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="content-box">
        <h2>Caja de Facturación Automatizada</h2>
        <p>Simule un flujo de salida de inventario para impactar de forma directa la contabilidad del negocio.</p>

        <?php echo $mensaje; ?>

        <form action="ventas.php" method="POST">
            <div class="form-group">
                <label>Fecha de Factura:</label>
                <input type="date" name="fecha" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="form-group">
                <label>Seleccionar Producto a Vender:</label>
                <select name="id_producto" required>
                    <option value="">-- Elija Artículo --</option>
                    <?php while($p = $productosOpt->fetch_assoc()) {
                        echo "<option value='".$p['id_producto']."'>".$p['nombre']." ($".$p['precio_venta'].")</option>";
                    } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Cantidad:</label>
                <input type="number" name="cantidad" value="1" min="1" required>
            </div>
            <div class="form-group">
                <label>Valor Total Facturado ($):</label>
                <input type="number" name="total" step="0.01" placeholder="Ej: 15000.00" required>
            </div>
            <div class="form-group">
                <label>Asociar Cliente (Opcional):</label>
                <select name="id_cliente">
                    <option value="">-- Consumidor Final --</option>
                    <?php while($c = $clientesOpt->fetch_assoc()) {
                        echo "<option value='".$c['id_cliente']."'>".$c['nombre']."</option>";
                    } ?>
                </select>
            </div>
            <button type="submit" class="btn-primary">Procesar Facturación</button>
        </form>
    </div>
</body>
</html>
