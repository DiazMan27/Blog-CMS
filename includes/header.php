<!DOCTYPE html>
<!-- Define el documento como HTML5 con idioma español -->
<html lang="es">
<head>
    <!-- Configuración básica del documento -->
    <meta charset="UTF-8"> <!-- Codificación de caracteres Unicode -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive design -->
    <title>CSM Post</title> <!-- Título de la pestaña del navegador -->
    
    <!-- Enlace a la hoja de estilos principal -->
    <link rel="stylesheet" href="/blog-cms/assets/css/style.css">
</head>
<body>
    <!-- Cabecera del sitio -->
    <header>
        <div class="container"> <!-- Contenedor para centrar contenido -->
            <h1>CSM Post</h1> <!-- Logo/Título principal del sitio -->
            
            <!-- Menú de navegación dinámico según estado de autenticación -->
            <nav>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Menú para usuarios logueados -->
                    <a href="/blog-cms/">Inicio</a> <!-- Enlace a página principal -->
                    <a href="/blog-cms/posts/create.php">Nuevo Post</a> <!-- Crear contenido -->
                    
                    <?php if (isAdmin()): ?>
                        <!-- Opción exclusiva para administradores -->
                        <a href="/blog-cms/admin/">Admin</a>
                    <?php endif; ?>
                    
                    <!-- Botón de cierre de sesión con nombre de usuario -->
                    <a href="/blog-cms/logout.php">Cerrar Sesión (<?= htmlspecialchars($current_user['username']) ?>)</a>
                
                <?php else: ?>
                    <!-- Menú para visitantes no autenticados -->
                    <a href="/blog-cms/auth/login.php">Login</a>
                    <a href="/blog-cms/auth/register.php">Registro</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    
    <!-- Contenido principal de la página -->
    <main class="container">