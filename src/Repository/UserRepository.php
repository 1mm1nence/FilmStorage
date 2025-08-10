<?php

namespace App\Repository;

use PDO;

class UserRepository {
    private PDO $pdo;

    public function __construct() {
        $config = require __DIR__ . '/../../config/config.php';
        $this->pdo = new PDO($config['dsn'], $config['user'], $config['pass']);
    }

    public function findByUsername(string $username): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function create(string $username, string $hashedPassword): ?int {
        $stmt = $this->pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");

        try {
            $stmt->execute([$username, $hashedPassword]);
            return (int)$this->pdo->lastInsertId();
        } catch (\PDOException $e) {
            return null; // duplicate username, etc.
        }
    }

}