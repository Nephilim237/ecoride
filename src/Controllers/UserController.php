<?php

namespace Ecoride\Ecoride\Controllers;

use Ecoride\Ecoride\Core\Controller;

class UserController extends Controller
{

    public function __construct()
    {
        parent::__construct();

    }

    public function profile(): void
    {
        $this->service->require_auth();
        $this->renderView('profile/profile', [
            'title' => "Profil de Coding237 | EcoRide",
        ]);
    }

}