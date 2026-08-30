<?php
// Habilitar reporte de errores de MySQLi en silencio para que no rompa las cabeceras
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Lee las credenciales del entorno de Render que configuramos de Clever Cloud
    $host     = getenv('DB_HOST') ?: '://clever-cloud.com';
    $user     = getenv('DB_USER') ?: 'uggix6qnt9va7sxz';
    $password = getenv('DB_PASSWORD') ?: 'vNmUpyGYYMwGHsABHQN8';
    $dbname   = getenv('DB_NAME') ?: 'bedn8olbdy9dqpdnwa3q';
    $port     = 3306; // Puerto estándar de MySQL

    // Crear la conexión real utilizando MySQLi orientado a objetos
    $conexion = new mysqli($host, $user, $password, $dbname, $port);

    // Configurar la codificación de caracteres a UTF-8
    $conexion->set_charset("utf8");

} catch (mysqli_sql_exception $e) {
    // Registra el error internamente en los logs de Render sin imprimir nada en pantalla
    error_log("Error crítico de conexión a la base de datos: " . $e->getMessage());
    
    // Muestra un mensaje limpio al usuario final para no romper los headers
    exit("Error interno de conexión del servidor. Por favor, reintente más tarde.");
}

// IMPORTANTE: No colocar "?>" al final del archivo para evitar espacios vacíos que corrompan los headers.
