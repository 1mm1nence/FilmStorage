<?php

namespace App\Core;

class Router {
    private array $routes = [];

    public function __construct() {
        $this->registerRoutes();
    }

    private function registerRoutes(): void {
        $this->routes = [
            'GET' => [
                '/login' => [\App\Controller\AuthController::class, 'login'],
                '/register' => [\App\Controller\AuthController::class, 'register'],
                '/films' => [\App\Controller\FilmController::class, 'index'],
            ],
            'POST' => [
                '/login' => [\App\Controller\AuthController::class, 'login'],
                '/register' => [\App\Controller\AuthController::class, 'register'],
            ]
        ];
    }

    public function handle(string $uri): void {
        $path = parse_url($uri, PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        $handler = $this->routes[$method][$path] ?? null;

        if ($handler) {
            [$controller, $action] = $handler;
            (new $controller())->$action();
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
    }
}