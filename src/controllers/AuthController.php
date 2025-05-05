<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController {
    private $userModel;
    private $secretKey;

    public function __construct($database) {
        $this->userModel = new User($database);
        $this->secretKey = $_ENV['SECRET_TOKEN'];
    }

    public function register($data) {
        if (empty($data['email']) || empty($data['password']) || empty($data['username']) || empty($data['user_type'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Email, username, password, and user type are required']);
            return;
        }

        if ($this->userModel->findByEmail($data['email'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Email already exists']);
            return;
        }

        if ($this->userModel->create($data['username'], $data['password'], $data['email'], $data['privacy_accepted'], $data['user_type'])) {
            http_response_code(201);
            echo json_encode(['message' => 'User registered successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Error registering user']);
        }
    }

    public function login($data) {
        if (empty($data['email']) || empty($data['password'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Email and password are required']);
            return;
        }

        $user = $this->userModel->findByEmail($data['email']);
        if ($user && password_verify($data['password'], $user['password'])) {
            $token = JWT::encode(['id' => $user['id'], 'email' => $user['email'], 'username' => $user['username'], 'user_type' => $user['user_type']], $this->secretKey, 'HS256');
            echo json_encode(['token' => $token]);
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid email or password']);
        }
    }

    public function verifyToken($token) {
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            return [
                'valid' => true,
                'data' => $decoded
            ];
        } catch (Exception $e) {
            return [
                'valid' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
?>