<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use PDO;

class AuthService {
    private UserRepository $userRepo;
    private PDO $pdo;
    private ?User $currentUser = null;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->userRepo = new UserRepository($pdo);
    }

    public function getCurrentUser(): ?User
    {
        if ($this->currentUser !== null) {
            return $this->currentUser;
        }

        if (empty($_SESSION['user_id'])) {
            return null;
        }

        $this->currentUser = $this->userRepo->findById($_SESSION['user_id']);

        return $this->currentUser;
    }

    public function login(string $username, string $password): bool {
        $user = $this->userRepo->findByUsername($username);
        if (!$user) return false;

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            return true;
        }

        return false;
    }

    public function register(string $username, string $password): bool {
        if ($this->userRepo->findByUsername($username)) {
            return false; // Username already taken
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $userId = $this->userRepo->create($username, $hashedPassword);

        if ($userId) {
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $username;
            return true;
        }

        return false;
    }

    public function isLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }

    public function getUsername(): ?string {
        return $_SESSION['username'] ?? null;
    }
}