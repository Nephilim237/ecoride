<?php

namespace Ecoride\Ecoride\Controllers;

use Ecoride\Ecoride\Core\Controller;

class UserController extends Controller
{

    public function profile() {
        $this->renderView('profile/profile', [
            'title' => "Coding City | " . APP_NAME
        ]);
    }


}