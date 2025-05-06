<?php
// Incluye el archivo de autenticación que contiene las funciones de verificación de sesión y roles
require_once '../includes/auth.php';

// Verifica dos condiciones para ejecutar la eliminación:
// 1. Que la solicitud sea de tipo POST (envío de formulario)
// 2. Que el usuario actual tenga privilegios de administrador
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isAdmin()) {
    // Prepara una consulta SQL segura con parámetros para eliminar el post
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    
    // Ejecuta la consulta usando el ID recibido por POST
    // El array [$_POST['id']] asocia el parámetro ? en la consulta
    $stmt->execute([$_POST['id']]);
}

// Redirige al usuario a la página principal independientemente del resultado
// Esto previene reenvíos accidentales del formulario (F5)
redirect('/blog-cms/');
?>