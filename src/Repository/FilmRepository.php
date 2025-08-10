<?php

namespace App\Repository;

use PDO;

class FilmRepository {
    private PDO $pdo;

    public function __construct() {
        $config = require __DIR__ . '/../../config/config.php';
        $this->pdo = new PDO($config['dsn'], $config['user'], $config['pass']);
    }

    public function findAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM films");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}