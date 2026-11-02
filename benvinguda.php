
<?php

// Iniciar la sesión
session_start();

// Cargar los datos
$dades = require __DIR__ . '/inc/dades.php';

// Cargar las funciones
require_once __DIR__ . '/inc/funcions.php';

// Comprobar que el usuario tenga la sesión iniciada
require_once __DIR__ . '/inc/protegeix.php';

// Obtener los datos del usuario
$usuari = $_SESSION['usuari'];

// Obtener la hora de inicio de sesión
$horaLogin = $_SESSION['hora_login'] ?? time();

// Calcular cuánto tiempo ha pasado desde el login
$tempsLogin = tempsDesDe($horaLogin);

// Obtener los datos de la votación
$pregunta = $dades['consulta']['pregunta'];
$opcions = $dades['consulta']['opcions'];
$tancament = $dades['consulta']['tancament'];

// Comprobar si la votación está abierta
$oberta = votacioOberta($tancament);

// Comprobar si el usuario ya ha votado
$haVotat = jaHaVotat($usuari['email']);

// Preparar la fecha de cierre
$dataTancament = date('d/m/Y \a \l\e\s H:i', strtotime($tancament));

// Mostrar la cabecera
require_once __DIR__ . '/inc/capcalera.php';

?>

<h2>Hola, <?php echo htmlspecialchars($usuari['nom'], ENT_QUOTES, 'UTF-8'); ?>!</h2>

<!-- Mostrar cuándo inició sesión -->
<p>
    Has iniciat sessió <?php echo htmlspecialchars($tempsLogin, ENT_QUOTES, 'UTF-8'); ?>.
</p>

<h3>Pregunta de la votació</h3>

<p>
    <?php echo htmlspecialchars($pregunta, ENT_QUOTES, 'UTF-8'); ?>
</p>

<!-- Mostrar si la votación está abierta o cerrada -->
<?php if ($oberta): ?>

    <p>La votació està oberta.</p>

<?php else: ?>

    <p>La votació està tancada.</p>

<?php endif; ?>

<!-- Mostrar la fecha de cierre -->
<p>
    Data de tancament:
    <?php echo htmlspecialchars($dataTancament, ENT_QUOTES, 'UTF-8'); ?>
</p>


<?php if ($usuari['rol'] == 'votant'): ?>

    <!-- Comprobar si el usuario ya ha votado -->
    <?php if ($haVotat): ?>

        <p>Ja has votat.</p>

        <a href="resultats.php">Veure els resultats</a>

    <?php elseif ($oberta): ?>

        <a href="votacio.php">Votar ara</a>

    <?php else: ?>

        <a href="resultats.php">Veure els resultats</a>

    <?php endif; ?>

<?php else: ?>

    <!-- El administrador puede consultar los resultados -->
    <a href="resultats.php">Veure els resultats</a>

<?php endif; ?>

<?php

// Mostrar el pie de página
require_once __DIR__ . '/inc/peu.php';

?>
