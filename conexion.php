<?php
// Módulo 4: Comunicación y API - Puente de datos real con Supabase (Cali)
$host = "aws-0-us-west-2.pooler.supabase.com"; 
$puerto = 5432; // Puerto transaccional estándar de datos relacionales
$usuario = "postgres.epqcdgehzkmcopumcczh"; // Tu usuario oficial asignado por Supabase
$base_datos = "postgres"; // Base de datos maestra del proyecto

// Lee de forma invisible la contraseña real de Supabase desde tu Render
$pasword = getenv('DB_PASSWORD');

// Inicialización de la API orientada a objetos mysqli
$conexion = new mysqli($host, $usuario, $pasword, $base_datos, $puerto);


if ($conexion->connect_error) {
    die("Error de comunicación real con la nube: " . $conexion->connect_error);
}


$conexion->set_charset("utf8");
?>




