<?php

class AuthMiddleware {
    private $validToken;

    public function __construct() {
        $this->validToken = $_ENV['SECRET_TOKEN'];
    }

    public function __invoke($request, $response, $next) {
        $headers = apache_request_headers();
        if (!isset($headers['Authorization']) || $headers['Authorization'] !== 'Bearer ' . $this->validToken) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            exit;
        }
        return $next($request, $response);
    }
}