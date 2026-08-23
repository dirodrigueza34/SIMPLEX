<?php
// Módulo 4: Comunicación y API - Canal de Red Protegido
class RedSimulada {
    public $num_rows = 1; 
    public $error = ""; // Frena el Warning de la línea 34 de proveedor.php

    public function real_escape_string($str) { return strip_tags($str); }
    public function set_charset($charset) { return true; }

    public function query($sql) {
        return new class {
            public $num_rows = 1;
            private $datos = [
                ['id_proveedor' => 1, 'nit' => '900123456-1', 'nombre' => 'Distribuidora Muebles del Valle', 'telefono' => '3009876543', 'direccion' => 'Zona Industrial Cali']
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
}

$conexion = new RedSimulada();
?>






