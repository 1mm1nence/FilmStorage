<?php

namespace App\Repository;

use App\Entity\Actor;
use App\Entity\Film;
use PDO;

class ActorRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findManyByFilmId(int $filmId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT a.id, a.name, a.surname
            FROM actors a
            INNER JOIN film_actor fa ON a.id = fa.actor_id
            WHERE fa.film_id = :film_id
        ");
        $stmt->bindValue(':film_id', $filmId, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $actors = [];

        foreach ($rows as $row) {
            $actors[] = new Actor(
                $row['id'],
                $row['name'],
                $row['surname']
            );
        }

        return $actors;
    }

    public function findIdByNameSurname(string $name, string $surname): ?int
    {
        $stmt = $this->pdo->prepare("
            SELECT id FROM actors WHERE name = :name AND surname = :surname
        ");
        $stmt->execute([':name' => $name, ':surname' => $surname]);
        $id = $stmt->fetchColumn();
        return $id ? (int)$id : null;
    }

    public function create(string $name, string $surname): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO actors (name, surname) VALUES (:name, :surname)
        ");
        $stmt->execute([':name' => $name, ':surname' => $surname]);
        return (int)$this->pdo->lastInsertId();
    }

    public function addActorToFilm(Actor $actor, Film $film): void
    {
        $stmt = $this->pdo->prepare("
            INSERT IGNORE INTO film_actor (film_id, actor_id)
            VALUES (:film_id, :actor_id)
        ");
        $stmt->execute([':film_id' => $film->getId(), ':actor_id' => $actor->getId()]);
    }

    public function removeActorFromFilm(int $actorId, int $filmId): void
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM film_actor WHERE film_id = :film_id AND actor_id = :actor_id
        ");
        $stmt->execute([':film_id' => $filmId, ':actor_id' => $actorId]);
    }

    public function isActorAddedToFilm(Actor $actor, Film $film): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM film_actor
            WHERE film_id = :film_id AND actor_id = :actor_id
            LIMIT 1
        ");
        $stmt->execute([':film_id' => $film->getId(), ':actor_id' => $actor->getId()]);
        $rows = $stmt->fetchAll();

        return count($rows) > 0;
    }

}