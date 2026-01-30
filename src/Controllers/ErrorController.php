<?php

function notFound(): void {
    header("HTTP/1.0 404 Not Found");
    include __DIR__ . '/../views/error/404.php';
    exit();
}

function showError(string $message = '', string $title = 'Erreur') {
    $error_title = $title;
    $error_message = $message;
    include __DIR__ . '/../views/error/error.php';
    exit();
}