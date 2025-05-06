<?php
// Incluye el archivo de autenticación que contiene funciones de sesión y seguridad
require_once '../includes/auth.php';

// Verifica si la solicitud es de tipo POST (envío del formulario)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene y limpia los datos del formulario usando el operador null coalescing (??) como fallback
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    
    // Validación: verifica que los campos no estén vacíos
    if (empty($title) || empty($content)) {
        $error = 'Todos los campos son requeridos';  // Mensaje de error para mostrar al usuario
    } else {
        // Prepara y ejecuta una consulta SQL segura con parámetros para evitar inyecciones SQL
        $stmt = $pdo->prepare("INSERT INTO posts (title, content, user_id) VALUES (?, ?, ?)");
        $stmt->execute([$title, $content, $current_user['id']]);  // Asocia el post al usuario actual
        
        // Redirige a la página principal con parámetro de éxito
        redirect('/blog-cms/?success=created');
    }
}
?>

<!-- Incluye el encabezado común del sitio -->
<?php include '../includes/header.php'; ?>

    <h2>Nuevo Post</h2>
    
    <!-- Muestra mensaje de error si existe -->
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>
    
    <!-- Formulario para crear nuevo post -->
    <form method="POST">
        <div class="form-group">
            <label for="title">Título</label>
            <!-- Input para el título con atributo required para validación del cliente -->
            <input type="text" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="content">Contenido</label>
            <!-- Textarea para el contenido con validación required -->
            <textarea id="content" name="content" required></textarea>
        </div>
        <!-- Botón de envío del formulario -->
        <button type="submit">Publicar</button>
    </form>

<!-- Incluye el pie de página común -->
<?php include '../includes/footer.php'; ?>