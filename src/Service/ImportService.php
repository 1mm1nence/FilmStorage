<?php

namespace App\Service;

use App\Entity\Film;
use App\Entity\Actor;
use App\Entity\User;
use App\Enum\FilmFormat;
use App\Repository\FilmRepository;
use App\Repository\ActorRepository;
use PDO;

class ImportService
{
    private PDO $pdo;
    private FilmRepository $filmRepository;
    private ActorRepository $actorRepository;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->filmRepository = new FilmRepository($pdo);
        $this->actorRepository = new ActorRepository($pdo);
    }

    /**
     * Imports films from a text file for a given user
     *
     * @param string $filePath
     * @param User $user
     * @return array Imported Film entities
     * @throws \Throwable
     */
    public function importFromTxt(string $filePath, User $user): array
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException("File not found: $filePath");
        }

        $content = file_get_contents($filePath);

        // Remove UTF-8 BOM if present
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);

        // Normalize all line endings to "\n"
        $content = preg_replace("/\r\n|\r/", "\n", $content);

        // Split into blocks by two or more newlines
        $blocks = preg_split("/\n{2,}/", trim($content));

        $importedFilms = [];

        $this->pdo->beginTransaction();
        try {
            foreach ($blocks as $block) {
                $filmData = $this->parseBlock($block);

                $format = match (strtoupper($filmData['format'])) {
                    'VHS' => FilmFormat::VHS,
                    'DVD' => FilmFormat::DVD,
                    'BLU-RAY' => FilmFormat::BLURAY,
                    default => throw new \InvalidArgumentException("Invalid film format: {$filmData['format']}")
                };

                $film = new Film(
                    id: null,
                    name: $filmData['title'],
                    year: (int)$filmData['year'],
                    format: $format,
                    user: $user,
                    actors: []
                );

                $filmId = $this->filmRepository->create($film);
                $film->setId($filmId);

                if (!empty($filmData['actors'])) {
                    foreach ($filmData['actors'] as $actorName) {
                        $actorParts = array_map('trim', explode(' ', $actorName, 2));
                        $firstName = $actorParts[0] ?? '';
                        $lastName = $actorParts[1] ?? '';
                        $actor = new Actor(null, $firstName, $lastName);

                        $actorId = $this->actorRepository->addActorToFilm($actor, $film);
                        $actor->setId($actorId);

                        $film->addActor($actor);
                    }
                }
                $importedFilms[] = $film;
            }

            $this->pdo->commit();
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }

        return $importedFilms;
    }

    /**
     * Parse one film block
     *
     * @param string $block
     * @return array
     */
    private function parseBlock(string $block): array
    {
        $block = preg_replace("/\r\n|\r/u", "\n", $block);
        $lines = array_values(array_filter(array_map('trim', explode("\n", $block)), fn($l) => $l !== ''));

        $data = [];

        foreach ($lines as $line) {
            if (preg_match('/^Title:\s*(.+)$/i', $line, $matches)) {
                $data['title'] = trim($matches[1]);
            } elseif (preg_match('/^Release Year:\s*(\d{4})$/i', $line, $matches)) {
                $data['year'] = $matches[1];
            } elseif (preg_match('/^Format:\s*(.+)$/i', $line, $matches)) {
                $data['format'] = trim($matches[1]);
            } elseif (preg_match('/^Stars:\s*(.+)$/i', $line, $matches)) {
                $actors = array_map('trim', explode(',', $matches[1]));
                $data['actors'] = $actors;
            }
        }

        if (empty($data['title'] ?? '') || empty($data['year'] ?? '') || empty($data['format'] ?? '')) {
            throw new \RuntimeException("Invalid block structure:\n$block");
        }

        return $data;
    }

    public function isTxtFile(array $file): bool
    {
        if (!isset($file['name'], $file['tmp_name'])) {
            return false;
        }

        // 1. Check file extension
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (strtolower($ext) !== 'txt') {
            return false;
        }

        // 2. Check MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if ($mimeType !== 'text/plain') {
            return false;
        }

        return true;
    }
}
