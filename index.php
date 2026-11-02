
<?php

// Iniciar la sesión
session_start();

// Cargar los datos de los usuarios
$dades = require __DIR__ . '/inc/dades.php';

// Cargar las funciones
require_once __DIR__ . '/inc/funcions.php';

// Si el usuario ya ha iniciado sesión, ir a bienvenida
if (isset($_SESSION['usuari'])) {
    header('Location: benvinguda.php');
    exit;
}




// Obtener el valor de la cookie
$darreraVisita = $_COOKIE['darrera_visita'] ?? '';

// Mensaje que aparece si es la primera visita
$missatgeVisita = "Benvingut! És la teva primera visita.";

// Comprobar que la cookie contiene un número válido
$timestamp = filter_var($darreraVisita, FILTER_VALIDATE_INT);

// Comprobar que la fecha no sea cero ni futura
if ($timestamp !== false && $timestamp > 0 && $timestamp <= time()) {

    // Calcular cuánto tiempo ha pasado
    $missatgeVisita = "La teva darrera visita va ser " . tempsDesDe($timestamp) . ".";
}

// Guardar la hora actual en la cookie durante 30 días
setcookie('darrera_visita', (string) time(), [
    'expires' => time() + (30 * 24 * 60 * 60),
    'path' => '/',
    'httponly' => true,
    'samesite' => 'Lax'
]);


// COOKIE PARA RECORDAR EL EMAIL


// Obtener el email guardado
$emailRecordat = $_COOKIE['email_recordat'] ?? '';

// Comprobar que el email sea correcto
if (!filter_var($emailRecordat, FILTER_VALIDATE_EMAIL)) {
    $emailRecordat = '';
}

// Preparar los valores del formulario
$email = $emailRecordat;
$recorda = ($emailRecordat != '');


// ---------------------------------------------------


// Comprobar si se ha enviado el formulario por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Obtener los datos del formulario
    $email = trim($_POST['email'] ?? '');
    $contrasenya = $_POST['contrasenya'] ?? '';

    // Comprobar si está marcada la casilla
    $recorda = isset($_POST['recorda']);

    // Si está marcada, guardar el email
    if ($recorda && filter_var($email, FILTER_VALIDATE_EMAIL)) {

        setcookie('email_recordat', $email, [
            'expires' => time() + (30 * 24 * 60 * 60),
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax'
        ]);

    } else {

        // Si no está marcada, borrar la cookie
        setcookie('email_recordat', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax'
        ]);

        $recorda = false;
    }

    // Buscar el usuario por su email
    $usuari = buscaUsuari($dades['usuaris'], $email);

    // Comprobar el email y la contraseña
    if ($usuari !== null && $usuari['contrasenya'] == $contrasenya) {

        // Crear un nuevo identificador de sesión
        session_regenerate_id(true);

        // Guardar los datos del usuario sin la contraseña
        unset($usuari['contrasenya']);

        $_SESSION['usuari'] = $usuari;

        // Guardar la hora de inicio de sesión
        $_SESSION['hora_login'] = time();

        // Guardar la última actividad
        $_SESSION['ultima_activitat'] = time();

        // Ir a la página de bienvenida
        header('Location: benvinguda.php');
        exit;

    } else {

        // Si los datos no son correctos, volver al login
        header('Location: index.php?error=credencials');
        exit;
    }
}


// MENSAJES DE ERRORr

// Crear un array con los mensajes de error
$errors = [
    'credencials' => 'Credencials no vàlides.',
    'acces' => 'Primer has d\'iniciar sessió.',
    'caducada' => 'La sessió ha caducat per inactivitat.'
];

// Obtener el error de la URL
$error = $_GET['error'] ?? '';

// Buscar el mensaje correspondiente
$missatgeError = $errors[$error] ?? null;

?>

<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <title>Login - Votacions</title>
</head>

<body>

    <h1>Votacions de l'institut</h1>

    <!-- Mostrar el mensaje de la última visita -->
    <p>
        <?php echo htmlspecialchars($missatgeVisita, ENT_QUOTES, 'UTF-8'); ?>
    </p>

    <!-- Mostrar el error si existe -->
    <?php if ($missatgeError !== null): ?>

        <p>
            <?php echo htmlspecialchars($missatgeError, ENT_QUOTES, 'UTF-8'); ?>
        </p>

    <?php endif; ?>

    <h2>Iniciar sessió</h2>

    <!-- Formulario para iniciar sesión -->
    <form action="index.php" method="POST">

        <p>
            <label>Email:</label>

            <input
                type="email"
                name="email"
                value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>"
                required
            >
        </p>

        <p>
            <label>Contrasenya:</label>

            <input
                type="password"
                name="contrasenya"
                required
            >
        </p>

        <p>
            <input
                type="checkbox"
                name="recorda"
                value="si"
                <?php if ($recorda): ?>
                    checked
                <?php endif; ?>
            >

            <label>Recorda el meu email</label>
        </p>

        <button type="submit">Entrar</button>

    </form>

</body>
</html>
