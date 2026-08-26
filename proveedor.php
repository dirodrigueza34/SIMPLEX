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
    <div style="display: flex; align-items: center; gap: 10px;">
        <img src="logo.png" alt="Logo Simplex" style="height: 40px; width: auto; border-radius: 4px;">
        <span class="nav-welcome">SIMPLEX SOFTWARE</span>
    </div>
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
        <th>ID</th>
        <th>NIT</th>
        <th>Nombre</th>
        <th>Teléfono</th>
        <th>Acciones</th>
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


document.addEventListener("DOMContentLoaded", function() {
    const tabla = document.querySelector("table tbody") || document.querySelector(".tabla-datos tbody") || document.querySelector("table");
    const formulario = document.querySelector('form');
    const botonGuardar = formulario.querySelector('button[type="submit"]') || formulario.querySelector('.btn-guardar') || formulario.querySelector('button');
    
    let filaEditando = null;

    function cargarProveedoresLocales() {
        if(tabla) tabla.innerHTML = "";
        
        const proveedoresBase = [
            { nit: '900111222-1', nombre: 'Distribuidora ABC', telefono: '3001234567' },
            { nit: '900333444-2', nombre: 'Proveedor XYZ', telefono: '3109876543' },
            { nit: '860003020-1', nombre: 'Alpina', telefono: '1235058' },
            { nit: '890900608-9', nombre: 'ALKOSTO', telefono: '3175318215' },
            { nit: '901222333-6', nombre: 'Distribuidora de Alimentos del Valle', telefono: '6024445566' },
            { nit: '901444555-7', nombre: 'Distribuidora de Alimentos la Muñeca', telefono: '6023332241' }
        ];

        let proveedoresGuardados = JSON.parse(localStorage.getItem('proveedores_simetricos_losprados'));
        
        if (!proveedoresGuardados || proveedoresGuardados.length === 0) {
            proveedoresGuardados = proveedoresBase;
            localStorage.setItem('proveedores_simetricos_losprados', JSON.stringify(proveedoresGuardados));
        }

        proveedoresGuardados.forEach((prov, index) => {
            const nuevoId = index + 1;
            const nuevaFila = document.createElement('tr');
            nuevaFila.innerHTML = `
                <td>${nuevoId}</td>
                <td>${prov.nit}</td>
                <td>${prov.nombre}</td>
                <td>${prov.telefono}</td>
                <td>
                    <a href="#" class="btn btn-warning btn-sm btn-editar" style="color: #fff; font-weight: bold; background-color: #ff9800; border: none; padding: 5px 10px; border-radius: 4px; text-decoration: none; margin-right: 5px;">Editar</a>
                    <a href="#" class="btn btn-danger btn-sm btn-borrar" style="color: #fff; font-weight: bold; background-color: #f44336; border: none; padding: 5px 10px; border-radius: 4px; text-decoration: none;">Borrar</a>
                </td>
            `;
            tabla.appendChild(nuevaFila);
        });
        asignarAccionesProveedores();
    }

    function guardarEnDiscoVirtual() {
        const filas = tabla.querySelectorAll('tr');
        const listaProveedores = [];
        filas.forEach(fila => {
            if(fila.cells.length >= 4) {
                listaProveedores.push({
                    nit: fila.cells[1].textContent.trim(),
                    nombre: fila.cells[2].textContent.trim(),
                    telefono: fila.cells[3].textContent.trim()
                });
            }
        });
        localStorage.setItem('proveedores_simetricos_losprados', JSON.stringify(listaProveedores));
    }

    formulario.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const txtNombre = formulario.querySelector('input[placeholder*="ABC"]') || formulario.querySelector('input[type="text"]');
        const txtTelefono = formulario.querySelector('input[placeholder*="300"]') || formulario.querySelector('input[type="number"]') || formulario.querySelector('input[type="tel"]');
        
        let nombre = txtNombre ? txtNombre.value.trim() : '';
        let telefono = txtTelefono ? txtTelefono.value.trim() : '';
        
        if (nombre === "" || telefono === "") {
            alert('Por favor, complete todos los campos.');
            return;
        }

        if (filaEditando) {
            filaEditando.cells[2].textContent = nombre;
            filaEditando.cells[3].textContent = telefono;
            
            alert('¡Sprint Scrum Exitoso! El proveedor "' + nombre + '" ha sido modificado y actualizado.');
            botonGuardar.textContent = 'Guardar';
            botonGuardar.style.backgroundColor = ''; 
            filaEditando = null;
        } else {
            const totalFilas = tabla.querySelectorAll('tr').length;
            const nuevoId = totalFilas + 1;
            const nitGenerado = "901" + Math.floor(100000 + Math.random() * 900000) + "-" + nuevoId;
            
            const nuevaFila = document.createElement('tr');
            nuevaFila.innerHTML = `
                <td>${nuevoId}</td>
                <td>${nitGenerado}</td>
                <td>${nombre}</td>
                <td>${telefono}</td>
                <td>
                    <a href="#" class="btn btn-warning btn-sm btn-editar" style="color: #fff; font-weight: bold; background-color: #ff9800; border: none; padding: 5px 10px; border-radius: 4px; text-decoration: none; margin-right: 5px;">Editar</a>
                    <a href="#" class="btn btn-danger btn-sm btn-borrar" style="color: #fff; font-weight: bold; background-color: #f44336; border: none; padding: 5px 10px; border-radius: 4px; text-decoration: none;">Borrar</a>
                </td>
            `;
            tabla.appendChild(nuevaFila);
            alert('¡Sprint Scrum Exitoso! El proveedor "' + nombre + '" ha sido registrado.');
        }
        
        guardarEnDiscoVirtual();
        formulario.reset();
        asignarAccionesProveedores();
    });

    function asignarAccionesProveedores() {
        document.querySelectorAll('table .btn-editar').forEach(boton => {
            boton.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                filaEditando = this.closest('tr');
                
                const txtNombre = formulario.querySelector('input[placeholder*="ABC"]') || formulario.querySelector('input[type="text"]');
                const txtTelefono = formulario.querySelector('input[placeholder*="300"]') || formulario.querySelector('input[type="number"]') || formulario.querySelector('input[type="tel"]');
                
                if(txtNombre) txtNombre.value = filaEditando.cells[2].textContent.trim();
                if(txtTelefono) txtTelefono.value = filaEditando.cells.textContent.trim();
                
                botonGuardar.textContent = 'Actualizar Proveedor';
                botonGuardar.style.backgroundColor = '#ff9800';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            };
        });

        document.querySelectorAll('table .btn-borrar').forEach(boton => {
            boton.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (confirm('¿Está seguro de eliminar este proveedor de la lista registrada de la tienda?')) {
                    this.closest('tr').remove();
                    guardarEnDiscoVirtual();
                    alert('Registro eliminado de la tabla horizontal con éxito.');
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



    cargarProveedoresLocales();
});
</script>


</body>
</html>

