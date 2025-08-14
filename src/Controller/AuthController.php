<?php
namespace App\Controller;

use App\Service\AuthService;
use App\Core\View;
use PDO;

class AuthController {
    private AuthService $authService;

    public function __construct(PDO $pdo)
    {
        $this->authService = new AuthService($pdo);
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($this->authService->login($username, $password)) {
                header('Location: /');
                exit;
            } else {
                View::render('auth/login_form.php', [
                    'error' => 'Invalid credentials',
                ]);
            }
        } else {
            View::render('auth/login_form.php');
        }
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            http_response_code(403);
            echo "Forbidden: Invalid CSRF token";
            exit;
        }

        session_unset();
        session_destroy();

        header("Location: /login");
        exit;
    }

    public function register(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($username === '' || $password === '') {
                View::render('auth/register_form.php', [
                    'error' => 'Username and password are required.',
                ]);
                return;
            }

            $success = $this->authService->register($username, $password);
            if ($success) {
                View::render('auth/register_success.php', [
                    'username' => $_SESSION['username'] ?? '',
                ]);
            } else {
                View::render('auth/register_form.php', [
                    'error' => 'Username already taken.',
                ]);
            }
        } else {
            View::render('auth/register_form.php');
        }
    }
}
