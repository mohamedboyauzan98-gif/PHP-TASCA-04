
# TASCA-04 - Votacions de l'institut lacetania (mohamed)

## 1. Per què guardem qui ha votat al servidor i no en una cookie?

Guardem els vots al servidor perquè així els usuaris no poden modificar fàcilment aquesta informació.

Si ho guardéssim en una cookie, un alumne podria entrar a les eines del navegador i esborrar-la per tornar a votar.

## 2. Què passa si un usuari canvia la cookie darrera_visita?

Si un usuari canvia la cookie, podria posar una data incorrecta.

Per evitar problemes, comprovem que sigui un número vàlid, que sigui més gran que zero i que no sigui una data futura.

## 3. Quina diferència hi ha entre el timeout i la cookie darrera_visita?

El timeout serveix per tancar la sessió si l'usuari porta més de cinc minuts sense activitat.

La cookie darrera_visita dura 30 dies i es guarda al navegador. La sessió es guarda al servidor.

## 4. Per què no podem llegir la cookie que acabem de guardar?

Perquè quan fem servir setcookie(), la cookie s'envia al navegador, però no es modifica $_COOKIE en la mateixa petició.

Per això hem d'esperar a la següent petició per poder llegir el valor nou.
