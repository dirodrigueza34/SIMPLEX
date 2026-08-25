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
document.addEventListener("DOMContentLoaded", function() {
    const tabla = document.querySelector("table tbody") || document.querySelector(".tabla-datos tbody") || document.querySelector("table");
    const formulario = document.querySelector('form');
    const botonGuardar = formulario.querySelector('button[type="submit"]') || formulario.querySelector('.btn-guardar') || formulario.querySelector('button');
    
    let filaEditando = null;

    function cargarCategoriasLocales() {
        if(tabla) tabla.innerHTML = "";
        
        const categoriasBase = [
            { id_categoria: 1, nombre: 'Granos', descripcion: 'Productos de granos y cereales' },
            { id_categoria: 2, nombre: 'Licores', descripcion: 'Bebidas alcohólicas' },
            { id_categoria: 3, nombre: 'Aseo y Hogar', 'descripcion': 'Productos de limpieza para la tienda' },
            { id_categoria: 4, nombre: 'Lacteos', 'descripcion': 'Productos derivados de la leche' },
            { id_categoria: 5, nombre: 'Carnes y Pescados', 'descripcion': 'Productos cárnicos y del mar' },
            { id_categoria: 6, nombre: 'Panadería y Pastelería', 'descripcion': 'Productos horneados y dulces' },
            { id_categoria: 7, nombre: 'Bebidas No Alcohólicas', 'descripcion': 'Refrescos, jugos y aguas' },
            { id_categoria: 8, nombre: 'Snacks y Botanas', 'descripcion': 'Aperitivos y golosinas' },
            { id_categoria: 9, nombre: 'Congelados', 'descripcion': 'Alimentos congelados para la venta' }
        ];

        let categoriasGuardadas = JSON.parse(localStorage.getItem('categorias_organizadas_losprados'));
        
        if (!categoriasGuardadas || categoriasGuardadas.length === 0) {
            categoriasGuardadas = categoriasBase;
            localStorage.setItem('categorias_organizadas_losprados', JSON.stringify(categoriasGuardadas));
        }

        categoriasGuardadas.forEach((cat, index) => {
            const nuevoId = index + 1;
            const nuevaFila = document.createElement('tr');
            nuevaFila.innerHTML = `
                <td>${nuevoId}</td>
                <td>${cat.nombre}</td>
                <td>${cat.descripcion}</td>
                <td>
                    <a href="#" class="btn btn-warning btn-sm btn-editar" style="color: #fff; font-weight: bold; background-color: #ff9800; border: none; padding: 5px 10px; border-radius: 4px; text-decoration: none; margin-right: 5px;">Editar</a>
                    <a href="#" class="btn btn-danger btn-sm btn-borrar" style="color: #fff; font-weight: bold; background-color: #f44336; border: none; padding: 5px 10px; border-radius: 4px; text-decoration: none;">Borrar</a>
                </td>
            `;
            tabla.appendChild(nuevaFila);
        });
        asignarAccionesCategorias();
    }

    function guardarEnDiscoVirtual() {
        const filas = tabla.querySelectorAll('tr');
        const listaCategorias = [];
        filas.forEach(fila => {
            if(fila.cells.length >= 3) {
                listaClientes.push({
                    id_categoria: fila.cells[0].textContent.trim(),
                    nombre: fila.cells[1].textContent.trim(),
                    descripcion: fila.cells[2].textContent.trim()
                });
            }
        });
        localStorage.setItem('categorias_organizadas_losprados', JSON.stringify(listaCategorias));
    }

    formulario.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const inputs = formulario.querySelectorAll('input[type="text"]');
        let nombre = inputs[0] ? inputs[0].value.trim() : '';
        let descripcion = inputs[1] ? inputs[1].value.trim() : '';
        
        if (nombre === "") {
            alert('Por favor, complete el nombre de la categoría.');
            return;
        }

        if (filaEditando) {
            filaEditando.cells[1].textContent = nombre;
            filaEditando.cells[2].textContent = descripcion;
            
            alert('¡Sprint Scrum Exitoso! La categoría ha sido actualizada.');
            botonGuardar.textContent = 'Guardar';
            botonGuardar.style.backgroundColor = ''; 
            filaEditando = null;
        } else {
            const totalFilas = tabla.querySelectorAll('tr').length;
            const nuevoId = totalFilas + 1;
            
            const nuevaFila = document.createElement('tr');
            nuevaFila.innerHTML = `
                <td>${nuevoId}</td>
                <td>${nombre}</td>
                <td>${descripcion}</td>
                <td>
                    <a href="#" class="btn btn-warning btn-sm btn-editar" style="color: #fff; font-weight: bold; background-color: #ff9800; border: none; padding: 5px 10px; border-radius: 4px; text-decoration: none; margin-right: 5px;">Editar</a>
                    <a href="#" class="btn btn-danger btn-sm btn-borrar" style="color: #fff; font-weight: bold; background-color: #f44336; border: none; padding: 5px 10px; border-radius: 4px; text-decoration: none;">Borrar</a>
                </td>
            `;
            tabla.appendChild(nuevaFila);
            alert('¡Sprint Scrum Exitoso! Categoría registrada.');
        }
        
        guardarEnDiscoVirtual();
        formulario.reset();
        asignarAccionesCategorias();
    });

    function asignarAccionesCategorias() {
        document.querySelectorAll('table .btn-editar').forEach(boton => {
            boton.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                filaEditando = this.closest('tr');
                
                const inputs = formulario.querySelectorAll('input[type="text"]');
                if(inputs[0]) inputs[0].value = filaEditando.cells[1].textContent.trim();
                if(inputs[1]) inputs[1].value = filaEditando.cells[2].textContent.trim();
                
                botonGuardar.textContent = 'Actualizar Categoría';
                botonGuardar.style.backgroundColor = '#ff9800';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            };
        });

        document.querySelectorAll('table .btn-borrar').forEach(boton => {
            boton.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (confirm('¿Está seguro de eliminar esta categoría?')) {
                    this.closest('tr').remove();
                    guardarEnDiscoVirtual();
                    alert('Registro eliminado con éxito.');
                    formulario.reset();
                    botonGuardar.textContent = 'Guardar';
                    botonGuardar.style.backgroundColor = '';
                    filaEditando = null;
                    
                    const filasRestantes = tabla.querySelectorAll('tr');
                    filasRestantes.forEach((f, idx) => {
                        f.cells[0].textContent = idx + 1;
                    });
                    guardarEnDiscoVirtual();
                }
            };
        });
    }

    cargarCategoriasLocales();
});
</script>



</body>
</html>
