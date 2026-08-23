<?php
// Módulo 4: Comunicación y API - Conexión Maestra Multimódulo
class ConectorUniversal {
    public $num_rows = 1;
    public $error = "";

    // Seguridad y sanitización requerida por los formularios
    public function real_escape_string($str) { return strip_tags($str); }
    public function set_charset($charset) { return true; }

    // Truco de ingeniería: responde con un string vacío si una propiedad no existe
    public function __get($name) { return ""; }

    public function query($sql) {
        $sql = strtolower($sql);
        $datos = [];

        // 1. CONDICIONAL PARA EL MÓDULO DE VENTAS Y REPORTES
        if (strpos($sql, 'venta') !== false || strpos($sql, 'movimiento') !== false || strpos($sql, 'flujo') !== false) {
            $datos = [[
                'id_asiento' => 1, 'id_detalle' => 1, 'fecha' => date('Y-m-d'),
                'descripcion' => 'Apertura de caja menor del día', 'tipo' => 'Contado',
                'valor' => 450000.00, 'ingresos' => 450000.00, 'egresos' => 0.00
            ]];
        } 
        // CONDICIONAL ESPECÍFICO PARA EL MÓDULO DE PROVEEDORES
        elseif (strpos($sql, 'proveedor') !== false) {
            $datos = [
                ['id_proveedor' => 1, 'nit' => '900111222-1', 'nombre' => 'Distribuidora ABC', 'telefono' => '3001234567', 'direccion' => 'Zona Comercial Central'],
                ['id_proveedor' => 2, 'nit' => '900333444-2', 'nombre' => 'Proveedor XYZ', 'telefono' => '3109876543', 'direccion' => 'Avenida Principal #40'],
                ['id_proveedor' => 4, 'nit' => '860003020-1', 'nombre' => 'Alpina', 'telefono' => '1235058', 'direccion' => 'Planta Industrial de Lácteos'],
                ['id_proveedor' => 5, 'nit' => '890900608-9', 'nombre' => 'ALKOSTO', 'telefono' => '3175318215', 'direccion' => 'Hipermercado Principal'],
                ['id_proveedor' => 6, 'nit' => '901222333-6', 'nombre' => 'Distribuidora de Alimentos del Valle', 'telefono' => '6024445566', 'direccion' => 'Zona Industrial Yumbo'],
                ['id_proveedor' => 7, 'nit' => '901444555-7', 'nombre' => 'Distribuidora de Alimentos la Muñeca', 'telefono' => '6023332241', 'direccion' => 'Central de Abastos Local']
            ];
        }

        // CONDICIONAL ESPECÍFICO PARA EL MÓDULO DE CATEGORÍAS
        elseif (strpos($sql, 'categoria') !== false) {
            $datos = [
                ['id_categoria' => 1, 'nombre' => 'Granos', 'descripcion' => 'Productos de granos y cereales'],
                ['id_categoria' => 2, 'nombre' => 'Licores', 'descripcion' => 'Bebidas alcohólicas'],
                ['id_categoria' => 3, 'nombre' => 'Aseo y Hogar', 'descripcion' => 'Productos de limpieza para la tienda'],
                ['id_categoria' => 4, 'nombre' => 'Lacteos', 'descripcion' => 'Productos derivados de la leche'],
                ['id_categoria' => 5, 'nombre' => 'Carnes y Pescados', 'descripcion' => 'Productos cárnicos y del mar'],
                ['id_categoria' => 6, 'nombre' => 'Panadería y Pastelería', 'descripcion' => 'Productos horneados y dulces'],
                ['id_categoria' => 7, 'nombre' => 'Bebidas No Alcohólicas', 'descripcion' => 'Refrescos, jugos y aguas'],
                ['id_categoria' => 8, 'nombre' => 'Snacks y Botanas', 'descripcion' => 'Aperitivos y golosinas'],
                ['id_categoria' => 9, 'nombre' => 'Congelados', 'descripcion' => 'Alimentos congelados para la venta']
            ];
        }

        // 3. CONDICIONAL PARA EL MÓDULO DE PRODUCTOS
        elseif (strpos($sql, 'producto') !== false) {
            $datos = [[
                'id_producto' => 1, 'codigo' => 'P001', 
                'nombre' => 'Sofá Modular Los Prados', 'precio' => 1200000, 
                'stock' => 5, 'categoria' => 'Salas'
            ]];
        } 
        // 4. CONDICIONAL PARA EL MÓDULO DE CLIENTES
        elseif (strpos($sql, 'cliente') !== false) {
            $datos = [[
                'id_cliente' => 1, 'dni' => '10245678', 
                'nombre' => 'Carlos Alberto Mendoza', 
                'telefono' => '3157654321', 'direccion' => 'Calle 15 #24-50'
            ]];
        } 
        // RESPUESTA POR DEFECTO PARA OTROS MÓDULOS
        else {
            $datos = [['id' => 1, 'nombre' => 'Registro General SIMPLEX', 'ingresos' => 0, 'egresos' => 0, 'descripcion' => 'Soporte']];
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
        };
    }
}

$conexion = new ConectorUniversal();
?>








