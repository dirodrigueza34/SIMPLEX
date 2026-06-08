<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú Principal - SIMPLEX</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-top: 25px; }
        .menu-card { background: #fdfdfd; border: 1px solid #e0e0e0; padding: 20px; border-radius: 6px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.02); transition: transform 0.2s; }
        .menu-card:hover { transform: translateY(-3px); border-color: #002b5c; }
        .menu-card h4 { margin: 0 0 10px 0; color: #002b5c; font-size: 16px; }
    </style>
</head>
<body>

    <!-- Menú de navegación integrado -->
    <nav class="navbar">
        <span class="nav-welcome">SIMPLEX - Los Prados Express</span>
        <div class="nav-links">
            <a href="index.php">Inicio</a>
            <a href="productos.php">Inventario</a>
            <a href="ventas.php">Facturación</a>
            <a href="movimientos_contables.php">Caja Menor</a>
            <a href="clientes.php">Clientes</a>
            <a href="proveedor.php">Proveedores</a>
        </div>
    </nav>

    <div class="content-box">
        <h2>Sistema Integrado de Gestión Comercial</h2>
        <p>Panel unificado para el mantenimiento logístico, control de stock y auditoría de caja menor.</p>

        <div class="menu-grid">
            <div class="menu-card">
                <h4>Inventario</h4>
                <p>Mantenimiento general de existencias.</p>
                <a href="productos.php" class="btn-primary">Ingresar</a>
            </div>
            <div class="menu-card">
                <h4>Facturación</h4>
                <p>Simular salidas y ventas automáticas.</p>
                <a href="ventas.php" class="btn-primary">Ingresar</a>
            </div>
            <div class="menu-card">
                <h4>Caja Menor</h4>
                <p>Ver saldos y flujos de efectivo unificados.</p>
                <a href="movimientos_contables.php" class="btn-primary">Ingresar</a>
            </div>
            <div class="menu-card">
                <h4>Clientes</h4>
                <p>Mantenimiento de compradores.</p>
                <a href="clientes.php" class="btn-primary">Ingresar</a>
            </div>
            <div class="menu-card">
                <h4>Proveedores</h4>
                <p>Gestión de abastecimiento mayorista.</p>
                <a href="proveedor.php" class="btn-primary">Ingresar</a>
            </div>
        </div>
    </div>
</body>
</html>
