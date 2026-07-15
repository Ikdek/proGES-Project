<?php

declare(strict_types=1);

/**
 * Fonctions métier pures : aucune sortie, aucun accès à la base, aucun état global.
 *
 * C'est la seule partie du code testable unitairement — les pages, elles, mêlent
 * traitement et affichage. Toute logique extraite ici doit le rester : pas d'echo,
 * pas de header(), pas de $_GET lu directement (la valeur est passée en paramètre).
 */

/**
 * Hache un identifiant de connexion.
 *
 * Le hachage du login permet de ne pas stocker l'identifiant en clair. Il doit rester
 * déterministe : c'est ce qui permet de retrouver l'utilisateur par une simple égalité SQL.
 */
function hashLogin(string $login): string
{
    return hash('sha256', $login);
}

/**
 * Valide une colonne de tri destinée à une clause ORDER BY.
 *
 * Un nom de colonne ne peut pas être passé en paramètre lié : il est interpolé dans le SQL.
 * La liste blanche est donc la seule protection contre l'injection.
 *
 * @param array<int, string> $allowed Colonnes autorisées
 */
function sanitizeSortOrder(?string $requested, array $allowed, string $default): string
{
    if ($requested !== null && in_array($requested, $allowed, true)) {
        return $requested;
    }

    return $default;
}

/**
 * Nombre de semaines ISO-8601 d'une année : 52 ou 53.
 *
 * Le 28 décembre appartient toujours à la dernière semaine ISO de son année,
 * ce qui en fait le repère fiable pour cette mesure.
 */
function weeksInYear(int $year): int
{
    return (int) (new DateTimeImmutable(sprintf('%d-12-28', $year)))->format('W');
}

/**
 * Semaine précédente, avec passage à l'année antérieure.
 *
 * @return array{0: int, 1: int} [semaine, année]
 */
function previousWeek(int $week, int $year): array
{
    if ($week <= 1) {
        return [weeksInYear($year - 1), $year - 1];
    }

    return [$week - 1, $year];
}

/**
 * Semaine suivante, avec passage à l'année suivante.
 *
 * @return array{0: int, 1: int} [semaine, année]
 */
function nextWeek(int $week, int $year): array
{
    if ($week >= weeksInYear($year)) {
        return [1, $year + 1];
    }

    return [$week + 1, $year];
}

/**
 * Une note est valide si elle est numérique et comprise entre 0 et 20 inclus.
 */
function isValidMark(mixed $mark): bool
{
    if (!is_numeric($mark)) {
        return false;
    }

    return (float) $mark >= 0.0 && (float) $mark <= 20.0;
}
