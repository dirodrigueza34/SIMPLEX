<?php
include "conexion.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Ejecutar la eliminación en la tabla producto
    $sql = "DELETE FROM producto WHERE id_producto = $id";
    
    if ($conexion->query($sql) === TRUE) {
        header("Location: productos.php?status=deleted");
    } else {
        echo "Error al eliminar el registro de inventario: " . $conexion->error;
    }
} else {
    header("Location: productos.php");
}
?>
