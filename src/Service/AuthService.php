<?php

namespace App\Service;

use App\Repository\UserRepository;

class AuthService {
    private UserRepository $userRepo;

    public function __construct() {
        $this->userRepo = new UserRepository();
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