<?php

namespace Classes;

class Watchlist
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function addToWatchlist($userId, $movieId, $watchlistType)
    {
        $stmt = $this->pdo->prepare("INSERT INTO watchlist (user_id, movie_id, watchlist_type, tanggal_ditambahkan) VALUES (:user_id, :movie_id, :watchlist_type, CURRENT_TIMESTAMP)");
        $stmt->execute(['user_id' => $userId, 'movie_id' => $movieId, 'watchlist_type' => $watchlistType]);
    }

    public function getUserWatchlist($userId)
    {
        $stmt = $this->pdo->prepare("SELECT w.id, w.watchlist_type, w.tanggal_ditambahkan, m.judul FROM watchlist w JOIN movies m ON w.movie_id = m.id WHERE w.user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $watchlist = ['watching' => [], 'completed' => [], 'on-hold' => [], 'dropped' => [], 'plan-to-watch' => []];
        foreach ($result as $row) {
            $watchlist[$row['watchlist_type']][] = [
                'id' => $row['id'],
                'judul' => $row['judul'],
                'tanggal_ditambahkan' => $row['tanggal_ditambahkan']
            ];
        }
        return $watchlist;
    }

    public function isMovieInWatchlist($userId, $movieId)
    {
        $stmt = $this->pdo->prepare("SELECT id FROM watchlist WHERE user_id = :user_id AND movie_id = :movie_id");
        $stmt->execute(['user_id' => $userId, 'movie_id' => $movieId]);
        return $stmt->fetch() !== false;
    }

    public function updateWatchlistType($userId, $watchlistId, $newType)
    {
        $stmt = $this->pdo->prepare("UPDATE watchlist SET watchlist_type = :new_type WHERE id = :id AND user_id = :user_id");
        $stmt->execute(['new_type' => $newType, 'id' => $watchlistId, 'user_id' => $userId]);
    }

    public function deleteFromWatchlist($userId, $watchlistId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM watchlist WHERE id = :id AND user_id = :user_id");
        $stmt->execute(['id' => $watchlistId, 'user_id' => $userId]);
    }

    public function deleteMovieFromAllWatchlists($movieId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM watchlist WHERE movie_id = :movie_id");
        $stmt->execute(['movie_id' => $movieId]);
    }
}
