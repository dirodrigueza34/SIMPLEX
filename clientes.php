<?php
include "conexion.php";

$mensaje = "";

// Procesar el registro de un nuevo cliente con validaciones
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
    $direccion = isset($_POST['direccion']) ? trim($_POST['direccion']) : '';

    if (empty($nombre) || empty($telefono)) {
        $mensaje = "<div class='alert alert-error'>Prueba Fallida: Nombre y Teléfono son obligatorios.</div>";
    } 
    // Validación de texto (longitud de 3 a 50 caracteres)
    elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $nombre) || strlen($nombre) < 3 || strlen($nombre) > 50) {
        $mensaje = "<div class='alert alert-error'>Prueba Fallida: El nombre debe tener entre 3 y 50 caracteres (solo letras).</div>";
    }
    // Validación numérica estricta para teléfonos/documentos (longitud exacta o caracteres numéricos)
    elseif (!preg_match('/^[0-9]+$/', $telefono) || strlen($telefono) < 7 || strlen($telefono) > 15) {
        $mensaje = "<div class='alert alert-error'>Prueba Fallida: El teléfono debe contener entre 7 y 15 dígitos numéricos.</div>";
    } 
    else {
        $nombreEsc = $conexion->real_escape_string($nombre);
        $telEsc = $conexion->real_escape_string($telefono);
        $dirEsc = $conexion->real_escape_string($direccion);

        $sql = "INSERT INTO clientes (nombre, telefono, direccion) VALUES ('$nombreEsc', '$telEsc', '$dirEsc')";
        if ($conexion->query($sql) === TRUE) {
            $mensaje = "<div class='alert alert-success'>Prueba Exitosa: Cliente registrado y validado de forma correcta.</div>";
        } else {
            $mensaje = "<div class='alert alert-error'>Error: " . $conexion->error . "</div>";
        }
    }
}

// Consultar el listado actual
$resultado = $conexion->query("SELECT * FROM clientes ORDER BY id_cliente DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clientes - SIMPLEX</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="content-box">
        <h2>Mantenimiento y Control de Clientes</h2>
        <p>Registro y visualización de compradores de la tienda "Los Prados Express".</p>

        <?php echo $mensaje; ?>

        <form action="clientes.php" method="POST" style="margin-bottom: 30px;">
            <div style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                <div class="form-group">
                    <label>Nombre Completo:</label>
                    <input type="text" name="nombre" placeholder="Ej: Juan Pérez" required>
                </div>
                <div class="form-group">
                    <label>Teléfono / Celular:</label>
                    <input type="text" name="telefono" placeholder="Ej: 3101234567" required>
                </div>
                <div class="form-group">
                    <label>Dirección (Opcional):</label>
                    <input type="text" name="direccion" placeholder="Ej: Calle 10 #23-45">
                </div>
                <button type="submit" class="btn-primary" style="height: 40px;">Guardar Cliente</button>
            </div>
        </form>

        <h3>Lista de Clientes Registrados</h3>
        <table class="tabla-datos">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($resultado && $resultado->num_rows > 0) {
                    while($fila = $resultado->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><b>" . $fila['id_cliente'] . "</b></td>";
                        echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($fila['telefono']) . "</td>";
                        echo "<td>" . htmlspecialchars($fila['direccion'] ?? 'No registrada') . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align:center; color:#888;'>No hay clientes en la base de datos.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <br>
        <a href="productos.php" class="link-accion">← Ir al Inventario</a>
    </div>
</body>
</html>
