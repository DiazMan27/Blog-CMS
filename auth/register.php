<?php
// Incluye el archivo de configuración con la conexión a la base de datos y funciones
require_once '../includes/config.php';

// Redirige a la página principal si el usuario ya está autenticado
if (isset($_SESSION['user_id'])) {
    redirect('/csm-post/');
}

// Variable para almacenar mensajes de error
$error = '';

// Procesa el formulario cuando se envía con método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene y limpia los datos del formulario
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    // Validación de campos requeridos
    if (empty($username) || empty($password)) {
        $error = 'Todos los campos son requeridos';
    } 
    // Verifica que las contraseñas coincidan
    elseif ($password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden';
    } 
    // Si todo es válido, procede con el registro
    else {
        try {
            // Prepara la consulta SQL para insertar el nuevo usuario
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            // Ejecuta la consulta con los parámetros
            $stmt->execute([$username, $password]);
            
            // Obtiene el ID del nuevo usuario registrado
            $user_id = $pdo->lastInsertId();
            
            // Establece la sesión del usuario
            $_SESSION['user_id'] = $user_id;
            
            // Redirige al usuario a la página principal
            redirect('/blog-cms/');
            
        } catch (PDOException $e) {
            // Manejo específico para errores de duplicado de usuario
            if ($e->getCode() == 23000) {
                $error = 'El nombre de usuario ya existe';
            } else {
                $error = 'Error al registrar el usuario';
            }
        }
    }
}
?>

<!-- Incluye el encabezado común del sitio -->
<?php include '../includes/header.php'; ?>

    <h2>Registro</h2>
    
    <!-- Muestra mensajes de error si existen -->
    <?php if ($error): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>
    
    <!-- Formulario de registro -->
    <form method="POST">
        <div class="form-group">
            <label for="username">Usuario</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirmar Contraseña</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit">Registrarse</button>
    </form>
    
    <!-- Enlace alternativo para inicio de sesión -->
    <p>¿Ya tienes una cuenta? <a href="/blog-cms/auth/login.php">Inicia sesión aquí</a></p>

<!-- Incluye el pie de página común -->
<?php include '../includes/footer.php'; ?>