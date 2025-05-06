<?php
// Incluye el archivo de configuración que contiene las constantes y conexión a la base de datos
require_once '../includes/config.php';

// Si el usuario ya está autenticado, redirige a la página principal
if (isset($_SESSION['user_id'])) {
    redirect('/csm-post/');
}

// Variable para almacenar mensajes de error
$error = '';

// Procesamiento del formulario cuando se envía (método POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene los datos del formulario con operador null coalescing para valores por defecto
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Consulta preparada para buscar el usuario en la base de datos
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Verifica credenciales (comparación directa de contraseña - solo para desarrollo)
    if ($user && $user['password'] === $password) {
        // Establece la sesión del usuario
        $_SESSION['user_id'] = $user['id'];
        // Redirige al dashboard
        redirect('/blog-cms/');
    } else {
        // Mensaje de error genérico (por seguridad no se especifica qué falló)
        $error = 'Usuario o contraseña incorrectos';
    }
}
?>

<!-- Incluye el encabezado común -->
<?php include '../includes/header.php'; ?>

    <h2>Iniciar Sesión</h2>
    
    <!-- Muestra mensaje de error si existe -->
    <?php if ($error): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>
    
    <!-- Formulario de login -->
    <form method="POST">
        <div class="form-group">
            <label for="username">Usuario</label>
            <!-- Campo para el nombre de usuario con validación required -->
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <!-- Campo para la contraseña con validación required -->
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Ingresar</button>
    </form>
    
    <!-- Enlace alternativo para registro -->
    <p>¿No tienes una cuenta? <a href="/blog-cms/auth/register.php">Regístrate aquí</a></p>

<!-- Incluye el pie de página común -->
<?php include '../includes/footer.php'; ?>