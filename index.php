<?php
// Incluye el archivo de autenticación que contiene funciones de sesión y seguridad
require_once 'includes/auth.php';

// Prepara y ejecuta una consulta SQL para obtener todos los posts con información del autor
$stmt = $pdo->query("
    SELECT posts.*, users.username 
    FROM posts 
    JOIN users ON posts.user_id = users.id 
    ORDER BY posts.created_at DESC  // Ordena por fecha de creación (más nuevos primero)
");
// Obtiene todos los resultados como array asociativo
$posts = $stmt->fetchAll();
?>

<?php include 'includes/header.php'; ?>  <!-- Incluye el encabezado común del sitio -->
    <h2>Últimos Posts</h2>
    
    <!-- Muestra mensaje de éxito si existe en la URL -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            Post <?= $_GET['success'] === 'created' ? 'creado' : 'actualizado' ?> exitosamente
        </div>
    <?php endif; ?>
    
    <!-- Itera a través de cada post -->
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <!-- Muestra el título del post con escape de HTML para seguridad -->
            <h2><?= htmlspecialchars($post['title']) ?></h2>
            
            <!-- Muestra el contenido, convirtiendo saltos de línea en <br> y escapando HTML -->
            <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
            
            <!-- Muestra metadatos del post (autor y fecha) -->
            <div class="post-meta">
                Publicado por <?= htmlspecialchars($post['username']) ?> el <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?>
            </div>
            
            <!-- Muestra acciones solo si el usuario actual es el autor o es administrador -->
            <?php if ($current_user['id'] == $post['user_id'] || isAdmin()): ?>
                <div class="actions">
                    <!-- Enlace para editar el post -->
                    <a href="/blog-cms/posts/edit.php?id=<?= $post['id'] ?>" class="edit">Editar</a>
                    
                    <!-- Formulario para eliminar (solo visible para admins) -->
                    <?php if (isAdmin()): ?>
                        <form action="/blog-cms/posts/delete.php" method="POST" 
                              onsubmit="return confirm('¿Estás seguro de eliminar este post?')">
                            <input type="hidden" name="id" value="<?= $post['id'] ?>">
                            <button type="submit" class="delete">Eliminar</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

<?php include 'includes/footer.php'; ?>  <!-- Incluye el pie de página común -->