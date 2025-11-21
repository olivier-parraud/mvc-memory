<?php

namespace Core;
use PDO;


class Score
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getPdo();
    }

    public function save(int $userId, int $moves): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO scores (user_id, moves) VALUES (?, ?)');
        $stmt->execute([$userId, $moves]);
    }

    public function getTopScores(int $limit = 10): array
    {
        $stmt = $this->pdo->query(
            'SELECT u.username, s.moves, s.played_at 
             FROM scores s 
             JOIN users u ON s.user_id = u.id 
             ORDER BY s.moves ASC 
             LIMIT ' . $limit
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
