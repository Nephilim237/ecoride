<?php

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

