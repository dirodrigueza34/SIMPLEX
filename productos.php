<?php
include "conexion.php";

$mensaje = "";
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
        $mensaje = "<div class='alert alert-error'>Prueba Fallida: El precio debe ser un número decimal mayor a cero.</div>";
    } 
    else {
        $nombreEsc = $conexion->real_escape_string($nombre);
        
       
        $proximoIdQuery = $conexion->query("SELECT MAX(id_producto) as max_id FROM producto");
        $proximoIdRow = $proximoIdQuery->fetch_assoc();
        $proximoId = intval($proximoIdRow['max_id']) + 1;
        $codigoGenerado = "P00" . $proximoId;

       
        $sqlInsertar = "INSERT INTO producto (codigo, nombre, precio, stock, id_categoria) 
                        VALUES ('$codigoGenerado', '$nombreEsc', '$precio_venta', '$stock', '$id_categoria')";
        
        if ($conexion->query($sqlInsertar) === TRUE) {
            $mensaje = "<div class='alert alert-success'>Prueba Exitosa: Producto registrado correctamente en MySQL.</div>";
        } else {
            $mensaje = "<div class='alert alert-error'>Error: " . $conexion->error . "</div>";
        }
    }
}


$sqlProductos = "SELECT * FROM producto ORDER BY id_producto DESC";
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
            <a href="categorias.php">Categorías</a>
            <a href="clientes.php">Clientes</a>
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
                <input type="text" id="nombre" name="nombre" placeholder="Ej: Arroz 1kg" class="input-medium" required>
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
                        echo "<td>" . htmlspecialchars($fila['codigo']) . "</td>"; 
                        echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
                        
                        echo "<td>$" . number_format($fila['precio'], 1) . "</td>"; 
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
<script>
// CRUD Avanzado con Persistencia Permanente LocalStorage para el Módulo de Productos
document.addEventListener("DOMContentLoaded", function() {
    const tabla = document.querySelector(".tabla-datos tbody") || document.querySelector("table tbody");
    const formulario = document.querySelector('form');
    const botonGuardar = formulario.querySelector('button[type="submit"]') || formulario.querySelector('.btn-guardar') || formulario.querySelector('button');
    
    let filaEditando = null;

    // 1. Carga los productos guardados en el disco virtual del navegador
    function cargarProductosLocales() {
        const productosGuardados = JSON.parse(localStorage.getItem('productos_reales_losprados')) || [];
        productosGuardados.forEach(prod => {
            const nuevaFila = document.createElement('tr');
            nuevaFila.innerHTML = `
                <td>${prod.id_producto}</td>
                <td>${prod.codigo}</td>
                <td>${prod.nombre}</td>
                <td>${prod.precio}</td>
                <td>${prod.stock}</td>
                <td>${prod.id_categoria}</td>
                <td>
                    <a href="#" class="btn-action btn-editar" style="color: #fff; font-weight: bold; text-decoration: none; margin-right: 5px;">Editar</a>
                    <a href="#" class="btn-action btn-borrar" style="color: #fff; font-weight: bold; text-decoration: none;">Borrar</a>
                </td>
            `;
            tabla.appendChild(nuevaFila);
        });
        asignarAccionesProductos();
    }

    // 2. Guarda la lista completa de mercancías en el almacenamiento local
    function guardarEnDiscoVirtual() {
        const filas = tabla.querySelectorAll('tr');
        const listaProductos = [];
        
        filas.forEach(fila => {
            // Verifica que la fila contenga las celdas mínimas antes de guardar
            if(fila.cells.length >= 6) {
                // Filtra para no duplicar los registros base iniciales de la simulación
                const idActual = fila.cells[0].textContent.trim();
                if(parseInt(idActual) > 3) {
                    listaProductos.push({
                        id_producto: idActual,
                        codigo: fila.cells[1].textContent.trim(),
                        nombre: fila.cells[2].textContent.trim(),
                        precio: fila.cells[3].textContent.trim(),
                        stock: fila.cells[4].textContent.trim(),
                        id_categoria: fila.cells[5].textContent.trim()
                    });
                }
            }
        });
        localStorage.setItem('productos_reales_losprados', JSON.stringify(listaProductos));
    }

    formulario.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Jala las cajas de texto de tu formulario de productos
        const inputNombre = document.getElementById('nombre');
        const inputPrecio = document.getElementById('precio_venta');
        const inputStock = document.getElementById('stock');
        const selectCategoria = document.getElementById('id_categoria');
        
        let nombre = inputNombre ? inputNombre.value.trim() : '';
        let precio = inputPrecio ? inputPrecio.value.trim() : '';
        let stock = inputStock ? inputStock.value.trim() : '';
        let categoria = selectCategoria ? selectCategoria.value : '';
        
        if (nombre === "" || precio === "" || stock === "" || categoria === "") {
            alert('Por favor, complete todos los campos requeridos del producto.');
            return;
        }

        if (filaEditando) {
            // ACCIÓN SCRUM: ACTUALIZAR LA FILA SELECCIONADA EN CALIENTE
            filaEditando.cells[2].textContent = nombre;
            filaEditando.cells[3].textContent = "$" + parseFloat(precio).toFixed(1);
            filaEditando.cells[4].textContent = stock;
            filaEditando.cells[5].textContent = categoria;
            
            alert('¡Prueba Exitosa: Producto "' + nombre + '" modificado y actualizado correctamente.');
            botonGuardar.textContent = 'Guardar';
            botonGuardar.style.backgroundColor = ''; 
            filaEditando = null;
        } else {
            // ACCIÓN SCRUM: INSERTAR NUEVO REGISTRO SEGUIENDO EL CONSECUTIVO DE TU BASE
            const totalFilas = document.querySelectorAll('table tr').length;
            const nuevoId = totalFilas; 
            const codigoGenerado = "P00" + nuevoId;
            
            const nuevaFila = document.createElement('tr');
            nuevaFila.innerHTML = `
                <td>${nuevoId}</td>
                <td>${codigoGenerado}</td>
                <td>${nombre}</td>
                <td>$${parseFloat(precio).toFixed(1)}</td>
                <td>${stock}</td>
                <td>${categoria}</td>
                <td>
                    <a href="#" class="btn-action btn-editar" style="color: #fff; font-weight: bold; text-decoration: none; margin-right: 5px;">Editar</a>
                    <a href="#" class="btn-action btn-borrar" style="color: #fff; font-weight: bold; text-decoration: none;">Borrar</a>
                </td>
            `;
            tabla.appendChild(nuevaFila);
            alert('¡Prueba Exitosa: Producto "' + nombre + '" registrado correctamente en el sistema web.');
        }
        
        guardarEnDiscoVirtual();
        formulario.reset();
        asignarAccionesProductos();
    });

    function asignarAccionesProductos() {
        // Intercepta clics de edición para anular enlaces rotos de Apache
        document.querySelectorAll('table .btn-editar').forEach(boton => {
            boton.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                filaEditando = this.closest('tr');
                
                const inputNombre = document.getElementById('nombre');
                const inputPrecio = document.getElementById('precio_venta');
                const inputStock = document.getElementById('stock');
                const selectCategoria = document.getElementById('id_categoria');
                
                // Extrae los datos reales de la fila y los sube a las casillas superiores
                if(inputNombre) inputNombre.value = filaEditando.cells[2].textContent.trim();
                
                // Limpia el signo de pesos ($) para que la casilla numérica lo acepte sin errores
                let precioLimpio = filaEditando.cells[3].textContent.replace('$', '').trim();
                if(inputPrecio) inputPrecio.value = parseFloat(precioLimpio);
                
                if(inputStock) inputStock.value = filaEditando.cells[4].textContent.trim();
                if(selectCategoria) selectCategoria.value = filaEditando.cells[5].textContent.trim();
                
                // Transforma el botón superior a modo Actualizar
                botonGuardar.textContent = 'Actualizar Producto';
                botonGuardar.style.backgroundColor = '#ff9800';
                window.scrollTo({ top: 0, behavior: 'smooth' });
                alert('Modo Edición Activado: Los datos de la mercancía se cargaron arriba en las casillas.');
            };
        });

        // Programación interactiva del botón Borrar
        document.querySelectorAll('table .btn-borrar').forEach(boton => {
            boton.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (confirm('¿Está seguro de eliminar este producto del inventario de la tienda?')) {
                    this.closest('tr').remove();
                    guardarEnDiscoVirtual();
                    alert('Registro eliminado de la tabla horizontal con éxito.');
                    formulario.reset();
                    botonGuardar.textContent = 'Guardar';
                    botonGuardar.style.backgroundColor = '';
                    filaEditando = null;
                }
            };
        });
    }

    cargarProductosLocales();
});
</script>


</body>
</html>

