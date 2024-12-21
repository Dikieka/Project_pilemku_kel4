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

    public function addMovie($judul, $tanggal_rilis, $sutradara, $deskripsi, $gambar_poster)
    {
        $stmt = $this->pdo->prepare("INSERT INTO movies (judul, tanggal_rilis, sutradara, deskripsi, gambar_poster) VALUES (:judul, :tanggal_rilis, :sutradara, :deskripsi, :gambar_poster)");
        $stmt->execute([
            ':judul' => $judul,
            ':tanggal_rilis' => $tanggal_rilis,
            ':sutradara' => $sutradara,
            ':deskripsi' => $deskripsi,
            ':gambar_poster' => $gambar_poster
        ]);
    }

    public function updateMovie($id, $judul, $tanggal_rilis, $sutradara, $deskripsi, $gambar_poster)
    {
        $stmt = $this->pdo->prepare("UPDATE movies SET judul = :judul, tanggal_rilis = :tanggal_rilis, sutradara = :sutradara, deskripsi = :deskripsi, gambar_poster = :gambar_poster WHERE id = :id");
        $stmt->execute([
            ':id' => $id,
            ':judul' => $judul,
            ':tanggal_rilis' => $tanggal_rilis,
            ':sutradara' => $sutradara,
            ':deskripsi' => $deskripsi,
            ':gambar_poster' => $gambar_poster
        ]);
    }

    public function deleteMovie($id)
    {
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
    }
}
