<?php

namespace App\Core;

use App\Service\AuthService;
use PDO;

class Router {
    private array $routes = [];
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;

        $this->registerRoutes();
    }

    private function registerRoutes(): void {
        $this->routes = [
            'GET' => [
                '/' => [\App\Controller\FilmController::class, 'index'],
                '/login' => [\App\Controller\AuthController::class, 'login'],
                '/register' => [\App\Controller\AuthController::class, 'register'],
                '/films' => [\App\Controller\FilmController::class, 'index'],
            ],
            'POST' => [
                '/login' => [\App\Controller\AuthController::class, 'login'],
                '/logout' => [\App\Controller\AuthController::class, 'logout'],
                '/register' => [\App\Controller\AuthController::class, 'register'],
            ]
        ];
    }

    public function handle(string $uri): void {
        $path = parse_url($uri, PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        $handler = $this->routes[$method][$path] ?? null;

        if ($handler) {
            [$controllerClass, $action] = $handler;
            $controller = new $controllerClass($this->pdo);
            $controller->$action();
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
    }
}