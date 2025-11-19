<?php

namespace Ecoride\Ecoride\Models;

use Ecoride\Ecoride\Core\Model;

class VehicleModel extends Model
{
    protected string $table = 'voiture';
    
    public function get_or_create_brand(string $brandName) {
        // Si la marque existe
        $stmt = $this->connection->prepare("SELECT marque_id FROM marque WHERE libelle = ?");
        if ($brandName === '') return false;

        $stmt->execute([$brandName]);
        $result = $stmt->fetch();

        if ($result) {
            return $result->marque_id;
        }

        // Si $result ne renvoie rien, alor la marque n'esxite pas et doit etre creee
        $stmt = $this->connection->prepare("INSERT INTO marque (libelle) VALUES (?)");
        $stmt->execute([$brandName]);

        return $this->connection->lastInsertId();
    }

    public function add_vehicle(int $userId, array $vehicleData): bool
    {
        $vehicleData['user_id'] = $userId;
        return $this->create($vehicleData);
    }
}