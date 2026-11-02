
<?php

// Iniciar la sesión
session_start();

// Cargar los datos
$dades = require __DIR__ . '/inc/dades.php';

// Cargar las funciones
require_once __DIR__ . '/inc/funcions.php';

// Comprobar que el usuario ha iniciado sesión
require_once __DIR__ . '/inc/protegeix.php';

// Obtener los datos del usuario
$usuari = $_SESSION['usuari'];

// Obtener las opciones de la votación
$opcions = $dades['consulta']['opcions'];

// Obtener la fecha de cierre
$tancament = $dades['consulta']['tancament'];


// -----------------------------------------
// COMPROBACIONES ANTES DE VOTAR
// -----------------------------------------

// Comprobar si el usuario es votante
if ($usuari['rol'] != 'votant') {

    afegeixMissatge('Només els votants poden votar.');

    header('Location: benvinguda.php');
    exit;
}

// Comprobar si la votación está cerrada
if (!votacioOberta($tancament)) {

    afegeixMissatge('La votació està tancada.');

    header('Location: resultats.php');
    exit;
}

// Comprobar si el usuario ya ha votado
if (jaHaVotat($usuari['email'])) {

    afegeixMissatge('Ja has votat. Gràcies!');

    header('Location: resultats.php');
    exit;
}


// -----------------------------------------
// PROCESAR EL VOTO
// -----------------------------------------

// Variable para mostrar errores del formulario
$errorVot = '';

// Comprobar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Obtener la opción seleccionada
    $opcio = $_POST['opcio'] ?? '';

    // Comprobar si la opción existe en el array
    if (!isset($opcions[$opcio])) {

        // Si no existe, mostrar un error
        $errorVot = 'Tria una de les opcions.';

    } else {

        // Guardar el voto del usuario
        guardaVot($usuari['email'], $opcio);

        // Guardar un mensaje de confirmación
        afegeixMissatge('Vot registrat. Gràcies per participar!');

        // Ir a los resultados
        header('Location: resultats.php');
        exit;
    }
}

// Mostrar la cabecera
require_once __DIR__ . '/inc/capcalera.php';

?>

<h2>Votació</h2>

<!-- Mostrar la pregunta -->
<p>
    <?php echo htmlspecialchars($dades['consulta']['pregunta'], ENT_QUOTES, 'UTF-8'); ?>
</p>

<!-- Mostrar el error si existe -->
<?php if ($errorVot != ''): ?>

    <p>
        <?php echo htmlspecialchars($errorVot, ENT_QUOTES, 'UTF-8'); ?>
    </p>

<?php endif; ?>

<!-- Formulario para votar -->
<form action="votacio.php" method="POST">

    <!-- Recorrer todas las opciones -->
    <?php foreach ($opcions as $clau => $nom): ?>

        <p>

            <input
                type="radio"
                name="opcio"
                value="<?php echo htmlspecialchars($clau, ENT_QUOTES, 'UTF-8'); ?>"
            >

            <label>
                <?php echo htmlspecialchars($nom, ENT_QUOTES, 'UTF-8'); ?>
            </label>

        </p>

    <?php endforeach; ?>

    <button type="submit">Enviar vot</button>

</form>

<?php

// Mostrar el pie de página
require_once __DIR__ . '/inc/peu.php';

?>
