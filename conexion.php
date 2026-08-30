<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $host     = 'bedn8olbdy9dqpdnwa3q-mysql.services.clever-cloud.com';
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
