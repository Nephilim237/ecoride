<?php

namespace Ecoride\Ecoride\database;

use Ecoride\Ecoride\Core\Database;
use Ecoride\Ecoride\Core\MongoManager;
use Faker\Factory;

class Seeder
{

    private \PDO $db;
    private ?MongoManager $mongo;
    private \Faker\Generator $faker;
    private array $marqueIds;
    private array $userIds;
    private array $voitureIds;

    private array $covoiturageIds;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->mongo = MongoManager::getInstance();
        $this->faker = Factory::create("fr_FR");
    }

    /**
     * Cette fonction execute l'ensemble de mes fonctions utilitaires
     * @return void
     */
    public function run(): void
    {
        echo "📌 Debut de la generation des donnees tests... \n\n";

        $this->clearExistingData();
        $this->seedMarques();
        $this->seedUsers();
        $this->seedVoitures();
        $this->seedUserRoles();
        $this->seedCovoiturages();
        $this->seedReservations();
        $this->seedAvis();
        $this->seedPreferences();
//        $this->seedMongoData();

        echo "✅ generation des tests terminee avec succes ! \n";
    }

    private function clearExistingData(): void
    {
        echo "🧹 Nettoyage des donnees existantes...\n";

        // Desactiver les contraintes des cles etrangeres
        $this->db->exec("SET FOREIGN_KEY_CHECKS = 0");

        $tables = [
            'preference', 'avis', 'reservation', 'covoiturage', 'voiture',
            'role_user', 'user', 'marque', 'role_admin'
        ];

        foreach ($tables as $table) {
            $this->db->exec("TRUNCATE TABLE $table");
        }

        // Reactiver les contraintes des cles etrangeres
        $this->db->exec("SET FOREIGN_KEY_CHECKS = 1");

        //Nettoyer MongoDB
        $this->mongo->getCollection('trajets_geolocalisation')->deleteMany([]);

        echo "✅ Donnees nettoyees. \n";
    }

    private function seedMarques()
    {
        echo "🚘 Creation des marques de voiture...\n";

        $marques = [
            'Renault', 'Peugeot', 'Citroën', 'Volkswagen', 'Ford', 'BMW',
            'Mercedes', 'Audi', 'Toyota', 'Nissan', 'Hyundai', 'Kia',
            'Fiat', 'Opel', 'Volvo', 'Seat', 'Skoda', 'Mazda', 'Honda', 'Suzuki'
        ];

        foreach ($marques as $marque) {
            $stmt = $this->db->prepare("INSERT INTO marque (libelle) VALUES (:libelle)");
            $stmt->execute(['libelle' => $marque]);
            $this->marqueIds[] = $this->db->lastInsertId();
        }

        echo "✅ " . count($marques) . " marques creees ...\n";
    }

    private function seedVoitures(): void
    {
        echo "🏎️ Creation des voitures...\n";

        $modelesParMarque = [
            'Renault' => ['Clio', 'Mégane', 'Scénic', 'Captur', 'Kadjar', 'Twingo', 'Zoe'],
            'Peugeot' => ['208', '308', '3008', '5008', '2008', '508', 'Partner'],
            'Citroën' => ['C3', 'C4', 'C5', 'Berlingo', 'Cactus', 'DS3', 'Jumpy'],
            'Volkswagen' => ['Golf', 'Polo', 'Passat', 'T-Roc', 'Tiguan', 'Touran', 'Caddy'],
            'Ford' => ['Fiesta', 'Focus', 'Kuga', 'Puma', 'S-Max', 'Mondeo', 'Tourneo'],
            'BMW' => ['Série 1', 'Série 3', 'Série 5', 'X1', 'X3', 'X5', 'Série 7'],
            'Mercedes' => ['Classe A', 'Classe C', 'Classe E', 'GLA', 'GLC', 'GLE', 'Classe S'],
            'Audi' => ['A1', 'A3', 'A4', 'A6', 'Q2', 'Q3', 'Q5'],
            'Toyota' => ['Yaris', 'Corolla', 'RAV4', 'C-HR', 'Prius', 'Auris', 'Aygo'],
            'Nissan' => ['Micra', 'Qashqai', 'Juke', 'X-Trail', 'Leaf', 'Note']
        ];

        // Recuperer toutes les marques pour les faire correspondre a leur modele
        $stmt = $this->db->query("SELECT marque_id, libelle FROM marque");
        $marques = $stmt->fetchAll();
        //[
        //1: 'peugeot
        //2: Citroen
        //$marque->libelle => citroen
        //$marque->marque_id => 1
        //$modelesParMarque[$marque->libelle] => $modelesParMarque['citroen']
        //]

        $countVoiture = 0;
        foreach ($this->userIds as $userId) {
            $marque = $this->faker->randomElement($marques);
            $modeles = $modelesParMarque[$marque->libelle] ?? [$this->faker->word . ' ' . $this->faker->numberBetween(1, 9)];
            $modele = $this->faker->randomElement($modeles);
            if ($this->faker->boolean(60)) {
                $infoVoiture = [
                    'modele' => $modele,
                    'immatriculation' => $this->generateFrenchLicensePlate(), // A creer
                    'couleur' => $this->faker->colorName,
                    'energie' => $this->faker->randomElement(['0', '1']),
                    'nb_places' => $this->faker->numberBetween(3, 30),
                    'date_premiere_immatriculation' => $this->faker
                        ->dateTimeBetween('-7 years', '-1 weeks')
                        ->format('Y-m-d'),
                    'user_id' => $userId,
                    'marque_id' => $marque->marque_id
                ];

                $stmt = $this->db->prepare("INSERT INTO voiture (
                     modele, immatriculation, couleur, energie, nb_places, date_premiere_immatriculation, user_id, marque_id) VALUES (
                    :modele, :immatriculation, :couleur, :energie, :nb_places, :date_premiere_immatriculation, :user_id, :marque_id
                     )");
                $stmt->execute($infoVoiture);
                $this->voitureIds[] = $this->db->lastInsertId();
                $countVoiture++;
            }
        }

        echo "✅ $countVoiture voitures creees \n";
    }

    private function generateFrenchLicensePlate(): string
    {
        $letters = "QWERTYUIOPLKJHGFDSAZXCVBNM";
        $numbers = "1234567890";

        return substr(str_shuffle($letters), 0, 2) . '-' .
            substr(str_shuffle($numbers), 0, 3) . '-' .
            substr(str_shuffle($letters), 0, 2);
    }

    private function seedUsers(): void
    {
        echo "👥 Creation des utilisateurs...\n";

        for ($i = 0; $i < 50; $i++) {
            $prenom = $this->faker->firstName;
            $nom = $this->faker->lastName;
            $userData = [
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $this->faker->email,
                'role_admin' => $this->faker->randomElement([1, 3, 7, 15]), //VISITEUR 1, UTILIASTEUR 2  1 | 2  A | B = A+B 1 => 0001, 2 => 0010
                'password' => password_hash('1234567890', PASSWORD_DEFAULT),
                'telephone' => $this->faker->phoneNumber,
                'adresse' => $this->faker->address,
                'pseudo' => $this->generateUniquePseudo($prenom, $nom), // A creer
                'credits' => 20,
                'date_naissance' => $this->faker->dateTimeBetween('-100 years', '-18 years')->format('Y-m-d'),
                'photo' => $this->faker->optional(0.75)->imageUrl(200, 200, 'people', true, $prenom),
                'date_creation' => $this->faker->dateTimeBetween('-1 years')->format('Y-m-d')
            ];
            $stmt = $this->db->prepare(
                "INSERT INTO user (
                  nom, prenom, email, role_admin, password, telephone, 
                  adresse, pseudo, credits, date_naissance, photo, date_creation) VALUES (
                  :nom, :prenom, :email, :role_admin, :password, :telephone, 
                  :adresse, :pseudo, :credits, :date_naissance, :photo, :date_creation)"
            );
            $stmt->execute($userData);
            $this->userIds[] = $this->db->lastInsertId();
        }

        echo "✅ " . count($this->userIds) . " utilisateurs crees ...\n";
    }

    private function generateUniquePseudo(string $prenom, string $nom): string
    {
        $basePseudo = strtolower($prenom . '.' . $nom);
        $pseudo = $basePseudo;
        $counter = 1;

        // Checker aue le pseudo choisi ne se trouve pas en BD
        while (true) {
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM user WHERE pseudo = :pseudo");
            $stmt->execute(['pseudo' => $pseudo]);
            $result = $stmt->fetch();

            if ($result->count == 0) {
                break;
            }

            $pseudo = $basePseudo . $counter;
            $counter++;
        }

        return $pseudo;
    }

    private function seedUserRoles(): void
    {
        echo "Attribution des roles...\n";

        // Tableau indiquant la repartition des roles (Chauffeur, Passager et Chauffeur-Passager) de nos utilisateurs
        $stats = [
            'role_passager' => 0,
            'role_chauffeur' => 0,
            'role_chauffeur_passager' => 0
        ];

        // Si un utilisateur possede un vehicule => il est chauffeur
        // On veut que 70% de nos chauffeurs soient egalement des passagers
        foreach ($this->userIds as $userId) {
            // Si vehicule => role_chauffeur
            $hasCar = $this->userHasCar($userId);

            if ($hasCar) {
                $this->assignRole($userId, 2); // A creer

                // 70% des Chauffeurs sont aussi des passagers
                if ($this->faker->boolean(70)) {
                    $this->assignRole($userId, 1);
                    $stats['role_chauffeur_passager']++;
                } else {
                    $stats['role_chauffeur']++;
                }
            } else {
                $this->assignRole($userId, 1);
                $stats['role_passager']++;
            }
        }

        // Afficher la repartition Chauffeurs | Passagers | Passagers-Chauffeurs
        $this->displayStatistics($stats); // A creer
    }

    private function userHasCar(int $userId): bool
    {
        $stmt = $this->db->prepare("SELECT 1 FROM voiture WHERE user_id = :user_id LIMIT 1");
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetch() !== false;
    }

    /**
     * Permet d'assigner des roles aux utilisateurs de EcoRide.
     * @param int $userId Represente l'identifiant de l'utilisateur auquel on veut attribuer un role
     * @param int $roleId Represente le role qu'on va associer a l'utilisateur userId
     * @return void Ne renvoie aucune valeur
     */
    private function assignRole(int $userId, int $roleId): void
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO role_user (user_id, role_id) VALUES (?, ?)");
            $stmt->execute([$userId, $roleId]);
        } catch (\PDOException $e) {
            // Ignorer les doublons
            if ($e->getCode() === '23000') {// Violation de contrainte d'unicite
                echo " Role deja assigne: Utilisateur: $userId - Role: $role \n";
                return;
            } else {
                throw $e;
            }
        }

    }

    private function displayStatistics(array $stats): void
    {
        echo "✅ Repartition des roles...\n";
        echo "👤 Passagers: {$stats['role_passager']} \n";
        echo "🚗 Chauffeurs: {$stats['role_chauffeur']} \n";
        echo "🔗 Chauffeurs-Passagers: {$stats['role_chauffeur_passager']} \n";

    }

    //Date 26/10
    private function seedCovoiturages(): void
    {
        echo "🧳 Creation des covoiturage...\n";

        $villesFrance = [
            'Paris', 'Lyon', 'Marseille', 'Toulouse', 'Nice', 'Nantes', 'Strasbourg',
            'Montpellier', 'Bordeaux', 'Lille', 'Rennes', 'Reims', 'Le Havre', 'Saint-Étienne',
            'Toulon', 'Grenoble', 'Dijon', 'Angers', 'Villeurbanne', 'Le Mans'
        ];

        $countCovoiturage = 0;
        foreach ($this->voitureIds as $voitureId) {
            $stmt = $this->db->prepare("SELECT user_id FROM voiture WHERE voiture_id = :voiture_id");
            $stmt->execute(['voiture_id' => $voitureId]);
            $voiture = $stmt->fetch(); //$voiture => [user_id: 25]
            $conducteurId = $voiture->user_id; // ConducteurId = 25;

            $nbCovoiturages = $this->faker->numberBetween(0, 10);
            for ($i = 0; $i < $nbCovoiturages; $i++) {
                $dateDepart = $this->faker
                    ->dateTimeBetween('+1 days', '+1 months'); // Un DateTime est objet
                $heureDepart = $this->faker->time('H:i:s');
                $dureeTrajet = $this->faker->numberBetween(60, 240);
                $lieuDepart = $this->faker->randomElement($villesFrance);
                $lieuArrivee = $this->faker->randomElement(array_diff($villesFrance, [$lieuDepart]));

                $infoCovoiturage = [
                    'date_depart' => $dateDepart->format('Y-m-d'), //Datetime->format('Y-m-d') est une chaine de caractere
                    'heure_depart' => $heureDepart,
                    'lieu_depart' => $lieuDepart,
                    'date_arrivee' => $dateDepart->format('Y-m-d'), //Datetime->format('Y-m-d') est une chaine de caractere
                    'heure_arrivee' => $this->generateArrivalTime($dateDepart, $dureeTrajet), // A creer
                    'lieu_arrivee' => $lieuArrivee,
                    'statut' => $this->faker->randomElement(['prevu', 'annule', 'en cours', 'termine', 'prevu', 'prevu']),
                    'nb_places' => $this->faker->numberBetween(1, 30),
                    'prix_personne' => $this->faker->numberBetween(5, 50),
                    'conducteur_id' => $conducteurId,
                    'voiture_id' => $voitureId,
                    'date_creation' => $this->faker->dateTimeBetween('-1 months', '-1 weeks')->format('Y-m-d')
                ];
                $stmt = $this->db
                    ->prepare(
                        "INSERT INTO covoiturage (
                     date_depart, heure_depart, lieu_depart, date_arrivee, heure_arrivee, 
                     lieu_arrivee, statut, nb_places, prix_personne, conducteur_id, 
                     voiture_id, date_creation) VALUES ( 
                     :date_depart, :heure_depart, :lieu_depart, :date_arrivee, :heure_arrivee, 
                     :lieu_arrivee, :statut, :nb_places, :prix_personne, :conducteur_id, 
                     :voiture_id, :date_creation)");

                $stmt->execute($infoCovoiturage);
                $this->covoiturageIds[] = $this->db->lastInsertId();
                $countCovoiturage++;
            }
        }

        echo "✅ $countCovoiturage covoiturages crees \n";
    }

    private function generateArrivalTime($dateDepart, $dureeTrajet) {
        $arrival = clone $dateDepart;
        $arrival->modify("+{$dureeTrajet} minutes");

        return $arrival->format('H:i:s');
    }

    private function seedReservations()
    {
        echo "📖 Creation des reservations...\n";
        $countReservation = 0;

        foreach ($this->covoiturageIds as $covoiturageId) {
            $stmt = $this->db->prepare("SELECT nb_places, conducteur_id FROM covoiturage WHERE covoiturage_id = :covoiturage_id");
            $stmt->execute(['covoiturage_id' => $covoiturageId]);
            $covoiturage = $stmt->fetch(); // ['nb_places' => 12, 'conducteur_id' => 30]
            $nbPlacesDispo = $covoiturage->nb_places;
            $conducteurId = $covoiturage->conducteur_id;

            // Generer aleatoirement un nombre de reservation en fonction des places dispo
            $nbReservations = $this->faker->numberBetween(1, $nbPlacesDispo);
            $passagersAyantReserve = [];

            for($i=0; $i < $nbReservations; $i++) {
                // Une reservation est faite uniquement aux utilisateurs autres que le chauffeur
                // Ou au utilisateurs n'ayant pas encore de reservation.
                $passagersDispo = array_diff($this->userIds, [$conducteurId], $passagersAyantReserve);
                if (empty($passagersDispo)) break;

                $passagerId = $this->faker->randomElement($passagersDispo);
                $passagersAyantReserve[] = $passagerId;

                $infoResa = [
                    'passager_id' => $passagerId,
                    'covoiturage_id' => $covoiturageId,
                    'statut' => $this->faker->randomElement(['en attente', 'confirme', 'annule', 'confirme', 'confirme']),
                    'nb_place_reservee' => $this->faker->numberBetween(1, mt_rand(1, $nbPlacesDispo)),
                    'date_creation' => $this->faker->dateTimeBetween('-4 days')->format('Y-m-d')
                ];

                try {
                    $stmt = $this->db->prepare(
                        "INSERT INTO reservation (
                     passager_id, covoiturage_id, statut, nb_place_reservee, date_creation)  
                    VALUES (:passager_id, :covoiturage_id, :statut, :nb_place_reservee, :date_creation)");

                    $stmt->execute($infoResa);
                    $countReservation++;
                } catch (\PDOException $e) {
                    // Ignorer les doublons
                    continue;
                }
            }
        }

        echo "✅ $countReservation reservations creees.\n";
    }

    private function seedAvis()
    {
        echo "⭐ Creation des avis...\n";
        // Un avis ne peut etre donne que lorsqu'un covoiturage est termine,
        // donc on a besoin d'une reservation confirme et d'un covoiturage termine
        $stmt = $this->db->query("
            SELECT  r.passager_id, r.covoiturage_id, c.conducteur_id
            FROM reservation r
            JOIN covoiturage c ON r.covoiturage_id = c.covoiturage_id
            WHERE r.statut = 'confirme' AND c.statut = 'termine'
        ");
        $reservations = $stmt->fetchAll();

        $countAvis = 0;
        foreach ($reservations as $reservation) {
            // On suppose que seul 60% de nos utilisateurs vont donner des avis
            if ($this->faker->boolean(60)) {

                $infoAvis = [
                    'commentaire' => $this->faker->optional(0.8)->realText($this->faker->numberBetween(50, 1000)),
                    'note' => $this->faker->numberBetween(1, 5),
                    'statut' => $this->faker->randomElement(['publie', 'modere', 'modere', 'modere']),
                    'passager_id' => $reservation->passager_id,
                    'conducteur_id' => $reservation->conducteur_id,
                    'covoiturage_id'=> $reservation->covoiturage_id,
                    'date_creation' => $this->faker->dateTimeBetween('-12 months')->format('Y-m-d')
                ];

                $stmt = $this->db->prepare(
                    "INSERT INTO avis (commentaire, note, statut, passager_id, conducteur_id, covoiturage_id, date_creation)
                    VALUES (:commentaire, :note, :statut, :passager_id, :conducteur_id, :covoiturage_id, :date_creation)");

                $stmt->execute($infoAvis);
                $countAvis++;
            }
        }

        echo "✅ $countAvis avis crees.\n";
    }

    private function seedPreferences(): void
    {
        echo "⚙️ Creations des preferences utilisateurs...\n";

        $countPreference = 0;
        foreach ($this->userIds as $userId) {
            // Verifier si l'utilisateur est un chauffeur
            $stmt = $this->db->prepare("SELECT role_id FROM role_user WHERE user_id = ? AND role_id = 2");
            $stmt->execute([$userId]);

            $result = $stmt->fetch();

            if ($result) {
                $preferences = [
                    ['propriete' => 'Musique autorisee', 'valeur' => 'non'],
                    ['propriete' => 'Animaux autorises', 'valeur' => 'non'],
                ];

                foreach ($preferences as $preference) {
                    $stmt = $this->db->prepare(
                        "INSERT INTO preference (propriete, valeur, conducteur_id) VALUES (?, ?, ?)"
                    );
                    $stmt->execute([$preference['propriete'], $preference['valeur'], $userId]);
                    $countPreference++;
                }
            }
        }
        echo "✅ $countPreference preferences creees.\n";
    }

//    private function seedMongoData()
//    {
//
//    }

}