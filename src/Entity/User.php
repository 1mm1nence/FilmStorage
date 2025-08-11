<?php

namespace App\Entity;

class User
{
    public int $id;
    public string $username;
    public string $password; // hashed password
    public string $email;
    public string $created_at;

    public function __construct(string $username, string $password, string $email)
    {
        $this->username = $username;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->email = $email;
        $this->created_at = date('Y-m-d H:i:s');
    }
}
