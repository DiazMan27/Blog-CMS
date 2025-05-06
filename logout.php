<?php
// Incluye el archivo de configuración que contiene constantes y configuración de la base de datos
require_once 'includes/config.php';

// Destruye la sesión actual, eliminando todos los datos asociados al usuario
session_destroy();

// Redirige al usuario a la página de login del sistema de autenticación
redirect('/blog-cms/auth/login.php');
?>