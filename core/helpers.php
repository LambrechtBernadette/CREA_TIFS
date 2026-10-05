<?php

namespace Core\Helpers;

function dateFormator(string $date): string
{
    $moisFr = [
        1 => 'janvier', 2 => 'février', 3 => 'mars', 4 => 'avril',
        5 => 'mai', 6 => 'juin', 7 => 'juillet', 8 => 'août',
        9 => 'septembre', 10 => 'octobre', 11 => 'novembre', 12 => 'décembre',
    ];

    $timestamp = strtotime($date);
    $jour = date('j', $timestamp);
    $mois = $moisFr[(int) date('n', $timestamp)];
    $annee = date('Y', $timestamp);

    return "$jour $mois $annee";
}

function slugify(string $projet): string
{
    // 1. Remplacer les caractères accentués par leur équivalent non accentué
    $projet = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $projet);

    // 2. Mettre en minuscules
    $projet = strtolower($projet);

    // 3. Remplacer tout ce qui n'est pas une lettre, un chiffre ou un tiret par un tiret
    $projet = preg_replace('/[^a-z0-9]+/', '-', $projet);

    // 4. Supprimer les tirets en début et fin de chaîne
    $projet = trim($projet, '-');

    return $projet;
}
function truncate(string $text, int $x): string
{
    // Si le texte est déjà assez court, on le retourne
    if (strlen($text) <= $x) {
        return $text;
    }

    // On prend uniquement les x premiers caractères
    $text = substr($text, 0, $x);

    // On cherche la position du dernier espace
    $position = strrpos($text, ' ');

    if ($position === false) {
        return $text;
    }

    // On coupe à cet espace
    return substr($text, 0, $position);
}