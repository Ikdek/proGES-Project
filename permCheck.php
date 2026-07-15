<?php
// Contrôle d'accès administrateur.
//
// À inclure AVANT toute sortie HTML : une fois les en-têtes envoyés,
// header() est sans effet et la redirection ne se ferait pas.
// Pour afficher la barre de navigation admin, inclure navAdmin.php.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['connected']) || ($_SESSION['rank'] ?? '') !== 'admin') {
    header('Location: index.php');
    exit;
}
