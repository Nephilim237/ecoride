<?php

namespace Ecoride\Ecoride\Core;

use Ecoride\Ecoride\Services\AuthService;

class Controller
{
    protected Session $session;
    protected AuthService $auth;
    public function __construct()
    {
        $this->session = new Session();
        $this->auth = new AuthService();
    }
    protected function renderView($view, $data = []): void
    {
        // Verifier le remember token au chargement des pages
        if (!$this->auth->is_logged_in()) {
            $this->auth->login_with_remeber_toker();
        }
        extract($data);
        require __DIR__ . "/../Views/partials/header.php";
        require __DIR__ . "/../Views/{$view}.php";
        require __DIR__ . "/../Views/partials/footer.php";
    }

    protected function json($data, $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    public function redirect(string $path): void
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? ''; ///ecoride/public/index.php
        $basePath = str_replace('public/index.php', '', $scriptName);

        $url = rtrim($basePath, '/') . '/' . ltrim($path, '/');
        header("Location: $url");
        exit();
    }

}