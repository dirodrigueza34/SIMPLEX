<?php
// Módulo 4: Comunicación y API - Simulador Avanzado sin Warnings
class ConexionSimulada {
    public function query($sql) {
        return new class {
            public $num_rows = 3; // Define la propiedad para eliminar el Warning de la línea 128
            private $datos = [
                ['id_producto' => 1, 'codigo' => 'P001', 'nombre' => 'Sofá Modular Los Prados', 'precio' => 1200000, 'stock' => 5, 'categoria' => 'Salas', 'id_cliente' => 1, 'dni' => '12345678', 'nombre_completo' => 'Juan Pérez', 'telefono' => '3151234567', 'direccion' => 'Calle 10 #20-30'],
                ['id_producto' => 2, 'codigo' => 'P002', 'nombre' => 'Mesa de Centro Express', 'precio' => 350000, 'stock' => 12, 'categoria' => 'Comedores', 'id_cliente' => 2, 'dni' => '87654321', 'nombre_completo' => 'María Gomez', 'telefono' => '3167654321', 'direccion' => 'Avenida 40 #5-12']
            ];
            private $index = 0;

            public function fetch_assoc() {
                if ($this->index < count($this->datos)) {
                    return $this->datos[$this->index++];
                }
                return null;
            }
        };
    }
    public function set_charset($charset) { return true; }
}

$conexion = new ConexionSimulada();
?>



