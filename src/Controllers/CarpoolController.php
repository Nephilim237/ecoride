<?php

namespace Ecoride\Ecoride\Controllers;

use Ecoride\Ecoride\Core\Controller;
use Ecoride\Ecoride\Models\CarpoolModel;
use Ecoride\Ecoride\Services\AddressAutocompleteService;
use Ecoride\Ecoride\Services\CarpoolService;

class CarpoolController extends Controller
{
    private CarpoolModel $carpoolModel;
    private AddressAutocompleteService $addressService;
    private CarpoolService $carpoolService;

    public function __construct()
    {
        parent::__construct();
        $this->carpoolModel = new CarpoolModel();
        $this->addressService = new AddressAutocompleteService();
        $this->carpoolService = new CarpoolService();
    }

    public function index(): void
    {
        $data = [
            'title' => "Trouver un trajet | " . APP_NAME,
            'carpools' => [],
            'searchParams' => []
        ];
        $this->renderView('carpool/index', $data);
    }

    public function search(): void
    {
        $searchParams = [
            // Filtre US3
            'lieu_depart' => sanitize($_GET['lieu_depart'] ?? ''),
            'lieu_arrivee' => sanitize($_GET['lieu_arrivee'] ?? ''),
            'date_depart' => sanitize($_GET['date_depart'] ?? ''),
            'nb_passagers' => sanitize($_GET['nb_passagers'] ?? ''),

            // Nouveaux filtres US4
            'is_ecologic' => $_GET['is_ecologic'] ?? '',
            'prix_max' => $_GET['prix_max'] ?? '',
            'duree_max' => $_GET['duree_max'] ?? '',
            'note_min' => $_GET['note_min'] ?? '',
        ];

        $carpools = [];
        $nextAvailableDate = null;

        $hasCriteria = !empty($searchParams['lieu_depart']) || !empty($searchParams['lieu_arrivee']) ||
            !empty($searchParams['date_depart']);

        if ($hasCriteria) {
            $carpools = $this->carpoolModel->get_carpools($searchParams);

            // Si aucun resultat, trouver la procahine date
            if (empty($carpools) && !empty($searchParams['lieu_depart']) && !empty($searchParams['lieu_arrivee']) && !empty
                ($searchParams['date_depart'])) {
                // Mettre la logique de la prochaine date dispo

                $nextAvailableDate = $this->carpoolModel->get_next_available_date($searchParams['lieu_depart'],
                    $searchParams['lieu_arrivee'], $searchParams['date_depart']);
            }
        }

        $data = [
            'carpools' => $carpools,
            'title' => "Resultats pour {$searchParams['lieu_depart']} - {$searchParams['lieu_arrivee']} | " . APP_NAME,
            'searchParams' => $searchParams,
            'nextAvailableDate' => $nextAvailableDate,
            'activeFilters' => $this->get_active_filters($searchParams)
        ];

        $this->renderView('carpool/index', $data);

    }

    private function get_active_filters(array $searchParams): array
    {
        $activeFilters = [];

        if (!empty($searchParams['is_ecologic']) && $searchParams['is_ecologic'] === 'on') {
            $activeFilters[] = ['name' => 'is_ecologic', 'label' => 'Ecologique: Oui'];
        }

        if (!empty($searchParams['prix_max'])) {
            $activeFilters[] = ['name' => 'prix_max', 'label' => "Prix max: {$searchParams['prix_max']} Credits"];
        }

        if (!empty($searchParams['duree_max'])) {
            $activeFilters[] = ['name' => 'duree_max', 'label' => "Duree max: {$searchParams['duree_max']} minutes"];
        }

        if (!empty($searchParams['note_min'])) {
            $activeFilters[] = ['name' => 'note_min', 'label' => "Note min: {$searchParams['note_min']}/5"];
        }

        return $activeFilters;
    }

    public function carpool_details(): void
    {
        $this->auth->require_auth();
        if (!isset($_GET['covoiturage'])) {
            $this->redirect('404');
        }

        $carpoolId = (int)$_GET['covoiturage'];
        $seats = (int)$_GET['nb_passagers'] ?? 1;
        $carpoolDetails = $this->carpoolModel->get_carpool_details($carpoolId);

        if (!$carpoolDetails) {
            $this->session->set_flash('error', "Covoiturage non trouvé.");
            return;
        }

        $userId = $this->auth->get_connected_user_id() ?? null;
        $canUserParticipate = $this->carpoolService->can_user_participate($carpoolId, $userId, $seats);

        if (!$canUserParticipate['can_participate']) {
            //[
            //   'plus de place',
            //    'Credits insuffisants',   => 'plus de place' <br>  'Credits insuffisants' <br> 'Vous etes chauffeur'
            //    'Vous etes chauffeur'
            //];
            $this->session->set_flash('error', implode('<br>', $canUserParticipate['errors']));
        }

        $data = [
            'title' => "Trajet " . $carpoolDetails['depart']['lieu'] . "-" . $carpoolDetails['arrivee']['lieu'],
            'carpool' => $carpoolDetails,
            'user' => $this->userModel->find_by_id($userId),
            'canParticipate' => $canUserParticipate['can_participate'],
            'canUserParticipate' => $canUserParticipate
        ];
        $this->renderView('/carpool/details', $data);
    }

    public function handle_apply() {

    }

    public function apply_success() {
        $this->renderView('carpool/apply-success');
    }

    public function autocomplete(): void
    {
        $query = $_GET['query'] ?? '';

        // Verifier qqu'omn a a faire a une requete AJAX
        if (empty($query) || mb_strlen($query) < 3) {
            $this->json([]);
            return;
        }

        $addresses = $this->addressService->search_address($query);

        $this->json($addresses);
    }

}