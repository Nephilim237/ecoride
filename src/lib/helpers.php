<?php
function view_include($partial, $data = []): void
{
    extract($data);
    include __DIR__ . "/../Views/partials/$partial.php";
}

function provide_path(string $path = '', string $directory = null): string
{
    $directory = $directory !== null ? "$directory/" : '';
    return rtrim(BASE_URL, '/') . "/$directory" . ltrim($path, '/');
}

function redirect(string $path = ''): void
{
    $url =  provide_path($path);
    header("Location: $url");
    exit();
}

function assets(string $path): string
{
    return provide_path($path, 'assets');
}

function url(string $path = ''): string
{
    return provide_path($path);
}

function fill_form(string $field, mixed $default = '')
{
    return $_POST[$field] ?? $default;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf(string $token): bool
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
