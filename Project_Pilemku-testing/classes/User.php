<?php
namespace Classes;

use PDO;

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function register($username, $password, $email, $role) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("INSERT INTO users (username, password, email, role) VALUES (:username, :password, :email, :role)");
        $stmt->execute([
            ':username' => $username,
            ':password' => $hashedPassword,
            ':email' => $email,
            ':role' => $role,
        ]);
    }

    public function login($username, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user; // Return user data if login is successful
        }
        return false;
    }

    public function updateProfile($id, $name, $profilePicture) {
        $stmt = $this->pdo->prepare("UPDATE users SET name = :name, profile_picture = :profile_picture WHERE id = :id");
        $stmt->execute([
            ':name' => $name,
            ':profile_picture' => $profilePicture,
            ':id' => $id,
        ]);
    }

    public function getUserById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCurrentUser($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfilePicture($userId, $filePath) {
        $stmt = $this->pdo->prepare("UPDATE users SET profile_picture = :filePath WHERE id = :id");
        $stmt->execute([
            ':filePath' => $filePath,
            ':id' => $userId
        ]);
    }

    public function searchMovies($query) {
        $sql = "SELECT * FROM movies WHERE judul LIKE :query OR genre LIKE :query";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['query' => '%' . $query . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isMovieInWatchlist($userId, $movieId) {
        $stmt = $this->pdo->prepare('SELECT * FROM watchlist WHERE user_id = :user_id AND movie_id = :movie_id');
        $stmt->execute(['user_id' => $userId, 'movie_id' => $movieId]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Array jika ditemukan, false jika tidak
    }
    

}
