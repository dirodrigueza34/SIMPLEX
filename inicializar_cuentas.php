<?php
include "conexion.php";

$mensaje = "";

$check = $conexion->query("SELECT COUNT(*) as total FROM cuentas_contables");
$row = $check->fetch_assoc();

if ($row['total'] == 0) {

    $sql1 = "INSERT INTO cuentas_contables (id_cuenta, nombre, tipo) VALUES (1, 'Caja', 'Activo')";
    $sql2 = "INSERT INTO cuentas_contables (id_cuenta, nombre, tipo) VALUES (2, 'Ventas', 'Ingreso')";
    $sql3 = "INSERT INTO cuentas_contables (id_cuenta, nombre, tipo) VALUES (3, 'Compras', 'Gasto')";

    if ($conexion->query($sql1) && $conexion->query($sql2) && $conexion->query($sql3)) {
        $mensaje = "<div class='alert alert-success'>Catálogo de cuentas contables inicializado con éxito.</div>";
    } else {
        $mensaje = "<div class='alert alert-error'>Error al inicializar cuentas: " . $conexion->error . "</div>";
    }
} else {
    $mensaje = "<div class='alert alert-success'>El catálogo de cuentas contables ya se encuentra estructurado en MySQL.</div>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Configuración Contable - SIMPLEX</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="content-box">
        <h2>Verificación de Estructura Contable</h2>
        <p>Este módulo comprueba que las tablas de soporte contable compartan la misma integridad que el esquema relacional.</p>
        <?php echo $mensaje; ?>
        <br>
        <a href="index.php" class="btn-primary">Ir al Menú Principal</a>
    </div>
</body>
</html>
