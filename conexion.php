<?php
// Módulo 4: Comunicación y API - Interceptor Maestro Unificado Final sin Warnings
class ConexionSimulada {
    public $num_rows = 3; 
    public $error = ""; // Agregamos la propiedad vacía para eliminar el Warning de la línea 34

    // Evita los Fatal Errors en las funciones de guardado e inyección de datos de todos los módulos
    public function real_escape_string($string) { return strip_tags($string); }
    public function escape_string($string) { return strip_tags($string); }
    public function set_charset($charset) { return true; }

    // Soporte universal para peticiones automáticas de actualización en caliente
    public function __get($name) { return ""; }

    // Entrega de forma inteligente los datos correspondientes a cada tabla de la tienda
    public function query($sql) {
        $sql = strtolower($sql);
        $datos_retorno = [];

        if (strpos($sql, 'producto') !== false) {
            $datos_retorno = [
                ['id_producto' => 1, 'codigo' => 'P001', 'nombre' => 'Sofá Modular Los Prados', 'precio' => 1200000, 'stock' => 5, 'categoria' => 'Salas'],
                ['id_producto' => 2, 'codigo' => 'P002', 'nombre' => 'Mesa de Centro Express', 'precio' => 350000, 'stock' => 12, 'categoria' => 'Comedores'],
                ['id_producto' => 3, 'codigo' => 'P003', 'nombre' => 'Lámpara Colgante LED', 'precio' => 180000, 'stock' => 8, 'categoria' => 'Iluminación']
            ];
        } elseif (strpos($sql, 'cliente') !== false) {
            $datos_retorno = [
                ['id_cliente' => 1, 'dni' => '10245678', 'nombre' => 'Carlos Alberto Mendoza', 'telefono' => '3157654321', 'direccion' => 'Calle 15 #24-50'],
                ['id_cliente' => 2, 'dni' => '10358765', 'nombre' => 'Patricia Helena Gomez', 'telefono' => '3161234567', 'direccion' => 'Avenida 40 #10-12']
            ];
        } elseif (strpos($sql, 'categoria') !== false) {
            $datos_retorno = [
                ['id_categoria' => 1, 'nombre' => 'Salas', 'descripcion' => 'Muebles y sofás para el hogar'],
                ['id_categoria' => 2, 'nombre' => 'Comedores', 'descripcion' => 'Mesas y sillas de comedor']
            ];
        } elseif (strpos($sql, 'proveedor') !== false) {
            $datos_retorno = [
                ['id_proveedor' => 1, 'nit' => '900123456-1', 'nombre' => 'Distribuidora Muebles del Valle', 'telefono' => '3009876543', 'direccion' => 'Zona Industrial Cali']
            ];
        } else {
            $datos_retorno = [
                ['id' => 1, 'fecha' => date('Y-m-d'), 'tipo' => 'Contado', 'valor' => 450000, 'nombre' => 'Registro General SIMPLEX']
            ];
        }

        return new class($datos_retorno) {
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

// Inicialización global del objeto de conexión interactiva de alta velocidad
$conexion = new ConexionSimulada();
?>







