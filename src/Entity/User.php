<?php

namespace App\Entity;

class User
{
    private ?int $id;
    private string $username;
    private ?string $password;

    public function __construct(?int $id, string $username)
    {
        $this->id = $id;
        $this->setUsername($username);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);

        return $this;
    }
}
