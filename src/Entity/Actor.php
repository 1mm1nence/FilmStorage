<?php

namespace App\Entity;

class Actor
{
    private ?int $id = null;
    private string $name;
    private string $surname;

    public function __construct(string $name, string $surname)
    {
        $this->setName($name);
        $this->setSurname($surname);
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

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }
}
