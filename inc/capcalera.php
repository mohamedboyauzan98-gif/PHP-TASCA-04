
<?php

// Obtener los datos del usuario que ha iniciado sesión
$usuari = $_SESSION['usuari'] ?? [];

// Leer el mensaje temporal de la sesión
$missatge = llegeixMissatge();

?>

<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <title>Votacions de l'institut</title>
</head>

<body>

    <h1>Votacions de l'institut</h1>

    <!-- Mostrar el nombre del usuario -->
    <p>
        Usuari:
        <?php echo htmlspecialchars($usuari['nom'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
    </p>

    <!-- Menú para navegar por la aplicación -->
    <nav>

        <a href="benvinguda.php">Inici</a>

        <?php if (($usuari['rol'] ?? '') == 'votant'): ?>
            | <a href="votacio.php">Votar</a>
        <?php endif; ?>

        | <a href="resultats.php">Resultats</a>

        | <a href="logout.php">Tancar sessió</a>

    </nav>

    <hr>

    <!-- Mostrar el mensaje si existe -->
    <?php if ($missatge !== null): ?>

        <p>
            <?php echo htmlspecialchars($missatge, ENT_QUOTES, 'UTF-8'); ?>
        </p>

    <?php endif; ?>
