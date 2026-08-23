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
        // 2. CONDICIONAL PARA EL MÓDULO DE PROVEEDORES
        elseif (strpos($sql, 'proveedor') !== false) {
            $datos = [[
                'id_proveedor' => 1, 'nit' => '900123456-1', 
                'nombre' => 'Distribuidora Muebles del Valle', 
                'telefono' => '3009876543', 'direccion' => 'Zona Industrial Cali'
            ]];
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








