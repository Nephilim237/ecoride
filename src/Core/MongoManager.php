<?php

namespace Ecoride\Ecoride\Core;

use MongoDB\Client;
use MongoDB\Collection;

class MongoManager
{
    private static ?MongoManager $instance = null;
    private Client $client;
    private \MongoDB\Database $database;

    private function __construct() {
        $this->client = new Client(MONGO_DB_URI);
        $this->database = $this->client->selectDatabase(MONGO_DB_NAME);
    }

    public static function getInstance(): ?MongoManager
    {
        if(self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getCollection($name): Collection
    {
        return $this->database->selectCollection($name);
    }

    public function getConnection(): Client
    {
        return $this->client;
    }
}