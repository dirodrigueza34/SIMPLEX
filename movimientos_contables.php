<?php
include "conexion.php";

// 1. CALCULO ARITMÉTICO DEL SALDO REAL EN CAJA MENOR (Cuenta ID = 1)
$sqlSaldo = "SELECT 
                SUM(CASE WHEN tipo = 'Debito' THEN valor ELSE 0 END) as ingresos,
                SUM(CASE WHEN tipo = 'Credito' THEN valor ELSE 0 END) as egresos
             FROM detalle_movimiento WHERE id_cuenta = 1";
$resSaldo = $conexion->query($sqlSaldo);
$saldoNeto = 0.00;

if ($resSaldo && $fila = $resSaldo->fetch_assoc()) {
    $saldoNeto = (float)$fila['ingresos'] - (float)$fila['egresos'];
}

// 2. CONSULTA DEL HISTORIAL DETALLADO UNIFICANDO LAS TABLAS CONTABLES
$sqlHistorial = "SELECT dm.id_detalle, mc.fecha, mc.descripcion, dm.tipo, dm.valor 
                 FROM detalle_movimiento dm
                 INNER JOIN movimientos_contables mc ON dm.id_movimiento = mc.id_movimiento
                 WHERE dm.id_cuenta = 1 ORDER BY dm.id_detalle DESC";
$historial = $conexion->query($sqlHistorial);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Caja Menor - SIMPLEX</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .card-caja {
            background-color: #002b5c;
            color: white;
            padding: 20px;
            border-radius: 6px;
            display: inline-block;
            min-width: 300px;
            margin-bottom: 25px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .card-caja h4 { margin: 0; font-size: 13px; text-transform: uppercase; opacity: 0.9; letter-spacing: 0.5px;}
        .card-caja .balance { font-size: 30px; font-weight: bold; margin-top: 5px; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; }
        .badge-ingreso { background-color: #e2f0d9; color: #385723; }
        .badge-egreso { background-color: #fce4d6; color: #c65911; }
    </style>
</head>
<body>
    <div class="content-box">
        <h2>Balance General de Caja Menor</h2>
        <p>Arqueo y conciliación de saldos contables calculados en tiempo real desde la base de datos.</p>

        <div class="card-caja">
            <h4>Saldo Líquido en Caja Menor</h4>
            <div class="balance">$ <?php echo number_format($saldoNeto, 2); ?> COP</div>
        </div>

        <h3>Historial de Libro Auxiliar de Caja</h3>
        <table class="tabla-datos">
            <thead>
                <tr>
                    <th>ID Asiento</th>
                    <th>Fecha</th>
                    <th>Concepto / Descripción</th>
                    <th>Tipo</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($historial && $historial->num_rows > 0) {
                    while($row = $historial->fetch_assoc()) {
                        $isDebito = ($row['tipo'] == 'Debito');
                        $badge = $isDebito ? "badge-ingreso" : "badge-egreso";
                        echo "<tr>";
                        echo "<td><b>" . $row['id_detalle'] . "</b></td>";
                        echo "<td>" . $row['fecha'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['descripcion']) . "</td>";
                        echo "<td><span class='badge $badge'>" . ($isDebito ? 'INGRESO (Débito)' : 'EGRESO (Crédito)') . "</span></td>";
                        echo "<td>$ " . number_format($row['valor'], 2) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center; color:#888;'>No se registran operaciones en el libro de caja.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
