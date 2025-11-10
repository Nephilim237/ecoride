<?php

namespace Ecoride\Ecoride\Controllers;

use Ecoride\Ecoride\Core\Controller;

class UserController extends Controller
{

    public function profile(): void {
        $this->renderView('profile/profile', [
            'title' => "Profil de Coding237 | EcoRide"
        ]);
    }

}