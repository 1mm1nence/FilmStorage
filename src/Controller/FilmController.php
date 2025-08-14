<?php
namespace App\Controller;

use App\Core\View;
use App\Entity\Actor;
use App\Entity\Film;
use App\Entity\User;
use App\Enum\FilmFormat;
use App\Repository\ActorRepository;
use App\Repository\FilmRepository;
use App\Service\AuthService;
use App\Service\ImportService;
use PDO;

class FilmController {
    private PDO $pdo;
    private FilmRepository $filmRepository;
    private ActorRepository $actorRepository;
    private AuthService $authService;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->authService = new AuthService($pdo);
        $this->filmRepository = new FilmRepository($pdo);
        $this->actorRepository = new ActorRepository($pdo);
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

    public function searchFilm(): void
    {
        /** @var User $currentUser */
        $currentUser = $this->authService->getCurrentUser();

        if (!$currentUser) {
            header('Location: /login');
            exit;
        }

        $input_query = $_GET['q'];

        /** @var Film[] $films */
        $films = $this->filmRepository->findByNameOrActor($input_query, $currentUser);

        View::render('film/film_list.php', ['films' => $films]);
    }

    public function show(): void
    {
        $filmId = $_GET['id'];

        $film = $this->filmRepository->findById($filmId);

        if (!$film) {
            http_response_code(404);
            View::render('404.php', ['message' => 'Film not found']);
            return;
        }

        $actors = $this->actorRepository->findManyByFilmId($film->getId());
        $film->setActors($actors);

        View::render('film/film_detail.php', [
            'film' => $film
        ]);
    }

    public function createForm(): void
    {
        View::render('film/film_create.php', [
            'formats' => FilmFormat::cases()
        ]);
    }

    public function create(): void
    {
        $user = $this->authService->getCurrentUser();

        $film = new Film(
            null,
            $_POST['name'],
            (int)$_POST['year'],
            FilmFormat::from($_POST['format']),
            $user
        );

        $filmId = $this->filmRepository->create($film);

        header("Location: /film/edit?id={$filmId}");
        exit;
    }

    public function edit(): void
    {
        $film = $this->filmRepository->findById((int)$_GET['id']);
        $actors = $this->actorRepository->findManyByFilmId($film->getId());
        $film->setActors($actors);
        View::render('film/film_edit.php', [
            'film' => $film
        ]);
    }

    public function addActor(): void
    {
        $film_id = (int)$_POST['film_id'];
        $actor = new Actor(
            null,
            trim($_POST['name']),
            trim($_POST['surname'])
        );

        $film = $this->filmRepository->findById($film_id);

        $this->actorRepository->addActorToFilm($actor, $film);

        header("Location: /film/edit?id=" . (int)$_POST['film_id']);
        exit;
    }

    public function removeActor(): void
    {
        $this->actorRepository->removeActorFromFilm((int)$_POST['actor_id'], (int)$_POST['film_id']);
        header("Location: /film/edit?id=" . (int)$_POST['film_id']);
        exit;
    }

    public function deleteFilm(): void
    {
        $filmId = (int)$_GET['id'];

        $currentUser = $this->authService->getCurrentUser();
        if (!$currentUser) {
            header('Location: /login');
            exit;

            http_response_code(404);
            View::render('404.php', ['message' => 'Film not found']);
            return;
        }

        $film = $this->filmRepository->findById($filmId);
        if ($this->filmRepository->isOwnedBy($film->getId(), $currentUser->getId())) {
            $this->filmRepository->delete((int)$_GET['id']);
            header("Location: /films");
            exit;
        }

        View::render('404.php', ['message' => 'Film not found']);
    }

    public function importFilms(): void
    {
        $currentUser = $this->authService->getCurrentUser();
        if (!$currentUser) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['films_file'])) {
            $fileTmpPath = $_FILES['films_file']['tmp_name'];
            $fileName = $_FILES['films_file']['name'];
            $fileSize = $_FILES['films_file']['size'];
            $fileError = $_FILES['films_file']['error'];

            if ($fileError === UPLOAD_ERR_OK) {
                $importService = new ImportService($this->pdo);
                $importService->importFromTxt($fileTmpPath, $currentUser);

                // Redirect back to film list
                header("Location: /");
                exit;
            } else {
                // Handle error
                echo "Error uploading file. Pls check if its valid txt file.";
            }
        } else {
            echo "No file uploaded.";
        }
    }


}
