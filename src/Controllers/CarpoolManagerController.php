<?php

namespace Ecoride\Ecoride\Controllers;

use Ecoride\Ecoride\Core\Controller;
use Ecoride\Ecoride\Models\CarpoolModel;

class CarpoolManagerController extends Controller
{

    private CarpoolModel $carpoolModel;

    public function __construct()
    {
        parent::__construct();
        $this->carpoolModel = new CarpoolModel();
    }

    public function my_carpools(): void
    {
        $this->auth->require_auth();
        $userId = $this->auth->get_connected_user_id();
        $user = $this->auth->get_connected_user();
        $driverCarpools = $this->carpoolModel->get_driver_carpools($userId);
        $passengerCarpools = $this->carpoolModel->get_passenger_carpools($userId);

        $data = [
            'driver_carpools' => $driverCarpools,
            'passenger_carpools' => $passengerCarpools,
            'title' => "Covoiturage de {$user['pseudo']}"
        ];

        $this->renderView('carpool/my-carpools', $data);
    }

}