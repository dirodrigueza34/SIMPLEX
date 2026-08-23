<?php
// Módulo 4: Comunicación y API - Simulador de Datos Relacionales en la Nube
class ConexionSimulada {
    public function query($sql) {
        // Simulación nativa del conjunto de resultados para la tabla horizontal de productos
        return new class {
            private $datos = [
                ['id_producto' => 1, 'codigo' => 'P001', 'nombre' => 'Sofá Modular Los Prados', 'precio' => 1200000, 'stock' => 5, 'categoria' => 'Salas'],
                ['id_producto' => 2, 'codigo' => 'P002', 'nombre' => 'Mesa de Centro Express', 'precio' => 350000, 'stock' => 12, 'categoria' => 'Comedores'],
                ['id_producto' => 3, 'codigo' => 'P003', 'nombre' => 'Lámpara Colgante LED', 'precio' => 180000, 'stock' => 8, 'categoria' => 'Iluminación']
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

// Inyección perimetral del objeto de comunicación HTTP
$conexion = new ConexionSimulada();
?>


