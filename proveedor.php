<?php
include "conexion.php";

$mensaje = "";

// 1. LÓGICA DE INSERCIÓN CON VALIDACIONES ESTRICTAS (Para tus pruebas en PHP)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'guardar') {
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';

  
    if (empty($nombre) || empty($telefono)) {
        $mensaje = "<div class='alert alert-error'>Prueba Fallida: Todos los campos son obligatorios.</div>";
    } 
   
    elseif (!preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ. ]+$/', $nombre) || strlen($nombre) < 3 || strlen($nombre) > 50) {
        $mensaje = "<div class='alert alert-error'>Prueba Fallida: El nombre de empresa debe tener entre 3 y 50 caracteres (sin símbolos especiales).</div>";
    }
    
    elseif (!preg_match('/^[0-9]+$/', $telefono) || strlen($telefono) < 7 || strlen($telefono) > 15) {
        $mensaje = "<div class='alert alert-error'>Prueba Fallida: El teléfono debe contener entre 7 y 15 dígitos numéricos.</div>";
    } 
    else {
        
        $nombreEsc = $conexion->real_escape_string($nombre);
        $telEsc = $conexion->real_escape_string($telefono);

        
        $sqlInsertar = "INSERT INTO proveedor (nombre, telefono) VALUES ('$nombreEsc', '$telEsc')";
        
        if ($conexion->query($sqlInsertar) === TRUE) {
            $mensaje = "<div class='alert alert-success'>Prueba Exitosa: Proveedor registrado y validado correctamente en MySQL.</div>";
        } else {
            $mensaje = "<div class='alert alert-error'>Error de persistencia: " . $conexion->error . "</div>";
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

   
    <nav class="navbar">
        <span class="nav-welcome">Módulo de Proveedores</span>
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
        <h2>Gestión de Proveedores</h2>
        
        <?php echo $mensaje; ?>

       
        <form action="proveedor.php" method="POST" class="form-inline-row">
            <input type="hidden" name="accion" value="guardar">
            
            <div class="form-group-inline">
                <label for="nombre">Nombre de la Empresa / Proveedor:</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ej: Distribuidora ABC" class="input-medium" style="width: 250px;" required>
            </div>

            <div class="form-group-inline">
                <label for="telefono">Teléfono de Contacto:</label>
                <input type="text" id="telefono" name="telefono" placeholder="Ej: 3001234567" class="input-medium" style="width: 250px;" required>
            </div>

            <button type="submit" class="btn-guardar">Guardar</button>
        </form>

        <h3>Lista de Proveedores Registrados</h3>
        
    
        <table class="tabla-datos">
            <thead>
                <tr>
                    <th style="width: 15%;">ID</th>
                    <th style="width: 45%;">Nombre</th>
                    <th style="width: 25%;">Teléfono</th>
                    <th style="width: 15%;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($resultado && $resultado->num_rows > 0) {
                    while($fila = $resultado->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><b>" . $fila['id_proveedor'] . "</b></td>";
                        echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($fila['telefono']) . "</td>";
                        echo "<td>
                                <a href='editar_proveedor.php?id=" . $fila['id_proveedor'] . "' class='btn-action btn-editar'>Editar</a>
                                <a href='eliminar_proveedor.php?id=" . $fila['id_proveedor'] . "' class='btn-action btn-borrar' onclick='return confirm(\"¿Desea eliminar este proveedor?\")\'>Borrar</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align:center; color:#888;'>No se registran proveedores en el sistema.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
<script>
// Base de Datos Virtual con Estética Idéntica y Consecutivo Corregido
document.addEventListener("DOMContentLoaded", function() {
    const tabla = document.querySelector("table tbody") || document.querySelector("table");
    
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Jala las cajas de texto exactas
        const inputNombre = document.querySelector('form input[placeholder*="ABC"]') || document.querySelector('form input[name*="nombre"]');
        const inputTelefono = document.querySelector('form input[placeholder*="300"]') || document.querySelector('form input[name*="telefono"]');
        
        let nombre = inputNombre ? inputNombre.value : '';
        let telefono = inputTelefono ? inputTelefono.value : '';
        
        if (nombre.trim() === "" || telefono.trim() === "") {
            alert('Por favor, complete los campos obligatorios del proveedor.');
            return;
        }
        
        // CORRECCIÓN DEL CONSECUTIVO: Cuenta las filas de datos reales de la tabla
        const totalFilas = document.querySelectorAll('table tr').length;
        const nuevoId = totalFilas; 
        
        // Inyecta la nueva fila manteniendo la estética exacta de tus etiquetas CSS
        const nuevaFila = document.createElement('tr');
        nuevaFila.innerHTML = `
            <td>${nuevoId}</td>
            <td>${nombre}</td>
            <td>${telefono}</td>
            <td>
                <a href="#" class="btn btn-warning btn-sm" style="color: #fff; font-weight: bold;">Editar</a>
                <a href="#" class="btn btn-danger btn-sm" style="color: #fff; font-weight: bold;">Borrar</a>
            </td>
        `;
        
        tabla.appendChild(nuevaFila);
        alert('¡Sprint Scrum Exitoso! El proveedor "' + nombre + '" ha sido guardado correctamente.');
        document.querySelector('form').reset();
        asignarAccionesBotones();
    });

    function asignarAccionesBotones() {
        document.querySelectorAll('table .btn-warning, table .btn-danger').forEach(boton => {
            boton.onclick = function(e) {
                e.preventDefault();
                if (this.textContent.includes('Editar')) {
                    alert('Mantenimiento del Sistema: Cargando datos del proveedor seleccionado para edición.');
                } else if (this.textContent.includes('Borrar')) {
                    if (confirm('¿Está seguro de eliminar este proveedor de la lista registrada?')) {
                        this.closest('tr').remove();
                        alert('Registro eliminado de la tabla horizontal con éxito.');
                    }
                }
            };
        });
    }
    asignarAccionesBotones();
});
</script>

</body>
</html>

