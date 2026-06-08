<?php
include "conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $stock = isset($_POST['stock']) ? trim($_POST['stock']) : '';
    $precio_venta = isset($_POST['precio_venta']) ? trim($_POST['precio_venta']) : '';
    $id_categoria = isset($_POST['id_categoria']) ? trim($_POST['id_categoria']) : '';

   
    // BLOQUE DE VALIDACIONES DE DATOS - pruebas automatizadas para asegurar la integralidad de los datos antes de la inserción en MySQL
  
    if (empty($nombre) || $stock === '' || empty($precio_venta) || empty($id_categoria)) {
        $mensaje = "<div class='alert alert-error'>Prueba Fallida: Todos los campos son obligatorios.</div>";
    } 
    // Validación de longitud y caracteres especiales (Letras, números y espacios solamente)
    elseif (!preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$/', $nombre) || strlen($nombre) < 3 || strlen($nombre) > 50) {
        $mensaje = "<div class='alert alert-error'>Prueba Fallida: El nombre debe tener entre 3 y 50 caracteres, sin símbolos especiales.</div>";
    }
    // Validación de número Entero positivo para el Stock
    elseif (!filter_var($stock, FILTER_VALIDATE_INT) || (int)$stock < 0) {
        $mensaje = "<div class='alert alert-error'>Prueba Fallida: El stock debe ser estrictamente un número entero positivo o cero.</div>";
    }
    // Validación de número Decimal mayor a cero para el Precio
    elseif (!filter_var($precio_venta, FILTER_VALIDATE_FLOAT) || (float)$precio_venta <= 0) {
        $mensaje = "<div class='alert alert-error'>Prueba Fallida: El precio de venta debe ser un número decimal mayor a cero.</div>";
    } 
    else {
        // Validación superada: Inserción limpia e inocua contra inyecciones SQL básicas
        $nombreEscapado = $conexion->real_escape_string($nombre);
        
        // Se autocompletan los campos extras de tu phpMyAdmin (descripcion y precio_compra) con valores base
        $sql = "INSERT INTO producto (nombre, stock, precio_venta, id_categoria, descripcion, precio_compra) 
                VALUES ('$nombreEscapado', '$stock', '$precio_venta', '$id_categoria', 'Producto de inventario', 0.00)";
        
        if ($conexion->query($sql) === TRUE) {
            $mensaje = "<div class='alert alert-success'>Prueba Exitosa: Producto registrado y validado correctamente en MySQL.</div>";
        } else {
            $mensaje = "<div class='alert alert-error'>Error de persistencia: " . $conexion->error . "</div>";
        }
    }
}

// Obtener las categorías reales de tu phpMyAdmin para llenar el select dinámicamente
$categoriasQuery = $conexion->query("SELECT * FROM categorias");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Producto - SIMPLEX</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="content-box">
        <h2>Registrar Artículo en Inventario</h2>
        <p>Ingrese los datos para ejecutar las pruebas automatizadas de validación de datos en el backend.</p>

        <?php echo $mensaje; ?>

        <form action="insertar_producto.php" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre del Producto (Texto, 3-50 carac.):</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ej: Jabón Fab 1kg" required>
            </div>
            
            <div class="form-group">
                <label for="stock">Cantidad en Stock (Número Entero):</label>
                <input type="number" id="stock" name="stock" placeholder="Ej: 45" required>
            </div>

            <div class="form-group">
                <label for="precio_venta">Precio de Venta (Decimal/Número):</label>
                <input type="number" id="precio_venta" name="precio_venta" step="0.01" placeholder="Ej: 3800.00" required>
            </div>

            <div class="form-group">
                <label for="id_categoria">Categoría Asociada:</label>
                <select id="id_categoria" name="id_categoria" required>
                    <option value="">-- Seleccione una Categoría --</option>
                    <?php
                    if ($categoriasQuery && $categoriasQuery->num_rows > 0) {
                        while($cat = $categoriasQuery->fetch_assoc()) {
                            echo "<option value='".$cat['id_categoria']."'>".$cat['nombre']."</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <button type="submit" class="btn-primary">Guardar Producto</button>
            <br><br>
            <a href="productos.php" class="link-accion">← Volver al Inventario</a>
        </form>
    </div>

</body>
</html>
