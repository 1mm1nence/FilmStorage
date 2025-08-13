<?php

namespace App\Repository;

use App\Entity\Film;
use App\Entity\User;
use App\Enum\FilmFormat;
use PDO;

class FilmRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM films");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAllByUser(User $user): array
    {
        $sql = "
            SELECT 
                f.id,
                f.name,
                f.year,
                f.format
            FROM films f
            WHERE f.user_id = :user_id
            ORDER BY f.name ASC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $user->getId()]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $films = [];
        foreach ($rows as $row) {
            $filmId = $row['id'];

            if (!isset($films[$filmId])) {
                $films[$filmId] = new Film(
                    id: $filmId,
                    name: $row['name'],
                    year: (int) $row['year'],
                    format: FilmFormat::from($row['format']),
                    user: $user
                );
            }
        }

        return array_values($films);
    }

    public function getActorsByFilm(int $filmId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM actors 
            JOIN film_actor ON actors.id = film_actor.actor_id 
            WHERE film_actor.film_id = :filmId
            ");
        $stmt->execute([
            ':filmId' => $filmId,
        ]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }
}