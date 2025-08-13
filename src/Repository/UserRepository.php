<?php

namespace App\Repository;

use App\Entity\User;
use PDO;

class UserRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->pdo->prepare('SELECT id, username FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return new User(
            id: $id,
            username: $data['username']
        );
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function create(string $username, string $hashedPassword): ?int
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");

        try {
            $stmt->execute([$username, $hashedPassword]);
            return (int)$this->pdo->lastInsertId();
        } catch (\PDOException $e) {
            return null; // duplicate username, etc.
        }
    }

}