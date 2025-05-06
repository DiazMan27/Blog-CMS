<?php
require_once '../includes/auth.php';

function createUniqueSlug($title, $pdo) {
    $slug = strtolower(trim($title));
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE slug = ?");
    $originalSlug = $slug;
    $counter = 1;
    
    while (true) {
        $stmt->execute([$slug]);
        if ($stmt->fetchColumn() == 0) {
            return $slug;
        }
        $slug = $originalSlug . '-' . $counter;
        $counter++;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    
    if (empty($title) || empty($content)) {
        $error = 'Todos los campos son requeridos';
    } else {
        try {
            $slug = createUniqueSlug($title, $pdo);
            
            $stmt = $pdo->prepare("INSERT INTO posts (title, content, user_id, slug) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $content, $current_user['id'], $slug]);
            
            redirect('/blog-cms/?success=created');
        } catch (PDOException $e) {
            $error = 'Error al crear el post: ' . $e->getMessage();
        }
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