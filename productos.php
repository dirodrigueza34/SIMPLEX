<?php
include "conexion.php";

$mensaje = "";

// 1. LÓGICA DE INSERCIÓN CON VALIDACIONES ESTRICTAS (Para tus pruebas en PHP)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'guardar') {
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $stock = isset($_POST['stock']) ? trim($_POST['stock']) : '';
    $precio_venta = isset($_POST['precio_venta']) ? trim($_POST['precio_venta']) : '';
    $id_categoria = isset($_POST['id_categoria']) ? trim($_POST['id_categoria']) : '';

    if (empty($nombre) || $stock === '' || empty($precio_venta) || empty($id_categoria)) {
        $mensaje = "<div class='alert alert-error'>Prueba Fallida: Todos los campos son obligatorios.</div>";
    } 
    elseif (!preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$/', $nombre) || strlen($nombre) < 3 || strlen($nombre) > 50) {
        $mensaje = "<div class='alert alert-error'>Prueba Fallida: Nombre debe tener entre 3 y 50 caracteres, sin símbolos.</div>";
    }
    elseif (!filter_var($stock, FILTER_VALIDATE_INT) || (int)$stock < 0) {
        $mensaje = "<div class='alert alert-error'>Prueba Fallida: El stock debe ser un entero positivo.</div>";
    }
    elseif (!filter_var($precio_venta, FILTER_VALIDATE_FLOAT) || (float)$precio_venta <= 0) {
        $mensaje = "<div class='alert alert-error'>Prueba Fallida: El precio debe ser decimal mayor a cero.</div>";
    } 
    else {
        $nombreEsc = $conexion->real_escape_string($nombre);
        $sqlInsertar = "INSERT INTO producto (nombre, stock, precio_venta, id_categoria, descripcion, precio_compra) 
                        VALUES ('$nombreEsc', '$stock', '$precio_venta', '$id_categoria', 'Producto de inventario PHP', 0.00)";
        
        if ($conexion->query($sqlInsertar) === TRUE) {
            $mensaje = "<div class='alert alert-success'>Prueba Exitosa: Producto registrado correctamente en MySQL.</div>";
        } else {
            $mensaje = "<div class='alert alert-error'>Error: " . $conexion->error . "</div>";
        }
    }
}


$sqlProductos = "SELECT p.*, c.nombre AS nombre_categoria 
                 FROM producto p 
                 LEFT JOIN categorias c ON p.id_categoria = c.id_categoria 
                 ORDER BY p.id_producto DESC";
$resultado = $conexion->query($sqlProductos);

$categoriasQuery = $conexion->query("SELECT * FROM categorias");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos - SIMPLEX</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

   
    <nav class="navbar">
        <span class="nav-welcome">Módulo de Productos (Inventario)</span>
        <div class="nav-links">
            <a href="index.php">Inicio</a>
            <a href="productos.php">Productos</a>
            <a href="clientes.php">Clientes</a>
            <a href="categorias.php">Categorías</a>
            <a href="ventas.php">Ventas</a>
            <a href="proveedor.php">Proveedores</a>
            <a href="movimientos_contables.php">Reportes</a>
        </div>
    </nav>

    <div class="content-box">
        <h2>Mantenimiento y Registro de Productos</h2>
        
        <?php echo $mensaje; ?>

        
        <form action="productos.php" method="POST" class="form-inline-row">
            <input type="hidden" name="accion" value="guardar">
            
            <div class="form-group-inline">
                <label for="codigo_placeholder">Código:</label>
                <input type="text" id="codigo_placeholder" placeholder="Ej: P001" class="input-short" disabled value="Auto">
            </div>

            <div class="form-group-inline">
                <label for="nombre">Nombre / Descripción:</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ej: Arroz" class="input-medium" required>
            </div>

            <div class="form-group-inline">
                <label for="precio_venta">Precio Unitario:</label>
                <input type="number" id="precio_venta" name="precio_venta" step="0.01" placeholder="0.00" class="input-short" required>
            </div>

            <div class="form-group-inline">
                <label for="stock">Stock:</label>
                <input type="number" id="stock" name="stock" placeholder="0" class="input-short" required>
            </div>

            <div class="form-group-inline">
                <label for="id_categoria">ID Categoría:</label>
                <select id="id_categoria" name="id_categoria" class="input-short" required>
                    <option value="">-- Elige --</option>
                    <?php
                    if ($categoriasQuery && $categoriasQuery->num_rows > 0) {
                      
                        $categoriasQuery->data_seek(0);
                        while($cat = $categoriasQuery->fetch_assoc()) {
                            echo "<option value='".$cat['id_categoria']."'>".$cat['id_categoria']." - ".$cat['nombre']."</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <button type="submit" class="btn-guardar">Guardar</button>
        </form>

        <h3>Lista de Productos Registrados</h3>
        
   
        <table class="tabla-datos">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($resultado && $resultado->num_rows > 0) {
                    while($fila = $resultado->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $fila['id_producto'] . "</td>";
                        echo "<td>P00" . $fila['id_producto'] . "</td>"; // Genera el código visual dinámico
                        echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
                        echo "<td>$" . number_format($fila['precio_venta'], 1) . "</td>";
                        echo "<td>" . $fila['stock'] . "</td>";
                        echo "<td>" . $fila['id_categoria'] . "</td>";
                        echo "<td>
                                <a href='editar_producto.php?id=" . $fila['id_producto'] . "' class='btn-action btn-editar'>Editar</a>
                                <a href='eliminar_producto.php?id=" . $fila['id_producto'] . "' class='btn-action btn-borrar' onclick='return confirm(\"¿Seguro de borrar?\")\'>Borrar</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' style='text-align:center; color:#888;'>No hay productos en la base de datos.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
