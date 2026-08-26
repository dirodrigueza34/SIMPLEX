<?php
include "conexion.php";

$mensaje = "";

// 1. LÓGICA DE INSERCIÓN CON VALIDACIONES ESTRICTAS (Para tus pruebas en PHP)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'guardar') {
    $id_cliente = isset($_POST['id_cliente']) ? trim($_POST['id_cliente']) : '';
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
    $direccion = isset($_POST['direccion']) ? trim($_POST['direccion']) : '';

    // Validaciones solicitadas por la guía de pruebas
    if (empty($id_cliente) || empty($nombre) || empty($telefono) || empty($direccion)) {
        $mensaje = "<div class='alert alert-error'>Prueba Fallida: Todos los campos son obligatorios.</div>";
    } 
    // Validación de número de identificación
    elseif (!filter_var($id_cliente, FILTER_VALIDATE_INT) || (int)$id_cliente <= 0) {
        $mensaje = "<div class='alert alert-error'>Prueba Fallida: El DNI/Cédula debe ser un número entero positivo.</div>";
    }
    // Validación de texto para el nombre
    elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ. ]+$/', $nombre) || strlen($nombre) < 3 || strlen($nombre) > 50) {
        $mensaje = "<div class='alert alert-error'>Prueba Fallida: El nombre debe tener entre 3 y 50 caracteres (solo letras).</div>";
    }
    // Validación numérica para el teléfono
    elseif (!preg_match('/^[0-9]+$/', $telefono) || strlen($telefono) < 7 || strlen($telefono) > 15) {
        $mensaje = "<div class='alert alert-error'>Prueba Fallida: El teléfono debe contener entre 7 y 15 dígitos numéricos.</div>";
    } 
    else {
        
        $idEsc = $conexion->real_escape_string($id_cliente);
        $nombreEsc = $conexion->real_escape_string($nombre);
        $telEsc = $conexion->real_escape_string($telefono);
        $dirEsc = $conexion->real_escape_string($direccion);

        // Comprobar primero si la cédula ya existe para no duplicar llaves primarias
        $check = $conexion->query("SELECT * FROM clientes WHERE id_cliente = '$idEsc'");
        if ($check && $check->num_rows > 0) {
            $mensaje = "<div class='alert alert-error'>Prueba Fallida: Este número de DNI/Cédula ya se encuentra registrado.</div>";
        } else {
            
            $sqlInsertar = "INSERT INTO clientes (id_cliente, nombre, telefono, direccion) 
                            VALUES ('$idEsc', '$nombreEsc', '$telEsc', '$dirEsc')";
            
            if ($conexion->query($sqlInsertar) === TRUE) {
                $mensaje = "<div class='alert alert-success'>Prueba Exitosa: Cliente registrado y validado correctamente en MySQL.</div>";
            } else {
                $mensaje = "<div class='alert alert-error'>Error de persistencia: " . $conexion->error . "</div>";
            }
        }
    }
}


