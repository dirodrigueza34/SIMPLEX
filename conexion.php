<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Obtenemos la URL de conexión de las variables de entorno de Render
    $databaseUrl = getenv('DATABASE_URL') ?: ($_ENV['DATABASE_URL'] ?: '');

    if (!empty($databaseUrl)) {
        // Desglosamos la URL estructurada de forma automática
        $dbParts = parse_url($databaseUrl);

        $host     = $dbParts['host'];
        $user     = $dbParts['user'];
        $password = $dbParts['pass'];
        $dbname   = ltrim($dbParts['path'], '/');
        $port     = isset($dbParts['port']) ? $dbParts['port'] : 3306;
    } else {
        // Valores de respaldo integrados si la variable no responde
        $host     = 'bedn8olbdy9dqpdnwa3q-mysql.services.clever-cloud.com';
        $user     = 'uggix6qnt9va7sxz';
        $password = 'vNmUpyGYYMwGHsABHQN8';
        $dbname   = 'bedn8olbdy9dqpdnwa3q';
        $port     = 3306;
    }

    // Iniciar conexión formal
    $conexion = new mysqli($host, $user, $password, $dbname, $port);
    $conexion->set_charset("utf8");

} catch (mysqli_sql_exception $e) {
    error_log("Error crítico de conexión a la base de datos: " . $e->getMessage());
    exit("Error interno de conexión del servidor. Por favor, reintente más tarde.");
}
