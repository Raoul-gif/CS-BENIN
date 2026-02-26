<?php
// app/helpers.php

if (!function_exists('str_slug')) {
    /**
     * Générer un slug à partir d'une chaîne
     * 
     * @param string $title
     * @param string $separator
     * @return string
     */
    function str_slug(string $title, string $separator = '-'): string
    {
        // Convertir en minuscules
        $title = mb_strtolower($title, 'UTF-8');
        
        // Remplacer les accents
        $title = str_replace(
            ['é', 'è', 'ê', 'ë', 'à', 'â', 'ä', 'î', 'ï', 'ô', 'ö', 'ù', 'û', 'ü', 'ç'],
            ['e', 'e', 'e', 'e', 'a', 'a', 'a', 'i', 'i', 'o', 'o', 'u', 'u', 'u', 'c'],
            $title
        );
        
        // Remplacer les caractères non alphanumériques par le séparateur
        $title = preg_replace('/[^a-z0-9]+/', $separator, $title);
        
        // Nettoyer les séparateurs en début et fin
        return trim($title, $separator);
    }
}