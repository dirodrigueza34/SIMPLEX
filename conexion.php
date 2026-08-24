<?php
// Módulo 4: Comunicación y API - Conexión Maestra Multimódulo Blindada
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
        // 1. CONDICIONAL PARA EL MÓDULO DE PRODUCTOS (INVENTARIO)
        elseif (strpos($sql, 'producto') !== false) {
            $datos = [
                ['id_producto' => 1, 'codigo' => 'P001', 'nombre' => 'Arroz Diana 1kg', 'precio' => 4200.00, 'stock' => 50, 'id_categoria' => 1],
                ['id_producto' => 2, 'codigo' => 'P002', 'nombre' => 'Aceite Premier 1L', 'precio' => 11500.00, 'stock' => 24, 'id_categoria' => 4],
                ['id_producto' => 3, 'codigo' => 'P003', 'nombre' => 'Leche Alquería 1L', 'precio' => 4500.00, 'stock' => 30, 'id_categoria' => 4]
            ];
        } 
        // 2. CONDICIONAL CORREGIDO PARA EL MÓDULO DE CATEGORÍAS (SINGULAR Y PLURAL)
        elseif (strpos($sql, 'categoria') !== false || strpos($sql, 'categorias') !== false) {
            $datos = [
                ['id_categoria' => 1, 'nombre' => 'Granos', 'descripcion' => 'Productos de granos y cereales'],
                ['id_categoria' => 2, 'nombre' => 'Licores', 'descripcion' => 'Bebidas alcohólicas'],
                ['id_categoria' => 3, 'nombre' => 'Aseo y Hogar', 'descripcion' => 'Productos de limpieza para la tienda'],
                ['id_categoria' => 4, 'nombre' => 'Lacteos', 'descripcion' => 'Productos derivados de la leche']
            ];
        }
        // 3. CONDICIONAL PARA EL MÓDULO DE CLIENTES
        elseif (strpos($sql, 'cliente') !== false) {
            $datos = [[
                'id_cliente' => 1, 'dni' => '10245678', 'nombre' => 'Carlos Alberto Mendoza', 
                'telefono' => '3157654321', 'direccion' => 'Calle 15 #24-50'
            ]];
        } 
        // 4. CONDICIONAL PARA EL MÓDULO DE PROVEEDORES
        elseif (strpos($sql, 'proveedor') !== false) {
            $datos = [[
                'id_proveedor' => 1, 'nit' => '900111222-1', 'nombre' => 'Distribuidora ABC', 
                'telefono' => '3001234567', 'direccion' => 'Zona Comercial Central'
            ]];
        } 
        // 5. CONDICIONAL PARA VENTAS Y REPORTES
        else {
            $datos = [[
                'id_asiento' => 1, 'id_detalle' => 1, 'fecha' => date('Y-m-d'),
                'descripcion' => 'Apertura de caja menor del día', 'tipo' => 'Contado',
                'valor' => 450000.00, 'ingresos' => 450000.00, 'egresos' => 0.00
            ]];
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









