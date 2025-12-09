<?php

namespace Ecoride\Ecoride\Core;

use Ecoride\Ecoride\Models\UserModel;
use Ecoride\Ecoride\Models\VehicleModel;
use Ecoride\Ecoride\Services\AuthService;

class Controller
{
    protected Session $session;
    protected AuthService $auth;
    protected Service $service;
    protected UserModel $userModel;
    protected VehicleModel $vehicleModel;

    public function __construct()
    {
        $this->session = new Session();
        $this->auth = new AuthService();
        $this->service = new Service();
        $this->userModel = new UserModel();
        $this->vehicleModel = new VehicleModel();
    }

    public function redirect(string $path, array $params = []): void
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? ''; ///ecoride/public/index.php
        $basePath = str_replace('public/index.php', '', $scriptName);

        $url = rtrim($basePath, '/') . '/' . ltrim($path, '/');
        // On check si le tableau des parametres n'est pas vide. S'il n'est pas vide, dans
        // ce cas, on va faire un traitement de ce qu'il contient
        if (!empty($params)) {
            // Filtrer les parametre null ou vide, On conserve uniquement les parametres non null
            // Tous les parametres null sont ignore
            $filteredParams = array_filter($params, function($value) {
                return $value !== null && trim((string)$value) !== '';
            });

            if (!empty($filteredParams)) {
                $url .= '?' . http_build_query($filteredParams);
            }
        }
        header("Location: $url");
        exit();
    }

    protected function renderView($view, $data = []): void
    {
        // Verifier le remember token au chargement des pages
        if (!$this->service->is_logged_in()) {
            $this->auth->login_with_remeber_toker();
        }
        $sessionUser = $this->service->get_connected_user() ?? null;
        $globalData = [
            'currentUser' => $sessionUser,
            'user' => $this->userModel->find_by_id($sessionUser['id']  ?? null),
            'roles' => $this->userModel->get_user_roles($sessionUser['id'] ?? null),
            'isPassenger' => $this->userModel->is_passenger($sessionUser['id'] ?? null),
            'isDriver' => $this->userModel->is_driver($sessionUser['id'] ?? null),
            'isLoggedIn' => $this->service->is_logged_in(),
        ];

        $viewData = array_merge($globalData, $data);

        extract($viewData);
        require_once __DIR__ . "/../Views/partials/header.php";
        require __DIR__ . "/../Views/{$view}.php";
        require_once __DIR__ . "/../Views/partials/footer.php";
    }

    protected function json($data, $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

}