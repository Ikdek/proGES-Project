<?php

declare(strict_types=1);

/**
 * Configuration PHP-CS-Fixer — standard PSR-12.
 *
 * Ne traite que src/ et tests/ : les pages à la racine mêlent PHP et HTML,
 * et un reformatage automatique y déplacerait le balisage. Elles seront
 * intégrées au périmètre au fur et à mesure de leur découpage (voir « évolution
 * cible » de la charte).
 */

$finder = PhpCsFixer\Finder::create()
    ->in([__DIR__ . '/src', __DIR__ . '/tests']);

return (new PhpCsFixer\Config())
    // strict_param ajoute le 3e argument de in_array() etc. : classé « risqué »
    // car il change le comportement. C'est précisément ce que l'on veut ici —
    // la comparaison non stricte est à l'origine de la faille de tri corrigée.
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'strict_param' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'no_unused_imports' => true,
        'trailing_comma_in_multiline' => true,
    ])
    ->setFinder($finder);
