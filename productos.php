<?php
include "conexion.php";

$sql = "SELECT p.*, c.nombre AS nombre_categoria 
        FROM producto p 
        LEFT JOIN categorias c ON p.id_categoria = c.id_categoria 
        ORDER BY p.id_producto DESC";
$resultado = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario - SIMPLEX</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="content-box">
        <h2>Mantenimiento y Control de Inventario</h2>
        <p>Listado general de existencias disponibles para la tienda "Los Prados Express".</p>
        
        <a href="insertar_producto.php" class="btn-primary">Agregar Nuevo Producto</a>
        <br><br>

        <table class="tabla-datos">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Artículo</th>
                    <th>Categoría</th>
                    <th>Existencias (Stock)</th>
                    <th>Precio de Venta</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($resultado && $resultado->num_rows > 0) {
                    while($fila = $resultado->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><b>" . $fila['id_producto'] . "</b></td>";
                        echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($fila['nombre_categoria'] ?? 'Sin categoría') . "</td>";
                        echo "<td>" . $fila['stock'] . "</td>";
                        echo "<td>$ " . number_format($fila['precio_venta'], 2) . " COP</td>";
                        echo "<td>
                                <a href='editar_producto.php?id=" . $fila['id_producto'] . "' class='link-accion'>Editar</a> | 
                                <a href='eliminar_producto.php?id=" . $fila['id_producto'] . "' class='link-accion' style='color:#ff3333;' onclick='return confirm(\"¿Está seguro de eliminar este producto del inventario?\")'>Eliminar</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align:center; color:#888;'>No se registran productos en el inventario actual.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
