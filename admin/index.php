<?php
// Incluye el archivo de autenticación que contiene la función isAdmin()
require_once '../includes/auth.php';

// Verifica si el usuario es administrador, si no, redirige
if (!isAdmin()) {
    redirect('/csm-post/');
}

// Consulta para obtener todos los usuarios ordenados por fecha de creación (más nuevos primero)
$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();

// Consulta para obtener todos los posts con información del autor
$posts = $pdo->query("
    SELECT posts.*, users.username 
    FROM posts 
    JOIN users ON posts.user_id = users.id 
    ORDER BY posts.created_at DESC
")->fetchAll();
?>

<!-- Incluye el encabezado común -->
<?php include '../includes/header.php'; ?>

    <h2>Panel de Administración</h2>
    
    <!-- Sección de gestión de usuarios -->
    <section>
        <h3>Usuarios</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Registrado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= $user['role'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
    
    <!-- Sección de gestión de posts -->
    <section>
        <h3>Posts</h3>
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <h2><?= htmlspecialchars($post['title']) ?></h2>
                <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                <div class="post-meta">
                    Publicado por <?= htmlspecialchars($post['username']) ?> el <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?>
                </div>
                <div class="actions">
                    <!-- Enlace para editar post -->
                    <a href="/blog-cms/posts/edit.php?id=<?= $post['id'] ?>" class="edit">Editar</a>
                    <!-- Formulario para eliminar post con confirmación JS -->
                    <form action="/blog-cms/posts/delete.php" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este post?')">
                        <input type="hidden" name="id" value="<?= $post['id'] ?>">
                        <button type="submit" class="delete">Eliminar</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

<!-- Incluye el pie de página común -->
<?php include '../includes/footer.php'; ?>