<?php
// Módulo 4: Comunicación y API - Conexión de red segura hacia la nube
$host = "://aivencloud.com";
$puerto = 28084;
$usuario = "avnadmin";
$base_datos = "defaultdb";

// Lee de forma invisible la contraseña desde el panel de entorno del servidor Render
$pasword = getenv('DB_PASSWORD');

// Inicialización de la API orientada a objetos mysqli
$conexion = new mysqli($host, $usuario, $pasword, $base_datos, $puerto);

if ($conexion->connect_error) {
    die("Error de comunicación HTTP: " . $conexion->connect_error);
}

$conexion->set_charset("utf8");
?>



