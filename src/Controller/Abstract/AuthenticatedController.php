<?php

namespace App\Controller\Abstract;

use App\Service\AuthService;
use App\Core\Router;
use App\Entity\User;
use PDO;

abstract class AuthenticatedController
{
    protected PDO $pdo;
    protected AuthService $authService;
    protected ?User $currentUser = null;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->authService = new AuthService($pdo);
        $this->checkAuth();
    }

    protected function checkAuth(): void
    {
        $this->currentUser = $this->authService->getCurrentUser();
        if (!$this->currentUser) {
            Router::redirectTo('/login', 'Session expired. Please login.');
        }
    }
}