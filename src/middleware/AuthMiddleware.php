<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {
    private $secretKey;

    public function __construct() {
        $this->secretKey = $_ENV['SECRET_TOKEN'];
    }

    public function __invoke($request, $response, $next) {
        $headers = apache_request_headers();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            exit;
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            $email = $decoded->email;
            $username = $decoded->username;
            $userType = $decoded->user_type;
            return $next($request, $response);
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            exit;
        }
    }
}
?>