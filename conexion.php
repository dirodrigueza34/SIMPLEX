<?php
class ConectorUniversal {
    public $num_rows = 1;
    public $error = "";

    public function real_escape_string($str) { return strip_tags($str); }
    public function set_charset($charset) { return true; }
    public function __get($name) { return ""; }

    public function query($sql) {
        $sql = strtolower($sql);
        $datos = [];

        if (strpos($sql, 'max(id_producto)') !== false) {
            $datos = [['max_id' => 3]];
        }
        elseif (strpos($sql, 'inventario_resumen') !== false || strpos($sql, 'sum(') !== false) {
            $datos = [['total_existencias' => 104, 'valor_total' => 690200.00, 'stock_bajo' => 1, 'ingresos' => 1450000.00, 'egresos' => 350000.00]];
        }
        elseif (strpos($sql, 'producto') !== false) {
            $datos = [
                ['id_producto' => 1, 'codigo' => 'P001', 'nombre' => 'Arroz Diana 1kg', 'precio' => 4200.00, 'stock' => 50, 'id_categoria' => 1],
                ['id_producto' => 2, 'codigo' => 'P002', 'nombre' => 'Aceite Premier 1L', 'precio' => 11500.00, 'stock' => 24, 'id_categoria' => 4],
                ['id_producto' => 3, 'codigo' => 'P003', 'nombre' => 'Leche Alquería 1L', 'precio' => 4500.00, 'stock' => 30, 'id_categoria' => 4]
            ];
        } 
        elseif (strpos($sql, 'categoria') !== false || strpos($sql, 'categorias') !== false) {
            $datos = [
                ['id_categoria' => 1, 'nombre' => 'Granos', 'descripcion' => 'Productos de granos y cereales'],
                ['id_categoria' => 2, 'nombre' => 'Licores', 'descripcion' => 'Bebidas alcohólicas'],
                ['id_categoria' => 3, 'nombre' => 'Aseo y Hogar', 'descripcion' => 'Productos de limpieza para la tienda'],
                ['id_categoria' => 4, 'nombre' => 'Lacteos', 'descripcion' => 'Productos derivados de la leche']
            ];
        }
        elseif (strpos($sql, 'cliente') !== false) {
            $datos = [[
                'id_cliente' => 1, 'dni' => '10245678', 'nombre' => 'Carlos Alberto Mendoza', 
                'telefono' => '3157654321', 'direccion' => 'Calle 15 #24-50'
            ]];
        } 
        elseif (strpos($sql, 'proveedor') !== false) {
            $datos = [
                ['id_proveedor' => 1, 'nit' => '900111222-1', 'nombre' => 'Distribuidora ABC', 'telefono' => '3001234567', 'direccion' => 'Zona Comercial Central'],
                ['id_proveedor' => 2, 'nit' => '900333444-2', 'nombre' => 'Proveedor XYZ', 'telefono' => '3109876543', 'direccion' => 'Avenida Principal #40']
            ];
        } 
        else {
            $datos = [
                ['id_detalle' => 1, 'fecha' => date('Y-m-d'), 'descripcion' => 'Venta de mercancía - Factura 001', 'tipo' => 'Debito', 'valor' => 1200000.00, 'id_asiento' => 1, 'ingresos' => 1450000.00, 'egresos' => 350000.00],
                ['id_detalle' => 2, 'fecha' => date('Y-m-d'), 'descripcion' => 'Pago a Proveedor Alpina', 'tipo' => 'Credito', 'valor' => 350000.00, 'id_asiento' => 2, 'ingresos' => 1450000.00, 'egresos' => 350000.00]
            ];
        }

        return new class($datos) {
            public $num_rows;
            private $datos;
            private $index = 0;

            public function __construct($datos) {
                $this->datos = $datos;
                $this->num_rows = count($datos);
            }

            public function fetch_assoc() {
                if ($this->index < count($this->datos)) {
                    return $this->datos[$this->index++];
                }
                return null;
            }
            
            public function data_seek($pos) { $this->index = $pos; }
        };
    }
}

$conexion = new ConectorUniversal();
?>










