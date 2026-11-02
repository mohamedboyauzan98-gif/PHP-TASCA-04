
<?php

// Ruta del archivo donde se guardan los votos
const FITXER_VOTS = __DIR__ . '/../dades/vots.json';

// Leer todos los votos guardados
function llegeixVots(): array {

    // Si todavía no existe el archivo, devolvemos un array vacío
    if (!file_exists(FITXER_VOTS)) {
        return [];
    }

    // Leer el contenido del archivo y convertirlo en un array
    $vots = json_decode(file_get_contents(FITXER_VOTS), true);

    // Devolver los votos si son correctos
    return is_array($vots) ? $vots : [];
}

// Guardar el voto de un usuario
function guardaVot(string $email, string $opcio): void {

    // Leer los votos que ya existen
    $vots = llegeixVots();

    // Guardar la opción elegida por el usuario
    $vots[$email] = $opcio;

    // Guardar todos los votos en el archivo JSON
    file_put_contents(FITXER_VOTS, json_encode($vots, JSON_PRETTY_PRINT), LOCK_EX);
}
