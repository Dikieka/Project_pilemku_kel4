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
}
