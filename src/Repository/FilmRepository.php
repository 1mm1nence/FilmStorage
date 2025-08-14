<?php

namespace App\Repository;

use App\Entity\Film;
use App\Entity\User;
use App\Enum\FilmFormat;
use App\Service\ImportService;
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

    public function findById(int $id): ?Film
    {
        $sql = "
            SELECT 
                id,
                name,
                year,
                format
            FROM films
            WHERE id = :film_id
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['film_id' => $id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($rows)) {
            return null;
        }
        $row = $rows[0];

        $filmFormat = FilmFormat::tryFrom($row['format']);

        //todo: make new exception for invalid format.
        if (!$filmFormat) {
            throw new \Exception('got invalid format for film format');
        }

        return new Film(
            id: $row['id'],
            name: $row['name'],
            year: $row['year'],
            format: $filmFormat,
            user: null,
            actors: []
        );
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

    public function findByNameOrActor(string $query, User $user): array
    {
        $sql = "
        SELECT DISTINCT 
                f.id,
                f.name,
                f.year,
                f.format
        FROM films f
        LEFT JOIN film_actor fa ON f.id = fa.film_id
        LEFT JOIN actors a ON fa.actor_id = a.id
        WHERE f.user_id = :user_id
          AND (
                f.name LIKE :q
                OR a.name LIKE :q
                OR a.surname LIKE :q
                OR CONCAT(a.name, ' ', a.surname) LIKE :q
              )
        ORDER BY f.name
    ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $user->getId(),
            ':q' => '%' . $query . '%',
        ]);

        $films = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $films[] = new Film(
                id: $row['id'],
                name: $row['name'],
                year: (int) $row['year'],
                format: FilmFormat::from($row['format']),
                user: $user
            );
        }
        return $films;
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

    public function create(Film $film): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO films (name, year, format, user_id)
            VALUES (:name, :year, :format, :user_id)
        ");
        $stmt->execute([
            ':name' => $film->getName(),
            ':year' => $film->getYear(),
            ':format' => $film->getFormat()->value,
            ':user_id' => $film->getUser()?->getId()
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM films WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    public function isOwnedBy(int $filmId, int $userId): bool
    {
        $stmt = $this->pdo->prepare("SELECT 1 FROM films WHERE id = :id AND user_id = :uid");
        $stmt->execute([':id' => $filmId, ':uid' => $userId]);
        return (bool)$stmt->fetchColumn();
    }


}
