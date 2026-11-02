
<?php

// Comprobar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuari'])) {

    // Si no hay usuario, volver al login
    header('Location: index.php?error=acces');
    exit;
}

// Guardar la hora de la última actividad
$ultimaActivitat = $_SESSION['ultima_activitat'] ?? 0;

// Comprobar si han pasado más de cinco minutos
if (time() - $ultimaActivitat > 300) {

    // Vaciar los datos de la sesión
    $_SESSION = [];

    // Comprobar si las cookies de sesión están activadas
    if (ini_get('session.use_cookies')) {

        // Obtener los datos de la cookie de sesión
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

    // Volver al login indicando que ha caducado
    header('Location: index.php?error=caducada');
    exit;
}

// Actualizar la hora de la última actividad
$_SESSION['ultima_activitat'] = time();
