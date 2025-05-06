<?php
// Inicia o reanuda una sesión PHP para mantener el estado del usuario
session_start();

// Configuración de conexión a la base de datos
$host = 'localhost';     // Servidor de la base de datos (local)
$db   = 'blog-cms';      // Nombre de la base de datos
$user = 'root';          // Usuario de MySQL (root es el superusuario por defecto)
$pass = '';              // Contraseña del usuario (vacía por defecto en desarrollo)
$charset = 'utf8mb4';    // Codificación de caracteres (soporta emojis y caracteres especiales)

// Cadena de conexión DSN (Data Source Name) para PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Opciones de configuración para PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // Lanza excepciones en errores
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // Devuelve resultados como arrays asociativos
    PDO::ATTR_EMULATE_PREPARES   => false,                   // Usa prepared statements nativos (más seguro)
];

try {
    // Intenta establecer la conexión con la base de datos
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Manejo de errores: relanza la excepción con información detallada
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

/**
 * Redirige al usuario a una URL específica y termina la ejecución del script
 * @param string $url La URL a la que redirigir (puede ser relativa o absoluta)
 */
function redirect($url) {
    header("Location: $url");  // Envía cabecera HTTP de redirección
    exit();                    // Termina la ejecución del script inmediatamente
}
?>