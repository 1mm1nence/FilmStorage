<?php

namespace App\Core;

use App\Controller\AuthController;
use App\Controller\FilmController;
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
                '/' => [FilmController::class, 'index'],
                '/login' => [AuthController::class, 'login'],
                '/register' => [AuthController::class, 'register'],

                '/films' => [FilmController::class, 'index'],
                '/film/create' => [FilmController::class, 'createForm'],
                '/film/detail' => [FilmController::class, 'show'],
                '/film/edit' => [FilmController::class, 'edit'],
                '/film/delete' => [FilmController::class, 'deleteFilm'],

                '/search' => [FilmController::class, 'searchFilm'],
            ],

            'POST' => [
                '/login' => [AuthController::class, 'login'],
                '/logout' => [AuthController::class, 'logout'],
                '/register' => [AuthController::class, 'register'],

                '/film/create' => [FilmController::class, 'create'],
                '/film/edit' => [FilmController::class, 'edit'],
                '/film/add-actor' => [FilmController::class, 'addActor'],
                '/film/remove-actor' => [FilmController::class, 'removeActor'],

                '/import' => [FilmController::class, 'importFilms'],
            ],
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

    public static function redirectTo(string $url, ?string $message = null, string $type = 'info'): void
    {
        if ($message) {
            $_SESSION['flash_message'] = [
                'text' => $message,
                'type' => $type
            ];
        }

        header('Location: ' . $url);
        exit;
    }
}