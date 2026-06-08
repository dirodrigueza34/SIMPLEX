<?php
include "conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';

    if (empty($nombre) || empty($telefono)) {
        $mensaje = "<div class='alert alert-error'>Prueba Fallida: Todos los campos son obligatorios.</div>";
    } 
    elseif (strlen($nombre) < 3 || strlen($nombre) > 50) {
        $mensaje = "<div class='alert alert-error'>Prueba Fallida: Longitud de nombre inválida (3-50 carac.).</div>";
    } 
    else {
        $nomEsc = $conexion->real_escape_string($nombre);
        $telEsc = $conexion->real_escape_string($telefono);

        $sql = "INSERT INTO proveedor (nombre, telefono) VALUES ('$nomEsc', '$telEsc')";
        if ($conexion->query($sql) === TRUE) {
            $mensaje = "<div class='alert alert-success'>Prueba Exitosa: Proveedor registrado correctamente.</div>";
        } else {
            $mensaje = "<div class='alert alert-error'>Error: " . $conexion->error . "</div>";
        }
    }
}

$resultado = $conexion->query("SELECT * FROM proveedor ORDER BY id_proveedor DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Proveedores - SIMPLEX</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="content-box">
        <h2>Gestión de Proveedores</h2>
        <p>Catálogo de distribuidores mayoristas de "Los Prados Express".</p>

        <?php echo $mensaje; ?>

        <form action="proveedor.php" method="POST" style="margin-bottom: 30px;">
            <div style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                <div class="form-group">
                    <label>Nombre del Distribuidor / Empresa:</label>
                    <input type="text" name="nombre" placeholder="Ej: Colanta S.A." required>
                </div>
                <div class="form-group">
                    <label>Teléfono de Contacto:</label>
                    <input type="text" name="telefono" placeholder="Ej: 6012345678" required>
                </div>
                <button type="submit" class="btn-primary" style="height: 40px;">Guardar Proveedor</button>
            </div>
        </form>

        <table class="tabla-datos">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de Empresa</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($resultado && $resultado->num_rows > 0) {
                    while($fila = $resultado->fetch_assoc()) {
                        echo "<tr><td><b>".$fila['id_proveedor']."</b></td><td>".htmlspecialchars($fila['nombre'])."</td><td>".htmlspecialchars($fila['telefono'])."</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' style='text-align:center; color:#888;'>No hay proveedores registrados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
