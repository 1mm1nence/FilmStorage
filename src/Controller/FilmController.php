<?php
namespace App\Controller;

use App\Core\View;
use App\Entity\Film;
use App\Entity\User;
use App\Repository\FilmRepository;
use App\Service\AuthService;
use PDO;

class FilmController {
    private FilmRepository $filmRepository;
    private AuthService $authService;

    public function __construct(PDO $pdo)
    {
        $this->authService = new AuthService($pdo);
        $this->filmRepository = new FilmRepository($pdo);
    }

    public function index(): void
    {
        /** @var User $currentUser */
        $currentUser = $this->authService->getCurrentUser();

        if (!$currentUser) {
            header('Location: /login');
            exit;
        }

        /** @var Film[] $films */
        $films = $this->filmRepository->findAllByUser($currentUser);

        View::render('film/film_list.php', ['films' => $films]);
    }

    public function show(int $id): void
    {
        $this->filmRepository->getById($id);

        if (!$film) {
            http_response_code(404);
            View::render('404.php', ['message' => 'Film not found']);
            return;
        }

        View::render('film_detail.php', [
            'film' => $film
        ]);
    }
}
