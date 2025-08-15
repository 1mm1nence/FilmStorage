<?php
namespace App\Controller;

use App\Core\Router;
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
            $username = $_POST['username'];
            $password = $_POST['password'];

            if (empty($username) || empty($password)) {
                Router::redirectTo('/login', 'Pls, enter both username and password to log in', 'error');
            }

            if ($this->authService->login($username, $password)) {
                Router::redirectTo('/', 'Welcome, ' . $username . '!', 'success');
            } else {
                Router::redirectTo('/login', 'Invalid credentials', 'error');
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

        Router::redirectTo('/login');
    }

    public function register(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($username === '' || $password === '') {
                Router::redirectTo('/register', 'Username and password are required', 'error');
            }

            $success = $this->authService->register($username, $password);
            if ($success) {
                Router::redirectTo('/', 'Welcome, ' . $username .'! You are now registered and logged in', 'success');
            } else {
                Router::redirectTo('/register', 'Username already taken. Pls, try other.', 'error');
            }
        } else {
            View::render('auth/register_form.php');
        }
    }
}
