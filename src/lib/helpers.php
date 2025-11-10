<?php

function redirect(string $path): void
{
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? ''; ///ecoride/public/index.php
    $basePath = str_replace('public/index.php', '', $scriptName);

    $url = rtrim($basePath, '/') . '/' . ltrim($path, '/');
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

function url(string $path = ''): string
{
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? ''; ///ecoride/public/index.php
    $basePath = str_replace('public/index.php', '', $scriptName);

    return rtrim($basePath, '/') . '/' . ltrim($path, '/');
}
//
//function sanitize($value) {
//
//}

