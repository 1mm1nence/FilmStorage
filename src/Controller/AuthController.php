<?php
namespace App\Controller;

use App\Service\AuthService;
use App\Core\View;

class AuthController {
    public function login(): void {
        $authService = new AuthService();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($authService->login($username, $password)) {
                View::render('login_success.php', [
                    'username' => $_SESSION['username'] ?? ''
                ]);
            } else {
                View::render('login_form.php', [
                    'error' => 'Invalid credentials',
                ]);
            }
        } else {
            View::render('login_form.php');
        }
    }

    public function register(): void {
        $authService = new AuthService();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($username === '' || $password === '') {
                View::render('register_form.php', [
                    'error' => 'Username and password are required.',
                ]);
                return;
            }

            $success = $authService->register($username, $password);
            if ($success) {
                View::render('register_success.php', [
                    'username' => $_SESSION['username'] ?? '',
                ]);
            } else {
                View::render('register_form.php', [
                    'error' => 'Username already taken.',
                ]);
            }
        } else {
            View::render('register_form.php');
        }
    }
}
