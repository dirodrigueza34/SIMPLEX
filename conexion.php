<?php
include "conexion.php";

$host = "127.0.0.1";
$puerto = 3306;
$usuario = "root";
$pasword = "";
$base_datos = "sistem_invent";

$conexion = new mysqli($host, $usuario, $pasword, $base_datos, $puerto);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8");
?>