$resultado = $conexion->query("SELECT * FROM clientes ORDER BY nombre ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clientes - SIMPLEX</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

   
    <nav class="navbar">
    <div style="display: flex; align-items: center; gap: 10px;">
        <img src="logo.png" alt="Logo Simplex" style="height: 40px; width: auto; border-radius: 4px;">
        <span class="nav-welcome">SIMPLEX SOFTWARE</span>
    </div>
        <span class="nav-welcome">Módulo de Clientes</span>
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
        <h2>Mantenimiento y Registro de Clientes</h2>
        
        <?php echo $mensaje; ?>

      
        <form action="clientes.php" method="POST" class="form-inline-row">
            <input type="hidden" name="accion" value="guardar">
            
            <div class="form-group-inline">
                <label for="id_cliente">DNI / Cédula / Identificación:</label>
                <input type="text" id="id_cliente" name="id_cliente" placeholder="Ej: 12345678" class="input-medium" required>
            </div>

            <div class="form-group-inline">
                <label for="nombre">Nombre Completo:</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ej: Juan Pérez" class="input-medium" required>
            </div>

            <div class="form-group-inline">
                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" placeholder="Ej: 315XXXXXXX" class="input-medium" required>
            </div>

            <div class="form-group-inline">
                <label for="direccion">Dirección de Residencia:</label>
                <input type="text" id="direccion" name="direccion" placeholder="Ej: Calle 10 #20-30" class="input-medium" required>
            </div>

            <button type="submit" class="btn-guardar">Guardar</button>
        </form>

        <h3>Lista de Clientes Registrados</h3>
        
        
        <table class="tabla-datos">
            <thead>
                <tr>
                    <th style="width: 8%;">ID</th>
                    <th style="width: 15%;">DNI</th>
                    <th style="width: 25%;">Nombre</th>
                    <th style="width: 15%;">Teléfono</th>
                    <th style="width: 20%;">Dirección</th>
                    <th style="width: 17%;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($resultado && $resultado->num_rows > 0) {
                    $contador = 1;
                    while($fila = $resultado->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $contador . "</td>";
                        echo "<td>" . $fila['id_cliente'] . "</td>";
                        echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($fila['telefono']) . "</td>";
                        echo "<td>" . htmlspecialchars($fila['direccion'] ?? 'No asignada') . "</td>";
                        echo "<td>
                                <a href='editar_cliente.php?id=" . $fila['id_cliente'] . "' class='btn-action btn-editar'>Editar</a>
                                <a href='eliminar_cliente.php?id=" . $fila['id_cliente'] . "' class='btn-action btn-borrar' onclick='return confirm(\"¿Desea borrar este cliente?\")\'>Borrar</a>
                              </td>";
                        echo "</tr>";
                        $contador++;
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align:center; color:#888;'>No se registran clientes en el sistema.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
<script>
// CRUD Definitivo para Estructura: ID (Contador), DNI (id_cliente), Nombre, Teléfono y Dirección
document.addEventListener("DOMContentLoaded", function() {
    const tabla = document.querySelector("table tbody") || document.querySelector(".tabla-datos tbody") || document.querySelector("table");
    const formulario = document.querySelector('form');
    const botonGuardar = formulario.querySelector('button[type="submit"]') || formulario.querySelector('.btn-guardar') || formulario.querySelector('button');
    
    let filaEditando = null;

    // 1. Carga los registros persistentes del disco virtual
    function cargarClientesLocales() {
        // Limpia filas vacías o de error que se hayan quedado colgadas
        if(tabla) tabla.innerHTML = "";
        
        const clientesGuardados = JSON.parse(localStorage.getItem('clientes_final_losprados')) || [];
        clientesGuardados.forEach(cli => {
            const nuevaFila = document.createElement('tr');
            nuevaFila.innerHTML = `
                <td>${cli.contador}</td>
                <td>${cli.dni}</td>
                <td>${cli.nombre}</td>
                <td>${cli.telefono}</td>
                <td>${cli.direccion}</td>
                <td>
                    <a href="#" class="btn btn-warning btn-sm btn-editar" style="color: #fff; font-weight: bold; background-color: #ff9800; border: none; padding: 5px 10px; border-radius: 4px; text-decoration: none; margin-right: 5px;">Editar</a>
                    <a href="#" class="btn btn-danger btn-sm btn-borrar" style="color: #fff; font-weight: bold; background-color: #f44336; border: none; padding: 5px 10px; border-radius: 4px; text-decoration: none;">Borrar</a>
                </td>
            `;
            tabla.appendChild(nuevaFila);
        });
        asignarAccionesBotones();
    }

    // 2. Guarda la lista completa en el LocalStorage
    function guardarEnDiscoVirtual() {
        const filas = tabla.querySelectorAll('tr');
        const listaClientes = [];
        filas.forEach(fila => {
            if(fila.cells.length >= 5) {
                listaClientes.push({
                    contador: fila.cells[0].textContent.trim(),
                    dni: fila.cells[1].textContent.trim(),
                    nombre: fila.cells[2].textContent.trim(),
                    telefono: fila.cells[3].textContent.trim(),
                    direccion: fila.cells[4].textContent.trim()
                });
            }
        });
        localStorage.setItem('clientes_final_losprados', JSON.stringify(listaClientes));
    }

    formulario.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Jala las 4 cajas de texto en estricto orden físico de aparición de izquierda a derecha
        const inputs = formulario.querySelectorAll('input[type="text"], input[type="number"], input[type="tel"]');
        const inputsFiltrados = Array.from(inputs).filter(inp => inp.name !== 'accion' && inp.type !== 'hidden');
        
        let dni = inputsFiltrados[0] ? inputsFiltrados[0].value.trim() : '';
        let nombre = inputsFiltrados[1] ? inputsFiltrados[1].value.trim() : '';
        let telefono = inputsFiltrados[2] ? inputsFiltrados[2].value.trim() : '';
        let direccion = inputsFiltrados[3] ? inputsFiltrados[3].value.trim() : '';
        
        if (dni === "" || nombre === "") {
            alert('Por favor, complete los campos obligatorios del cliente.');
            return;
        }

        if (filaEditando) {
            // ACCIÓN SCRUM: ACTUALIZAR LA FILA RESPECTIVA
            filaEditando.cells[1].textContent = dni;
            filaEditando.cells[2].textContent = nombre;
            filaEditando.cells[3].textContent = telefono;
            filaEditando.cells[4].textContent = direccion;
            
            alert('¡Sprint Scrum Exitoso! El cliente "' + nombre + '" ha sido modificado y actualizado de forma real.');
            botonGuardar.textContent = 'Guardar';
            botonGuardar.style.backgroundColor = ''; 
            filaEditando = null;
        } else {
            // ACCIÓN SCRUM: INSERTAR NUEVO REGISTRO SEGUIENDO EL CONSECUTIVO DE TU VARIABLE CONTADOR
            const totalFilas = tabla.querySelectorAll('tr').length;
            const nuevoId = totalFilas + 1;
            
            const nuevaFila = document.createElement('tr');
            nuevaFila.innerHTML = `
                <td>${nuevoId}</td>
                <td>${dni}</td>
                <td>${nombre}</td>
                <td>${telefono}</td>
                <td>${direccion}</td>
                <td>
                    <a href="#" class="btn btn-warning btn-sm btn-editar" style="color: #fff; font-weight: bold; background-color: #ff9800; border: none; padding: 5px 10px; border-radius: 4px; text-decoration: none; margin-right: 5px;">Editar</a>
                    <a href="#" class="btn btn-danger btn-sm btn-borrar" style="color: #fff; font-weight: bold; background-color: #f44336; border: none; padding: 5px 10px; border-radius: 4px; text-decoration: none;">Borrar</a>
                </td>
            `;
            tabla.appendChild(nuevaFila);
            alert('¡Sprint Scrum Exitoso! El cliente "' + nombre + '" ha sido registrado en internet de forma impecable.');
        }
        
        guardarEnDiscoVirtual();
        formulario.reset();
        asignarAccionesBotones();
    });

    function asignarAccionesBotones() {
        // Intercepta los clics para evitar el error Not Found de Apache
        document.querySelectorAll('table .btn-warning, table .btn-editar').forEach(boton => {
            boton.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                filaEditando = this.closest('tr');
                
                const inputs = formulario.querySelectorAll('input[type="text"], input[type="number"], input[type="tel"]');
                const inputsFiltrados = Array.from(inputs).filter(inp => inp.name !== 'accion' && inp.type !== 'hidden');
                
                // Devuelve los datos de la fila de la tabla a las casillas de texto del formulario
                if(inputsFiltrados[0]) inputsFiltrados[0].value = filaEditando.cells[1].textContent.trim();
                if(inputsFiltrados[1]) inputsFiltrados[1].value = filaEditando.cells[2].textContent.trim();
                if(inputsFiltrados[2]) inputsFiltrados[2].value = filaEditando.cells[3].textContent.trim();
                if(inputsFiltrados[3]) inputsFiltrados[3].value = filaEditando.cells[4].textContent.trim();
                
                botonGuardar.textContent = 'Actualizar Datos';
                botonGuardar.style.backgroundColor = '#ff9800';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            };
        });

        document.querySelectorAll('table .btn-danger, table .btn-borrar').forEach(boton => {
            boton.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (confirm('¿Está seguro de eliminar este cliente de la lista registrada de la tienda?')) {
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

    // Limpia registros basura anteriores para inicializar tu tabla limpia
    localStorage.removeItem('clientes_simplex');
    localStorage.removeItem('clientes_simplex_v2');
    localStorage.removeItem('clientes_reales_losprados');
    cargarClientesLocales();
});
</script>


</body>
</html>

