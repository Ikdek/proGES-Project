<?php
/**
 * Point d'accès unique à la base de données.
 *
 * Toute page qui a besoin de la base inclut ce fichier et utilise $conn
 * (ou dbConnection()). Aucune autre page ne doit instancier PDO elle-même :
 * la configuration ne doit exister qu'à un seul endroit.
 *
 * Configuration via variables d'environnement (voir .env.example),
 * avec repli sur les valeurs XAMPP par défaut.
 */

/**
 * Retourne la connexion PDO partagée, créée au premier appel.
 */
function dbConnection(): PDO
{
    static $connection = null;

    if ($connection === null) {
        $host     = getenv('DB_HOST') ?: 'localhost';
        $name     = getenv('DB_NAME') ?: 'progesdb';
        $user     = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASSWORD') ?: '';

        $connection = new PDO(
            "mysql:host={$host};dbname={$name};charset=utf8mb4",
            $user,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    }

    return $connection;
}

$conn = dbConnection();
