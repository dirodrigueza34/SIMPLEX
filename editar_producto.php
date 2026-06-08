<?php
include "conexion.php";

$mensaje = "";
$producto = null;

// 1. Cargar los datos actuales del producto mediante la URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $res = $conexion->query("SELECT * FROM producto WHERE id_producto = $id");
    if ($res && $res->num_rows > 0) {
        $producto = $res->fetch_assoc();
    }
}

// 2. Procesar la actualización con validaciones
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_producto = intval($_POST['id_producto']);
    $nombre = trim($_POST['nombre']);
    $stock = trim($_POST['stock']);
    $precio_venta = trim($_POST['precio_venta']);
    $id_categoria = trim($_POST['id_categoria']);

    if (empty($nombre) || $stock === '' || empty($precio_venta) || empty($id_categoria)) {
        $mensaje = "<div class='alert alert-error'>Prueba Fallida: Todos los campos son obligatorios.</div>";
    } 
    elseif (!preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$/', $nombre) || strlen($nombre) < 3 || strlen($nombre) > 50) {
        $mensaje = "<div class='alert alert-error'>Prueba Fallida: Nombre inválido (3-50 caracteres sin símbolos).</div>";
    }
    elseif (!filter_var($stock, FILTER_VALIDATE_INT) || (int)$stock < 0) {
        $mensaje = "<div class='alert alert-error'>Prueba Fallida: El stock debe ser un entero positivo.</div>";
    }
    elseif (!filter_var($precio_venta, FILTER_VALIDATE_FLOAT) || (float)$precio_venta <= 0) {
        $mensaje = "<div class='alert alert-error'>Prueba Fallida: El precio debe ser un decimal mayor a cero.</div>";
    } 
    else {
        $nombreEscapado = $conexion->real_escape_string($nombre);
        $sql = "UPDATE producto SET nombre='$nombreEscapado', stock='$stock', precio_venta='$precio_venta', id_categoria='$id_categoria' WHERE id_producto=$id_producto";
        
        if ($conexion->query($sql) === TRUE) {
            $mensaje = "<div class='alert alert-success'>Prueba Exitosa: Producto actualizado y validado correctamente.</div>";
            // Refrescar los datos locales para la vista
            $res = $conexion->query("SELECT * FROM producto WHERE id_producto = $id_producto");
            $producto = $res->fetch_assoc();
        } else {
            $mensaje = "<div class='alert alert-error'>Error al actualizar: " . $conexion->error . "</div>";
        }
    }
}

$categoriasQuery = $conexion->query("SELECT * FROM categorias");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto - SIMPLEX</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="content-box">
        <h2>Modificar Producto</h2>
        <p>Actualice la información del artículo validando las reglas lógicas del inventario.</p>

        <?php echo $mensaje; ?>

        <?php if ($producto): ?>
        <form action="editar_producto.php?id=<?php echo $producto['id_producto']; ?>" method="POST">
            <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">

            <div class="form-group">
                <label>Nombre del Producto:</label>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Cantidad en Stock:</label>
                <input type="number" name="stock" value="<?php echo $producto['stock']; ?>" required>
            </div>

            <div class="form-group">
                <label>Precio de Venta ($):</label>
                <input type="number" name="precio_venta" step="0.01" value="<?php echo $producto['precio_venta']; ?>" required>
            </div>

            <div class="form-group">
                <label>Categoría:</label>
                <select name="id_categoria" required>
                    <?php
                    if ($categoriasQuery && $categoriasQuery->num_rows > 0) {
                        while($cat = $categoriasQuery->fetch_assoc()) {
                            $selected = ($cat['id_categoria'] == $producto['id_categoria']) ? "selected" : "";
                            echo "<option value='".$cat['id_categoria']."' $selected>".$cat['nombre']."</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <button type="submit" class="btn-primary">Actualizar Cambios</button>
            <br><br>
            <a href="productos.php" class="link-accion">← Cancelar y Volver</a>
        </form>
        <?php else: ?>
            <div class='alert alert-error'>Producto no encontrado.</div>
            <a href="productos.php" class="link-accion">Volver al Inventario</a>
        <?php endif; ?>
    </div>

</body>
</html>
