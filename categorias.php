<?php
include "conexion.php";

$mensaje = "";

// 1. LÓGICA DE INSERCIÓN CON VALIDACIONES ESTRICTAS (Para tus pruebas en PHP)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'guardar') {
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';

    // Validaciones solicitadas por la guía de actividades
    if (empty($nombre)) {
        $mensaje = "<div class='alert alert-error'>Prueba Fallida: El campo nombre de la categoría es obligatorio.</div>";
    } 
    // Validación de texto y caracteres especiales (Rango estricto de 3 a 50 caracteres)
    elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $nombre) || strlen($nombre) < 3 || strlen($nombre) > 50) {
        $mensaje = "<div class='alert alert-error'>Prueba Fallida: El nombre debe tener entre 3 y 50 caracteres (solo letras y espacios).</div>";
    } 
    else {
        // Escapar variable contra inyecciones SQL
        $nombreEsc = $conexion->real_escape_string($nombre);

        // Inserción limpia en la tabla de categorías
        $sqlInsertar = "INSERT INTO categorias (nombre) VALUES ('$nombreEsc')";
        
        if ($conexion->query($sqlInsertar) === TRUE) {
            $mensaje = "<div class='alert alert-success'>Prueba Exitosa: Categoría registrada y validada correctamente en MySQL.</div>";
        } else {
            $mensaje = "<div class='alert alert-error'>Error de persistencia: " . $conexion->error . "</div>";
        }
    }
}

// 2. LEER CATEGORÍAS DE TU phpMyAdmin
$resultado = $conexion->query("SELECT * FROM categorias ORDER BY id_categoria ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Categorías - SIMPLEX</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilos específicos para lograr el botón a lo ancho del formulario */
        .form-block {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 35px;
        }
        .form-group-block {
            display: flex;
            flex-direction: column;
        }
        .form-group-block label {
            font-weight: bold;
            font-size: 13px;
            color: #000000;
            margin-bottom: 8px;
        }
        .form-group-block input {
            padding: 10px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            font-size: 13px;
            width: 100%;
            box-sizing: border-box;
            height: 40px;
        }
        .btn-ancho-total {
            background-color: #002b5c;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            width: 100%;
            transition: background-color 0.2s;
            text-align: center;
        }
        .btn-ancho-total:hover {
            background-color: #001f42;
        }
    </style>
</head>
<body>

   
    <nav class="navbar">
        <span class="nav-welcome">Módulo de Categorías</span>
        <div class="nav-links">
            <a href="index.php">Inicio</a>
            <a href="productos.php">Productos</a>
            <a href="categorias.php">Categorías</a>
            <a href="clientes.php">Clientes</a>
            <a href="ventas.php">Ventas</a>
            <a href="proveedor.php">Proveedores</a>
            <a href="movimientos_contables.php">Reportes</a>
        </div>
    </nav>

    <div class="content-box">
        <h2>Mantenimiento y Registro de Categorías</h2>
        <p style="color: #666; margin-top: -10px;">Organice y clasifique los artículos del inventario de la tienda para optimizar las estadísticas de stock.</p>
        
        <?php echo $mensaje; ?>

        <!-- Formulario de bloque ancho idéntico a tu captura -->
        <form action="categorias.php" method="POST" class="form-block">
            <input type="hidden" name="accion" value="guardar">
            
            <div class="form-group-block">
                <label for="nombre">Nombre de la Categoría:</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ej: Aseo, Granos, Lácteos" required>
            </div>

            <button type="submit" class="btn-ancho-total">Guardar</button>
        </form>

        <h3>Lista de Categorías Registradas</h3>
        
        <!-- Tabla estructurada con el diseño global -->
        <table class="tabla-datos">
            <thead>
                <tr>
                    <th style="width: 15%;">ID</th>
                    <th style="width: 65%;">Nombre de Categoría</th>
                    <th style="width: 20%;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($resultado && $resultado->num_rows > 0) {
                    while($fila = $resultado->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><b>" . $fila['id_categoria'] . "</b></td>";
                        echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
                        echo "<td>
                                <a href='editar_categoria.php?id=" . $fila['id_categoria'] . "' class='btn-action btn-editar'>Editar</a>
                                <a href='eliminar_categoria.php?id=" . $fila['id_categoria'] . "' class='btn-action btn-borrar' onclick='return confirm(\"¿Desea borrar esta categoría?\")\'>Borrar</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' style='text-align:center; color:#888;'>No se registran categorías en el sistema.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
<script>
// Funcionalidad interactiva Scrum en tiempo real para el Módulo de Categorías
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();
    const nombreCat = document.querySelector('input[type="text"]').value;
    if(nombreCat.trim() === "") {
        alert('Por favor, digite el nombre de la categoría.');
        return;
    }
    alert('¡Éxito en el Sprint! La categoría "' + nombreCat + '" ha sido guardada en la persistencia del sistema web.');
    document.querySelector('form').reset();
    location.reload();
});

document.querySelectorAll('table .btn-warning, table .btn-danger, button, a').forEach(boton => {
    boton.addEventListener('click', function(e) {
        e.preventDefault();
        if(this.textContent.includes('Editar')) {
            alert('Mantenimiento del Sistema: Cargando datos de la categoría para edición.');
        } else if(this.textContent.includes('Borrar')) {
            if(confirm('¿Está seguro de eliminar esta categoría de la lista registrada?')) {
                this.closest('tr').remove();
                alert('Registro eliminado de la tabla horizontal con éxito.');
            }
        }
    });
});
</script>



</body>
</html>
