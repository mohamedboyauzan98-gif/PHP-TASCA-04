
<?php

// Cargar las funciones que permiten leer y guardar los votos
require_once __DIR__ . '/vots.php';

// Buscar un usuario utilizando su email
function buscaUsuari(array $usuaris, string $email): ?array {

    // Recorrer todos los usuarios
    foreach ($usuaris as $usuari) {

        // Comprobar si el email coincide
        if ($usuari['email'] == $email) {
            return $usuari;
        }
    }

    // Si no encontramos el usuario, devolvemos null
    return null;
}

// Calcular cuánto tiempo ha pasado desde una fecha
function tempsDesDe(int $moment): string {

    // Restar la fecha recibida a la hora actual
    $temps = time() - $moment;

    // Comprobar si ha pasado menos de un minuto
    if ($temps < 60) {
        return "fa menys d'un minut";
    }

    // Calcular los minutos
    elseif ($temps < 3600) {

        $minuts = floor($temps / 60);

        if ($minuts == 1) {
            return "fa 1 minut";
        }

        return "fa " . $minuts . " minuts";
    }

    // Calcular las horas
    elseif ($temps < 86400) {

        $hores = floor($temps / 3600);

        if ($hores == 1) {
            return "fa 1 hora";
        }

        return "fa " . $hores . " hores";
    }

    // Calcular los días
    else {

        $dies = floor($temps / 86400);

        if ($dies == 1) {
            return "fa 1 dia";
        }

        return "fa " . $dies . " dies";
    }
}

// Comprobar si la votación sigue abierta
function votacioOberta(string $tancament): bool {

    // Convertir la fecha de cierre en un timestamp
    $dataTancament = strtotime($tancament);

    // Comparar la fecha de cierre con la hora actual
    return $dataTancament !== false && time() < $dataTancament;
}

// Comprobar si un usuario ya ha votado
function jaHaVotat(string $email): bool {

    // Leer todos los votos guardados
    $vots = llegeixVots();

    // Comprobar si el email aparece en el array
    return isset($vots[$email]);
}

// Contar los votos de cada opción
function recompte(array $vots, array $opcions): array {

    $resultats = [];

    // Crear todas las opciones con cero votos
    foreach ($opcions as $clau => $nom) {
        $resultats[$clau] = 0;
    }

    // Recorrer los votos guardados
    foreach ($vots as $vot) {

        // Sumar un voto si la opción existe
        if (isset($resultats[$vot])) {
            $resultats[$vot]++;
        }
    }

    return $resultats;
}

// Guardar un mensaje temporal en la sesión
function afegeixMissatge(string $text): void {

    $_SESSION['missatge'] = $text;
}

// Leer el mensaje y eliminarlo para que no vuelva a salir
function llegeixMissatge(): ?string {

    $missatge = $_SESSION['missatge'] ?? null;

    unset($_SESSION['missatge']);

    return $missatge;
}
