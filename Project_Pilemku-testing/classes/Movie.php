<?php

namespace Classes;

use PDO;

class Movie
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllMovies()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM movies");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMovieById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM movies WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addMovie($judul, $genre, $tanggal_rilis, $sutradara, $deskripsi, $gambar_poster)
    {
        $stmt = $this->pdo->prepare("INSERT INTO movies (judul, genre, tanggal_rilis, sutradara, deskripsi, gambar_poster) VALUES (:judul, :genre, :tanggal_rilis, :sutradara, :deskripsi, :gambar_poster)");
        $stmt->execute([
            ':judul' => $judul,
            ':genre' => $genre,
            ':tanggal_rilis' => $tanggal_rilis,
            ':sutradara' => $sutradara,
            ':deskripsi' => $deskripsi,
            ':gambar_poster' => $gambar_poster
        ]);
    }

    public function updateMovie($id, $judul, $genre, $tanggal_rilis, $sutradara, $deskripsi, $gambar_poster = null)
    {
        // Ambil data film lama untuk mendapatkan poster lama
        $stmt = $this->pdo->prepare("SELECT gambar_poster FROM movies WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $oldMovie = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Jika ada poster baru, hapus poster lama
        if ($gambar_poster && $oldMovie && file_exists($oldMovie['gambar_poster'])) {
            unlink($oldMovie['gambar_poster']);
        }
    
        // Update data film
        $query = "UPDATE movies SET judul = :judul, genre = :genre, tanggal_rilis = :tanggal_rilis, 
                  sutradara = :sutradara, deskripsi = :deskripsi";
        if ($gambar_poster) {
            $query .= ", gambar_poster = :gambar_poster";
        }
        $query .= " WHERE id = :id";
    
        $params = [
            ':id' => $id,
            ':judul' => $judul,
            ':genre' => $genre,
            ':tanggal_rilis' => $tanggal_rilis,
            ':sutradara' => $sutradara,
            ':deskripsi' => $deskripsi,
        ];
        if ($gambar_poster) {
            $params[':gambar_poster'] = $gambar_poster;
        }
    
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
    }
    

    public function deleteMovieAndWatchlist($id)
    {
        // Start a transaction
        $this->pdo->beginTransaction();

        try {
            // Delete watchlist entries associated with the movie
            $stmt = $this->pdo->prepare("DELETE FROM watchlist WHERE movie_id = :movie_id");
            $stmt->execute([':movie_id' => $id]);

            // Retrieve the file path of the image
            $stmt = $this->pdo->prepare("SELECT gambar_poster FROM movies WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $movie = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($movie && file_exists($movie['gambar_poster'])) {
                // Delete the image file
                unlink($movie['gambar_poster']);
            }

            // Delete the movie record from the database
            $stmt = $this->pdo->prepare("DELETE FROM movies WHERE id = :id");
            $stmt->execute([':id' => $id]);

            // Commit the transaction
            $this->pdo->commit();
        } catch (\Exception $e) {
            // Rollback the transaction if something failed
            $this->pdo->rollBack();
            throw $e;
        }
    }
}

