<?php

namespace App\Entity;

use App\Enum\FilmFormat;

class Film
{
    private ?int $id = null;
    private string $name;
    private int $year;
    private FilmFormat $format;
    private User $user;
    private array $actors;

    public function __construct(?int $id, string $name, int $year, FilmFormat $format, User $user, array $actors = [])
    {
        $this->id = $id;
        $this->setName($name);
        $this->setYear($year);
        $this->setFormat($format);
        $this->setUser($user);
        $this->setActors($actors);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getFormat(): FilmFormat
    {
        return $this->format;
    }

    public function setFormat(FilmFormat $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getActors(): array
    {
        return $this->actors;
    }

    /**
     * @param Actor[] $actors
     */
    public function setActors(array $actors): self
    {
        foreach ($actors as $actor) {
            if (!$actor instanceof Actor) {
                throw new \InvalidArgumentException("All elements must be Actor objects.");
            }
        }

        $this->actors = $actors;

        return $this;
    }

    public function addActor(Actor $actor): self
    {
        $this->actors[] = $actor;

        return $this;
    }
}
