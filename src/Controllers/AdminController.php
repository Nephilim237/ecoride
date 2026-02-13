<?php

namespace Ecoride\Ecoride\Controllers;

use Ecoride\Ecoride\Core\Controller;
use Ecoride\Ecoride\Models\CarpoolModel;

class AdminController extends Controller
{
    private CarpoolModel $carpooolModel;

    public function __construct()
    {
        parent::__construct();
        $this->carpooolModel = new CarpoolModel();
    }

    public function dashboard(): void
    {
        $user = $this->auth->get_connected_user();
        // Penser a implementer une methode qui determine si on est au moins employe
        $nbCarpools = $this->carpooolModel->count_carpools();
        $nbCarpoolsPrevus = $this->carpooolModel->count_carpools('prevu');
        $nbCanceledCarpools = $this->carpooolModel->count_carpools('annule');
        $nbReservations = $this->carpooolModel->count_reservations();

        dump($nbCarpools, $nbCarpoolsPrevus);

        $data = [
            'title' => "Tableau de Bord",
            'nb_carpools' => $nbCarpools,
            'nb_reservations' => $nbReservations,
            'nb_pendig_carpools' => $nbCarpoolsPrevus,
            'nb_canceled_carpools' => $nbCanceledCarpools
        ];

        $this->renderView('admin/dashboard', $data);
    }

}