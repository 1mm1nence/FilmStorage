<?php
namespace App\Controller;

use App\Controller\Abstract\AuthenticatedController;
use App\Core\Router;
use App\Core\View;
use App\Entity\Actor;
use App\Entity\Film;
use App\Enum\FilmFormat;
use App\Repository\ActorRepository;
use App\Repository\FilmRepository;
use App\Service\ImportService;
use App\Utils\Validator;
use PDO;

class FilmController extends AuthenticatedController {
    private FilmRepository $filmRepository;
    private ActorRepository $actorRepository;

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);

        $this->filmRepository = new FilmRepository($pdo);
        $this->actorRepository = new ActorRepository($pdo);
    }

    public function index(): void
    {
        /** @var Film[] $films */
        $films = $this->filmRepository->findAllByUser($this->currentUser);

        View::render('film/film_list.php', ['films' => $films]);
    }

    public function searchFilm(): void
    {
        $input_query = $_GET['q'];

        /** @var Film[] $films */
        $films = $this->filmRepository->findByNameOrActor($input_query, $this->currentUser);

        View::render('film/film_list.php', ['films' => $films]);
    }

    public function show(): void
    {
        $filmId = $_GET['id'];

        $film = $this->filmRepository->findById($filmId);

        if (!$film || !$this->filmRepository->isOwnedBy($filmId, $this->currentUser->getId())) {
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
        $filmName = trim($_POST['name']);
        $filmYear = (int)$_POST['year'];
        $filmFormat = FilmFormat::from($_POST['format']);

        if (!Validator::isStringLengthValid($filmName)) {
            Router::redirectTo(
                '/film/create',
                'The film was not created. The name should contain some letters.',
                'error'
            );
        }

        if (!Validator::isStringAllowedCharsOnly($filmName)) {
            Router::redirectTo(
                '/film/create',
                'The film was not created. The name should contain only letters, spaces numbers.',
                'error'
            );
        }

        if (!Validator::isYearValid($filmYear)) {
            Router::redirectTo(
                '/film/create',
                'The film was not created. The year should be more than 1800 or less than current year + 20.',
                'error'
            );
        }

        $film = new Film(
            null,
            $filmName,
            $filmYear,
            $filmFormat,
            $this->currentUser
        );

        $filmId = $this->filmRepository->create($film);

        Router::redirectTo(
            '/film/edit?id=' . $filmId,
            'Film ' . $filmName .' created successfully. Now feel free to add stars to it.',
            'success'
        );
    }

    public function edit(): void
    {
        $filmId = (int)$_GET['id'];

        $film = $this->filmRepository->findById($filmId);

        if(!$this->filmRepository->isOwnedBy($filmId, $this->currentUser->getId())) {
            View::render('404.php', ['message' => 'Film not found']);
        }

        $actors = $this->actorRepository->findManyByFilmId($film->getId());
        $film->setActors($actors);

        View::render('film/film_edit.php', [
            'film' => $film
        ]);
    }

    public function addActor(): void
    {
        $filmId = (int)$_POST['film_id'];
        $actorName = trim($_POST['name']);
        $actorSurname = trim($_POST['surname']);

        $film = $this->filmRepository->findById($filmId);
        if (!$film || !$this->filmRepository->isOwnedBy($filmId, $this->currentUser->getId())) {
            Router::redirectTo('/', 'Looks like your film gone somewhere ;(', 'error');
        }

        if (!Validator::isStringLengthValid($actorName) || !Validator::isStringLengthValid($actorSurname)) {
            Router::redirectTo(
                '/film/edit?id=' . $filmId,
                'The actor was not added. The name and surname should contain some letters and should not be longer than 255 characters.',
                'error'
            );
        }

        if (!Validator::isStringAllowedCharsOnly($actorName) || !Validator::isStringAllowedCharsOnly($actorSurname)) {
            Router::redirectTo(
                '/film/edit?id=' . $filmId,
                'The name and surname should not contain any characters other then letters.',
                'error'
            );
        }

        $actor = new Actor(
            null,
            $actorName,
            $actorSurname
        );

        $actorId = $this->actorRepository->findIdByNameSurname($actorName, $actorSurname);

        if ($actorId) {
            $actor->setId($actorId);
            if ($this->actorRepository->isActorAddedToFilm($actor, $film)) {
                Router::redirectTo(
                    '/film/edit?id=' . $filmId,
                    'Star with the name  ' . $actorName . ' ' . $actorSurname . ' was not added, as they were already listed in the film.',
                    'info'
                );
            }
        } else {
            $actorId = $this->actorRepository->create($actorName, $actorSurname);
            $actor->setId($actorId);
        }

        $this->actorRepository->addActorToFilm($actor, $film);

        Router::redirectTo(
            '/film/edit?id=' . $filmId,
            'Star ' . $actorName . ' ' . $actorSurname . ' added successfully.',
            'success'
        );
    }

    public function removeActor(): void
    {
        $actorId = (int)$_POST['actor_id'];
        $filmId = (int)$_POST['film_id'];

        $film = $this->filmRepository->findById($filmId);
        if (!$film || !$this->filmRepository->isOwnedBy($filmId, $this->currentUser->getId())) {
            Router::redirectTo('/', 'Looks like your film gone somewhere ;(', 'error');
        }

        $this->actorRepository->removeActorFromFilm($actorId, $filmId);

        Router::redirectTo(
            '/film/edit?id=' . $filmId,
            'Star removed successfully.',
            'info'
        );
    }

    public function deleteFilm(): void
    {
        $filmId = (int)$_GET['id'];

        $film = $this->filmRepository->findById($filmId);
        if (!$this->filmRepository->isOwnedBy($film->getId(), $this->currentUser->getId())) {
            View::render('404.php', ['message' => 'Film not found']);
        }

        $filmName = $film->getName();
        $this->filmRepository->delete($filmId);

        Router::redirectTo('/', 'Film ' . $filmName . ' deleted from the system.', 'info');
    }

    public function importFilms(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['films_file'])) {
            $fileTmpPath = $_FILES['films_file']['tmp_name'];
            $fileError = $_FILES['films_file']['error'];

            $importService = new ImportService($this->pdo);

            if ($fileError === UPLOAD_ERR_OK && $importService->isTxtFile($_FILES['films_file'])) {
                try {
                    $importService->importFromTxt($fileTmpPath, $this->currentUser);
                } catch (\RuntimeException $exception) {
                    Router::redirectTo('/', 'Error uploading file. ' . $exception->getMessage(), 'error');
                } catch (\InvalidArgumentException $exception) {
                    Router::redirectTo('/', 'Pls check formats of the films. Currently, we accept VHS, DVD and Blu-ray.', 'error');
                } catch (\Throwable $exception) {
                    Router::redirectTo('/', 'Error uploading file. Pls check if it formated correctly.', 'error');
                }

                Router::redirectTo('/', 'Films from file imported successfully.', 'success');
            } else {
                Router::redirectTo('/', 'Error uploading file. Pls check if its valid txt file.', 'error');
            }
        } else {
            Router::redirectTo('/', 'No file uploaded.', 'error');
        }
    }
}
