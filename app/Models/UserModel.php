<?php

namespace Core;
use PDO;


class User
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getPdo();
    }

    public function find_by_username(string $username): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function create(string $username): int
    {
        $stmt = $this->pdo->prepare('INSERT INTO users (username) VALUES (?)');
        $stmt->execute([$username]);
        return (int) $this->pdo->lastInsertId();
    }

    public function find_or_create(string $username): array
    {
        $user = $this->find_by_username($username);

        if ($user) {
            return $user;
        }

        $userId = $this->create($username);
        return ['id' => $userId];
    }
}
