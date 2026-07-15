<?php
// Démarrage de session, à inclure avant toute sortie HTML :
// session_start() ne peut plus envoyer son cookie une fois les en-têtes partis,
// et échoue donc silencieusement si on l'appelle depuis le <body>.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
