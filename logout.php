
<?php

// Iniciar la sesión
session_start();

// Guardar el nombre antes de destruir la sesión
$nom = $_SESSION['usuari']['nom'] ?? 'usuari';

// Vaciar los datos de la sesión
$_SESSION = [];

// Comprobar si las cookies de sesión están activadas
if (ini_get('session.use_cookies')) {

    // Obtener los datos de la cookie
    $params = session_get_cookie_params();

    // Borrar la cookie de sesión
    setcookie(session_name(), '', [
        'expires' => time() - 3600,
        'path' => $params['path'],
        'domain' => $params['domain'],
        'secure' => $params['secure'],
        'httponly' => $params['httponly'],
        'samesite' => 'Lax'
    ]);
}

// Destruir la sesión
session_destroy();

?>

<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <title>Tancar sessió</title>
</head>

<body>

    <h1>Fins la propera, <?php echo htmlspecialchars($nom, ENT_QUOTES, 'UTF-8'); ?>!</h1>

    <!-- Enlace para volver al login -->
    <p>
        <a href="index.php">Tornar a entrar</a>
    </p>

</body>
</html>
