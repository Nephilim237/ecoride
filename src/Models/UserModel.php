<?php

namespace Ecoride\Ecoride\Models;

use Ecoride\Ecoride\Core\Model;
use Ecoride\Ecoride\Core\RoleManager;

class UserModel extends Model
{
    protected string $table = "user";

    public function __construct() {
        parent::__construct();
    }

    /**
     * Trouver un utilisateur grace a son email
     * @param string $email
     * @return mixed
     */
    public function find_by_email(string $email): mixed
    {
        $stmt = $this->connection->prepare("SELECT * FROM $this->table WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Trouver un utilisateur grace a son email
     * @param string $identifier
     * @return mixed
     */
    public function find_by_username_or_email(string $identifier): mixed
    {
        $stmt = $this->connection->prepare("SELECT * FROM $this->table WHERE pseudo = ? OR email = ?");
        $stmt->execute([$identifier, $identifier]);
        return $stmt->fetch();
    }

    public function find_by_pseudo(string $pseudo) {
        $stmt = $this->connection->prepare("SELECT * FROM $this->table WHERE pseudo = ?");
        $stmt->execute([$pseudo]);
        return $stmt->fetch();

    }

    /**
     * Verifie si un email existe en base de donnee
     * @param string $email
     * @param int|null $excludeUserId
     * @return bool
     */
    public function email_exists(string $email, ?int $excludeUserId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM $this->table WHERE email = ?";
        $params = [$email];

        if ($excludeUserId) {
            $sql .= " AND user_id != ?";
            $params[] = $excludeUserId;
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    public function pseudo_exists(string $pseudo, ?int $excludeUserId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM $this->table WHERE pseudo = ?";
        $params = [$pseudo];

        if ($excludeUserId) {
            $sql .= " AND user_id != ?";
            $params[] = $excludeUserId;
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;

    }

    public function update_remember_token(int $userId, string $token): bool
    {
        $stmt = $this->connection->prepare("UPDATE $this->table SET remember_me = ? WHERE id = ?");
        return $stmt->execute([$token, $userId]);
    }

    public function find_by_remember_token(string $token) {
        $stmt = $this->connection->prepare("SELECT * FROM $this->table WHERE remember_me = ?");
        $stmt->execute([$token]);
        return $stmt->fetch();
    }

//    public function create_user($userData): bool
//    {
//        $sql = "INSERT INTO $this->table (
//                  nom, prenom, email, password, telephone, adresse, pseudo, date_naissance, photo
//                  ) VALUES (:nom, :prenom, :email, :password, :telephone, :adresse, :pseudo, :date_naissance, :photo)";
//
//        $stmt = $this->connection->prepare($sql);
//        return $stmt->execute($userData);
//    }

    public function update_profile (int $userId, $userData): bool
    {
        $allowedFields = ['nom', 'prenom', 'email', 'password', 'telephone', 'adresse', 'pseudo', 'date_naissance', 'photo'];
        $updates = [];
        $params = [];

        foreach ($userData as $field => $value) {
            if (in_array($field, $allowedFields)) {
                $updates[] = "$field = $field";
                $params[$field] = $value;
            }
        }

        if (empty($updates)) {
            return false;
        }

        $sql = "UPDATE $this->table SET " . implode(', ', $updates) . " WHERE user_id = :user_id";
        $params['user_id'] = $userId;

        $stmt = $this->connection->prepare($sql);
        return $stmt->execute($params);
    }

    public function get_user_roles(int $userId): array {
        $stmt = $this->connection->prepare(
            "SELECT r.role_id, r.libelle 
                    FROM role r
                    JOIN role_user ru ON ru.role_id = r.role_id
                    WHERE ru.user_id = ?"
        );
        $stmt->execute([$userId]);

        $roles = [];
        while($row = $stmt->fetch()) {
            $roles[] = $row->libelle;
        }

        return $roles;
    }

    public function is_driver (int $userId): bool
    {
        $stmt = $this->connection->prepare(
            "SELECT COUNT(*) 
                    FROM role_user ru
                    JOIN role r on ru.role_id = r.role_id
                    WHERE r.libelle = 'chauffeur' AND ru.user_id = ?"
        );

        $stmt->execute([$userId]);
        return $stmt->fetchColumn() > 0;
    }

    public function get_role_mask(int $userId): int
    {
        $stmt = $this->connection->prepare("SELECT role_admin FROM user WHERE user_id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();

        return $result ? (int) $result->role_admin : RoleManager::VISITEUR;
    }

    public function set_role_mask(int $userId, int $mask): bool
    {
        if (!RoleManager::is_valid_mask($mask)) {
            throw new \InvalidArgumentException("Masque de role invalide: $mask");

        }
        $stmt = $this->connection->prepare("UPDATE user SET role_admin = ? WHERE user_id = ?");
        return $stmt->execute([$mask, $userId]);
    }

    public function setRole(int $userId, string $role): bool
    {
        $mask = RoleManager::get_full_mask($role);

        return $this->set_role_mask($userId, $mask);
    }

    public function hasRole(int $userId, string $role): bool
    {
        $userMask = $this->get_role_mask($userId);

        return RoleManager::has_role($userMask, $role);
    }

    public function hasMinLevel(int $userId,int $minLevel): bool
    {
        $userMask = $this->get_role_mask($userId);
        return RoleManager::has_min_level($userMask, $minLevel);
    }

    public function promote (int $userId): bool
    {
        $currentMask = $this->get_role_mask($userId);
        $nextMask = RoleManager::get_next_mask($currentMask);

        if ($nextMask === null) {
            return false; // Niveau Max
        }

        return $this->set_role_mask($userId, $nextMask);
    }

    public function demote (int $userId): bool
    {
        $currentMask = $this->get_role_mask($userId);
        $previousMask = RoleManager::get_previous_mask($currentMask);

        if ($previousMask === null) {
            return false; // Niveau Plus bas
        }

        return $this->set_role_mask($userId, $previousMask);
    }

    public function getRoleInfo(int $userId): array
    {
        $mask = $this->get_role_mask($userId);

        return [
            'mask' => $mask,
            'name' => RoleManager::get_admin_role_name($mask),
            'level' => RoleManager::get_level_from_role($mask),
            'all_roles' => RoleManager::get_admin_roles($mask)
        ];
    }
}