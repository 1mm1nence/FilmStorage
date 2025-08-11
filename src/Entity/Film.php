<?php

namespace App\Entity;

class Film
{
    private ?int $id;
    private string $name;
    private int $year;
    private string $format;

    public function __construct(?int $id, string $name, int $year, string $format)
    {
        $this->id = $id;
        $this->setName($name);
        $this->setYear($year);
        $this->setFormat($format);
    }

    // --- Getters ---
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    // --- Setters ---
    public function setName(string $name): void
    {
        $name = trim($name);
        if ($name === '') {
            throw new InvalidArgumentException("Film name cannot be empty.");
        }
        $this->name = $name;
    }

    public function setYear(int $year): void
    {
        if ($year < 1888 || $year > intval(date("Y"))) {
            throw new InvalidArgumentException("Invalid film year.");
        }
        $this->year = $year;
    }

    public function setFormat(string $format): void
    {
        $validFormats = ['VHS', 'DVD', 'Blu-ray'];
        if (!in_array($format, $validFormats, true)) {
            throw new InvalidArgumentException("Invalid format. Allowed: " . implode(', ', $validFormats));
        }
        $this->format = $format;
    }
}
