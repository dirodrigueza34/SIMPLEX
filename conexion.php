<?php
// Módulo 4: Comunicación y API - Dirección completa del host de producción
$host = "://aivencloud.com";
$puerto = 28084;
$usuario = "avnadmin";
$base_datos = "defaultdb";

// Sigue leyendo la contraseña de forma segura desde las variables de Render
$pasword = getenv('DB_PASSWORD');

$conexion = new mysqli($host, $usuario, $pasword, $base_datos, $puerto);

if ($conexion->connect_error) {
    die("Error de comunicación HTTP: " . $conexion->connect_error);
}

$conexion->set_charset("utf8");
?>




