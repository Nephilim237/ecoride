<?php

namespace Ecoride\Ecoride\Models;

use Ecoride\Ecoride\Core\Database;

class UserModel
{

    protected string $table = 'user';
    private \PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
    }

    public function create(array $data): bool
    {
        $columns = implode(', ', array_keys($data)); // pseudo, email, password, credits
        $placeholders = ':' . implode(', :', array_keys($data)); // :pseudo, :email, :password, :credits
        $stmt = $this->connection->prepare("INSERT INTO user ($columns) VALUES ($placeholders)");
        return $stmt->execute($data);
    }

    public function email_exist(string $email, ?int $excludeUserId = null): bool
    {
        $sql = "SELECT COUNT(*) from user WHERE email = ?";
        $params = [$email];

        if ($excludeUserId) {
            $sql .= " AND user_id != ?";
            $params[] = $excludeUserId;
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchColumn() > 0;
    }

    public function pseudo_exist(string $pseudo, ?int $excludeUserId = null): bool
    {
        $sql = "SELECT COUNT(*) from user WHERE pseudo = ?";
        $params = [$pseudo];

        if ($excludeUserId) {
            $sql .= " AND user_id != ?";
            $params[] = $excludeUserId;
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchColumn() > 0;
    }

    public function find_by_username_or_email(string $identifier): mixed
    {
        $stmt = $this->connection->prepare("
            SELECT * FROM user WHERE pseudo = ? OR  email = ?
        ");
        $stmt->execute([$identifier, $identifier]);
        return $stmt->fetch();
    }

    public function find_by_username(string $username): mixed
    {
        $stmt = $this->connection->prepare("
            SELECT * FROM user WHERE pseudo = ?
        ");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    public function find_by_id($userId): mixed
    {
        $stmt = $this->connection->prepare("
            SELECT * FROM user WHERE user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function find_by_email(string $email): mixed
    {
        $stmt = $this->connection->prepare("
            SELECT * FROM user WHERE email = ?
        ");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function find_by_remeber_token(string $token): mixed
    {
        $stmt = $this->connection->prepare("SELECT * FROM user WHERE remember_me = ?");
        $stmt->execute([$token]);
        return $stmt->fetch();
    }

    public function get_user_roles($userId): array
    {
        $stmt = $this->connection->prepare("
            SELECT r.role_id, r.libelle FROM role r -- 1: Passager, 2: Chauffeur
            JOIN role_user ru ON ru.role_id = r.role_id
            WHERE ru.user_id = ?
        ");
        $stmt->execute([$userId]);

        $roles = [];
        while ($row = $stmt->fetch()) {
            $roles[] = $row->libelle;
            // $row = {'1'=> 'Passager'}
            // $row = {'2'=> 'Chauffeur'}
            // $roles = ['passager', 'Chauffeur']
        }

        return $roles;
    }

    public function is_driver($userId): bool
    {
        $stmt = $this->connection->prepare("
            SELECT COUNT(*)
            FROM role_user ru 
            JOIN role r on r.role_id = ru.role_id
            WHERE r.libelle = 'chauffeur' AND ru.user_id = ?
        ");

        $stmt->execute([$userId]);
        return $stmt->fetchColumn() > 0;
    }

    public function is_passenger($userId): bool
    {
        $stmt = $this->connection->prepare("
            SELECT COUNT(*)
            FROM role_user ru 
            JOIN role r on r.role_id = ru.role_id
            WHERE r.libelle = 'passager' AND ru.user_id = ?
        ");

        $stmt->execute([$userId]);
        return $stmt->fetchColumn() > 0;
    }


}