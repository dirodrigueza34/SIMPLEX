<?php
include "conexion.php";

$totalExistencias = 104;
$valorInventario = 690200.00;
$stockBajoCount = 1;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes Inventario - SIMPLEX</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .grid-tarjetas {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .tarjeta-analitica {
            background: white;
            padding: 20px;
            border-radius: 6px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border-left: 5px solid #002b5c;
        }
        .tarjeta-analitica.alerta {
            border-left-color: #dc3545;
        }
        .tarjeta-analitica.exito {
            border-left-color: #28a745;
        }
        .tarjeta-analitica h4 {
            margin: 0;
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
        }
        .tarjeta-analitica .valor {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-top: 10px;
        }
        .seccion-reporte {
            margin-bottom: 35px;
            background: white;
            padding: 20px;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .badge-status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
      
        }


        .status-entrada { background: #e2f0d9; color: #385723; }
        .status-salida { background: #fce4d6; color: #c65911; }
        .status-ajuste { background: #fff2cc; color: #d6b13d; }
    </style>
</head>
<body>

      <nav class="navbar">
    <div style="display: flex; align-items: center; gap: 10px;">
        <img src="logo.png" alt="Logo Simplex" style="height: 40px; width: auto; border-radius: 4px;">
        <span class="nav-welcome">SIMPLEX SOFTWARE</span>
    </div>
        <span class="nav-welcome">Módulo de Reportes Estadísticos</span>
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
        <h2>Panel de Control y Analítica del Inventario</h2>
        <p style="color: #666; margin-top: -10px;">Consolidado métrico del stock, rotación de mercancía y rendimiento de la Tienda Los Prados Express.</p>

        <div class="grid-tarjetas">
            <div class="tarjeta-analitica">
                <h4>Existencias Actuales</h4>
                <div class="valor"><?php echo $totalExistencias; ?> Unidades</div>
            </div>
            <div class="tarjeta-analitica exito">
                <h4>Valor del Inventario</h4>
                <div class="valor">$ <?php echo number_format($valorInventario, 2, ',', '.'); ?></div>
            </div>
            <div class="tarjeta-analitica alerta">
                <h4>Productos con Stock Bajo</h4>
                <div class="valor"><?php echo $stockBajoCount; ?> Crítico</div>
            </div>
        </div>

        <div class="seccion-reporte">
            <h3>1. Estado del Inventario y Alertas de Stock Bajo</h3>
            <table class="tabla-datos">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Stock Actual</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="background:#fff5f5;">
                        <td><b>P003</b></td>
                        <td>Leche Alquería 1L</td>
                        <td style="color:#dc3545; font-weight:bold;">2 Unidades</td>
                        <td><span class="badge-status status-salida">Reabastecer</span></td>
                    </tr>
                    <tr>
                        <td><b>P001</b></td>
                        <td>Arroz Diana 1kg</td>
                        <td>50 Unidades</td>
                        <td><span class="badge-status status-entrada">Óptimo</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="seccion-reporte">
            <h3>2. Movimientos de Mercancía (Kardex Historial de Ajustes)</h3>
            <table class="tabla-datos">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Movimiento</th>
                        <th>Cantidad</th>
                        <th>Motivo / Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2026-08-25</td>
                        <td>Aceite Premier 1L</td>
                        <td><span class="badge-status status-entrada">Entrada</span></td>
                        <td>+12 Unidades</td>
                        <td>Compra a Distribuidora ABC</td>
                    </tr>
                    <tr>
                        <td>2026-08-25</td>
                        <td>Arroz Diana 1kg</td>
                        <td><span class="badge-status status-salida">Salida</span></td>
                        <td>-5 Unidades</td>
                        <td>Venta Diaria Factura 102</td>
                    </tr>
                    <tr>
                        <td>2026-08-24</td>
                        <td>Leche Alquería 1L</td>
                        <td><span class="badge-status status-ajuste">Ajuste</span></td>
                        <td>-1 Unidad</td>
                        <td>Artículo Dañado / Avería</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="seccion-reporte">
            <h3>3. Rendimiento de Ventas y Rotación por Categoría</h3>
            <table class="tabla-datos">
                <thead>
                    <tr>
                        <th>Categoría</th>
                        <th>Producto Más Vendido</th>
                        <th>Unidades Vendidas</th>
                        <th>Nivel de Rotación</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><b>Granos</b></td>
                        <td>Arroz Diana 1kg</td>
                        <td>85 Bolsas</td>
                        <td style="color:#28a745; font-weight:bold;">Alta Rotación</td>
                    </tr>
                    <tr>
                        <td><b>Lácteos</b></td>
                        <td>Leche Alquería 1L</td>
                        <td>42 Unidades</td>
                        <td style="color:#ff9800; font-weight:bold;">Media Rotación</td>
                    </tr>
                    <tr>
                        <td><b>Aseo</b></td>
                        <td>Detergente Líquido</td>
                        <td>0 Unidades</td>
                        <td style="color:#dc3545; font-weight:bold;">Sin Movimiento</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>




</body>
</html>


