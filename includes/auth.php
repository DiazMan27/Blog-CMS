<?php
// Incluye el archivo de configuración que contiene las constantes y configuración de la base de datos
require_once 'config.php';

// Verifica si el usuario no está logueado comprobando la sesión
if (!isset($_SESSION['user_id'])) {
    // Redirige al login si no hay sesión activa
    redirect('/blog-cms/auth/login.php');
}

// Inicializa la variable del usuario actual
$current_user = null;

// Si existe una sesión de usuario, obtiene sus datos de la base de datos
if (isset($_SESSION['user_id'])) {
    // Prepara y ejecuta consulta segura para obtener información del usuario
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $current_user = $stmt->fetch();  // Almacena los datos del usuario
}

/**
 * Verifica si el usuario actual tiene rol de administrador
 * @return bool True si es admin, False si no
 */
function isAdmin() {
    global $current_user;  // Accede a la variable global $current_user
    // Comprueba que exista el usuario y que su rol sea 'admin'
    return $current_user && $current_user['role'] === 'admin';
}
?>