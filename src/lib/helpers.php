<?php

function redirect(string $path, array $params = []): void
{
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? ''; ///ecoride/public/index.php
    $basePath = str_replace('public/index.php', '', $scriptName);

    $url = rtrim($basePath, '/') . '/' . ltrim($path, '/');
    // On check si le tableau des parametres n'est pas vide. S'il n'est pas vide, dans
    // ce cas, on va faire un traitement de ce qu'il contient
    if (!empty($params)) {
        // Filtrer les parametre null ou vide, On conserve uniquement les parametres non null
        // Tous les parametres null sont ignore
        $filteredParams = array_filter($params, function ($value) {
            return $value !== null && trim((string)$value) !== '';
        });

        if (!empty($filteredParams)) {
            $url .= '?' . http_build_query($filteredParams);
        }
    }
    header("Location: $url");
    exit();
}

function sanitize(mixed $value): string
{
    return stripslashes(htmlspecialchars(trim((string)$value), ENT_QUOTES, 'UTF-8'));
}

function assets(string $path): string
{
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? ''; ///ecoride/public/index.php
    $basePath = str_replace('public/index.php', '', $scriptName);

    return rtrim($basePath, '/') . '/assets/' . ltrim($path, '/');
}

function url(string $path = '', array $params = []): string
{
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? ''; ///ecoride/public/index.php
    $basePath = str_replace('public/index.php', '', $scriptName);
    $url = rtrim($basePath, '/') . '/' . ltrim($path, '/');

    if (!empty($params)) {
        // Filtrer les parametre null ou vide, On conserve uniquement les parametres non null
        // Tous les parametres null sont ignore
        $filteredParams = array_filter($params, function ($value) {
            return $value !== null && trim((string)$value) !== '';
        });

        if (!empty($filteredParams)) {
            $url .= '?' . http_build_query($filteredParams);
        }
    }

    return $url;
}
//
//function sanitize($value) {
//
//}

