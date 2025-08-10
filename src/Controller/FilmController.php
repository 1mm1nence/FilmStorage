<?php
namespace App\Controller;

use App\Core\View;
use App\Repository\FilmRepository;

class FilmController {
    public function index(): void {
        $filmRepo = new FilmRepository();
        $films = $filmRepo->getAll();

        View::render('film_list.php', [
            'films' => $films
        ]);
    }

    public function show(int $id): void {
        $filmRepo = new FilmRepository();
        $film = $filmRepo->getById($id);

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
