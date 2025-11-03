<?php

namespace Ecoride\Ecoride\Core;

use Ecoride\Ecoride\Services\AuthService;

class Controller
{
    protected AuthService $auth;
    protected Session $session;

    public function __construct()
    {
        $this->auth = new AuthService();
        $this->session = new Session();
    }


    protected function renderView($view, $data = []): void
    {
        extract($data);
        require __DIR__ . "/../Views/{$view}.php";
    }

    protected function json($data, $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    protected function redirect(string $path): void
    {
        $url =  rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
        header("Location: $url");
        exit();
    }

}