<?php
// Incluye el archivo de autenticación que contiene las funciones de verificación de sesión y roles
require_once '../includes/auth.php';

// Verifica si se proporcionó un ID en la URL, si no, redirige al inicio
if (!isset($_GET['id'])) {
    redirect('/blog-cms/');
}

// Prepara y ejecuta consulta para obtener el post específico usando parámetros para seguridad
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$_GET['id']]);
$post = $stmt->fetch();

// Si el post no existe, redirige al inicio
if (!$post) {
    redirect('/blog-cms/');
}

// Verificación de permisos: solo el autor original o un admin pueden editar
if ($post['user_id'] != $current_user['id'] && !isAdmin()) {
    redirect('/blog-cms/');
}

// Procesamiento del formulario de edición (método POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Limpieza básica de los datos del formulario
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    
    // Validación de campos obligatorios
    if (empty($title) || empty($content)) {
        $error = 'Todos los campos son requeridos';
    } else {
        // Actualización segura usando consultas preparadas
        $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
        $stmt->execute([$title, $content, $post['id']]);
        // Redirección con mensaje de éxito
        redirect('/blog-cms/?success=updated');
    }
}
?>

<!-- Incluye el encabezado común -->
<?php include '../includes/header.php'; ?>

    <h2>Editar Post</h2>
    
    <!-- Muestra error de validación si existe -->
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>
    
    <!-- Formulario de edición con valores precargados -->
    <form method="POST">
        <div class="form-group">
            <label for="title">Título</label>
            <!-- Input con valor actual escapado para seguridad XSS -->
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
        </div>
        <div class="form-group">
            <label for="content">Contenido</label>
            <!-- Textarea con contenido actual escapado -->
            <textarea id="content" name="content" required><?= htmlspecialchars($post['content']) ?></textarea>
        </div>
        <button type="submit">Actualizar</button>
    </form>

<!-- Incluye el pie de página común -->
<?php include '../includes/footer.php'; ?>