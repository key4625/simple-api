<?php

class User {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($username, $password, $email, $privacyAccepted, $userType) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (username, password, email, privacy_accepted, user_type) VALUES (:username, :password, :email, :privacy_accepted, :user_type)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':privacy_accepted', $privacyAccepted, PDO::PARAM_INT);
        $stmt->bindParam(':user_type', $userType);
        return $stmt->execute();
    }
}
?>