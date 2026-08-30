<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Usamos la IP directa de Clever Cloud en vez del subdominio de texto para saltar el fallo de DNS
    $host     = '54.37.129.231'; 
    $user     = 'uggix6qnt9va7sxz';
    $password = 'vNmUpyGYYMwGHsABHQN8';
    $dbname   = 'bedn8olbdy9dqpdnwa3q';
    $port     = 3306;

    $conexion = new mysqli($host, $user, $password, $dbname, $port);
    $conexion->set_charset("utf8");

} catch (mysqli_sql_exception $e) {
    error_log("Error crítico de conexión a la base de datos: " . $e->getMessage());
    exit("Error interno de conexión del servidor. Por favor, reintente más tarde.");
}
