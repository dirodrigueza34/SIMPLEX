<?php
// Módulo 4: Comunicación y API - Puente de datos real con Supabase (Cali)
$host = "://supabase.com"; // Servidor AWS de alta velocidad en la nube
$puerto = 5432; // Puerto transaccional estándar de datos relacionales
$usuario = "postgres.epqcdgehzkmcopumcczh"; // Tu usuario oficial asignado por Supabase
$base_datos = "postgres"; // Base de datos maestra del proyecto

// Lee de forma invisible la contraseña real de Supabase desde tu Render
$pasword = getenv('DB_PASSWORD');

// Inicialización de la API orientada a objetos mysqli
$conexion = new mysqli($host, $usuario, $pasword, $base_datos, $puerto);

// Validación perimetral del canal de comunicación HTTP
if ($conexion->connect_error) {
    die("Error de comunicación real con la nube: " . $conexion->connect_error);
}

// Configuración del set de caracteres universal para evitar distorsiones en las tablas horizontales
$conexion->set_charset("utf8");
?>




