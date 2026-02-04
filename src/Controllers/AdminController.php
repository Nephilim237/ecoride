<?php

namespace Ecoride\Ecoride\Controllers;

use Ecoride\Ecoride\Core\Controller;

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function dashboard(): void
    {
        $user = $this->auth->get_connected_user();
        // Penser a implementer une methode qui determine si on est au moins employe

        $data = [
            'title' => "Tableau de Bord",
        ];

        $this->renderView('admin/dashboard', $data);
    }

}