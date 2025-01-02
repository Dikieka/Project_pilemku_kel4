<?php

namespace Classes;

class Watchlist
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function addToWatchlist($userId, $movieId, $tipeWatchlist)
    {
        $stmt = $this->pdo->prepare("INSERT INTO watchlist (user_id, movie_id, tipe_watchlist) VALUES (:user_id, :movie_id, :tipe_watchlist)");
        $stmt->execute(['user_id' => $userId, 'movie_id' => $movieId, 'tipe_watchlist' => $tipeWatchlist]);
    }

    public function getUserWatchlist($userId)
    {
        $stmt = $this->pdo->prepare("SELECT w.id, w.movie_id, w.tipe_watchlist, m.judul, m.gambar_poster FROM watchlist w JOIN movies m ON w.movie_id = m.id WHERE w.user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $watchlist = ['watching' => [], 'completed' => [], 'on-hold' => [], 'dropped' => [], 'plan-to-watch' => []];
        foreach ($result as $row) {
            $watchlist[$row['tipe_watchlist']][] = [
                'id' => $row['movie_id'],
                'judul' => $row['judul'],
                'gambar_poster' => $row['gambar_poster']
            ];
        }
        return $watchlist;
    }

    public function isMovieInWatchlist($userId, $movieId)
    {
        $stmt = $this->pdo->prepare("SELECT id, tipe_watchlist FROM watchlist WHERE user_id = :user_id AND movie_id = :movie_id");
        $stmt->execute(['user_id' => $userId, 'movie_id' => $movieId]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ?: null; // Kembalikan null jika tidak ada data
    }


    public function updateWatchlistType($userId, $watchlistId, $newType)
    {
        $stmt = $this->pdo->prepare("UPDATE watchlist SET tipe_watchlist = :new_type WHERE id = :id AND user_id = :user_id");
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

