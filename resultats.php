
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

// Obtener las opciones
$opcions = $dades['consulta']['opcions'];

// Comprobar si la votación está abierta
$oberta = votacioOberta($dades['consulta']['tancament']);

// Comprobar si el usuario ya ha votado
$haVotat = jaHaVotat($usuari['email']);

// Comprobar si es administrador
$esAdmin = ($usuari['rol'] == 'admin');


// -----------------------------------------
// COMPROBAR SI PUEDE VER LOS RESULTADOS
// -----------------------------------------

// Si no es administrador, no ha votado y la votación sigue abierta
if (!$esAdmin && !$haVotat && $oberta) {

    // Guardar un mensaje
    afegeixMissatge('Podràs veure els resultats quan hagis votat.');

    // Ir a la página de votación
    header('Location: votacio.php');
    exit;
}


// -----------------------------------------
// CALCULAR LOS RESULTADOS
// -----------------------------------------

// Leer todos los votos
$vots = llegeixVots();

// Contar los votos de cada opción
$resultats = recompte($vots, $opcions);

// Calcular el total de votos
$totalVots = 0;

foreach ($resultats as $quantitat) {
    $totalVots = $totalVots + $quantitat;
}

// Calcular el número de votantes
$totalVotants = 0;

foreach ($dades['usuaris'] as $u) {

    // Contar solamente los usuarios con rol votante
    if ($u['rol'] == 'votant') {
        $totalVotants++;
    }
}

// Calcular cuántos votantes han participado
$participacio = 0;

foreach ($dades['usuaris'] as $u) {

    // Comprobar si el usuario es votante
    if ($u['rol'] == 'votant') {

        // Comprobar si aparece en los votos
        if (isset($vots[$u['email']])) {
            $participacio++;
        }
    }
}

// Mostrar la cabecera
require_once __DIR__ . '/inc/capcalera.php';

?>

<h2>Resultats de la votació</h2>

<p>
    <?php echo htmlspecialchars($dades['consulta']['pregunta'], ENT_QUOTES, 'UTF-8'); ?>
</p>

<!-- Tabla con los resultados -->
<table border="1" cellpadding="8">

    <tr>
        <th>Opció</th>
        <th>Vots</th>
        <th>Percentatge</th>
    </tr>

    <!-- Recorrer todas las opciones -->
    <?php foreach ($opcions as $clau => $nom): ?>

        <?php

        // Obtener los votos de esta opción
        $quantitat = $resultats[$clau];

        // Calcular el porcentaje
        if ($totalVots > 0) {
            $percentatge = round(($quantitat / $totalVots) * 100);
        } else {
            $percentatge = 0;
        }

        ?>

        <tr>

            <td>
                <?php echo htmlspecialchars($nom, ENT_QUOTES, 'UTF-8'); ?>
            </td>

            <td>
                <?php echo $quantitat; ?>
            </td>

            <td>
                <?php echo $percentatge; ?>%
            </td>

        </tr>

    <?php endforeach; ?>

</table>

<!-- Mostrar la participación -->
<p>
    Participació:
    <?php echo $participacio; ?>
    de
    <?php echo $totalVotants; ?>
    votants
</p>

<?php

// Mostrar el pie de página
require_once __DIR__ . '/inc/peu.php';

?>
