<?php
include "conexion.php";

// 1. CÁLCULO ARITMÉCO DEL SALDO NETO EN CAJA MENOR (Cuenta ID = 1)
$sqlSaldo = "SELECT 
                SUM(CASE WHEN tipo = 'Debito' THEN valor ELSE 0 END) as ingresos,
                SUM(CASE WHEN tipo = 'Credito' THEN valor ELSE 0 END) as egresos
             FROM detalle_movimiento WHERE id_cuenta = 1";
$resSaldo = $conexion->query($sqlSaldo);
$saldoCajaMenor = 0.00;

if ($resSaldo && $fila = $resSaldo->fetch_assoc()) {
    // Saldo neto = Ingresos por ventas menos Egresos por compras o gastos
    $saldoCajaMenor = (float)$fila['ingresos'] - (float)$fila['egresos'];
}

// 2. CONSULTA DEL HISTORIAL UNIFICANDO LOS ASIENTOS CONTABLES
$sqlHistorial = "SELECT dm.id_detalle, mc.fecha, mc.descripcion, dm.tipo, dm.valor 
                 FROM detalle_movimiento dm 
                 INNER JOIN movimientos_contables mc ON dm.id_movimiento = mc.id_movimiento 
                 WHERE dm.id_cuenta = 1 ORDER BY dm.id_detalle DESC";
$resultado = $conexion->query($sqlHistorial);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes - SIMPLEX</title>
    <link rel="stylesheet" href="styles.css">
    <style>
      
        .card-saldo-azul {
            background-color: #002b5c; 
            color: white;
            padding: 15px 20px;
            border-radius: 6px;
            display: inline-block;
            min-width: 250px;
            margin-bottom: 25px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }
        .card-saldo-azul h4 {
            margin: 0;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            opacity: 0.9;
            font-weight: bold;
        }
        .card-saldo-azul .monto-grande {
            font-size: 26px;
            font-weight: bold;
            margin-top: 6px;
        }
        
       
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }
        .badge-debito {
            background-color: #e2f0d9; 
            color: #385723;
        }
        .badge-credito {
            background-color: #fce4d6; 
            color: #c65911;
        }
    </style>
</head>
<body>

    
    <nav class="navbar">
        <span class="nav-welcome">Panel de Reportes</span>
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
        <h2>Reportes Estadísticos</h2>
        <p style="color: #666; margin-top: -10px;">Muestra balances de ganancias, stock bajo y saldo en caja.</p>
        
       
        <div class="card-saldo-azul">
            <h4>Saldo en Caja Menor</h4>
            <div class="monto-grande">$ <?php echo number_format($saldoCajaMenor, 2, ',', ''); ?> COP</div>
        </div>

        <h3>Historial de Flujo de Efectivo (Caja Menor)</h3>
        
        <!-- Tabla estructurada con tus columnas de arqueo de caja -->
        <table class="tabla-datos">
            <thead>
                <tr>
                    <th style="width: 12%;">ID Asiento</th>
                    <th style="width: 15%;">Fecha</th>
                    <th style="width: 40%;">Descripción</th>
                    <th style="width: 18%;">Tipo de Registro</th>
                    <th style="width: 15%;">Valor</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($resultado && $resultado->num_rows > 0) {
                    while($fila = $resultado->fetch_assoc()) {
                        $tipo = $fila['tipo'];
                        // Definir el estilo de la etiqueta si es Débito o Crédito
                        $badgeClass = (strcasecmp($tipo, 'Debito') == 0) ? 'badge-debito' : 'badge-credito';
                        
                        echo "<tr>";
                        echo "<td><b>" . $fila['id_detalle'] . "</b></td>";
                        echo "<td>" . $fila['fecha'] . "</td>";
                        echo "<td>" . htmlspecialchars($fila['descripcion']) . "</td>";
                        echo "<td><span class='badge $badgeClass'>" . htmlspecialchars($tipo) . "</span></td>";
                        echo "<td>$ " . number_format($fila['valor'], 2, ',', '.') . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center; color:#888; padding:20px;'>No se registran transacciones contables en el libro de caja.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
