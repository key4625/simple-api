<?php
require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/PostController.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();
$remoteUrl = rtrim($_ENV['REMOTE_URL'], '/'); // Rimuove eventuali slash finali

$postController = new PostController($db);
$authController = new AuthController($db);
$authMiddleware = new AuthMiddleware();

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = str_replace(parse_url($remoteUrl, PHP_URL_PATH), '', $_SERVER['REQUEST_URI']); // Rimuove il prefisso del percorso base
$requestUri = parse_url($requestUri, PHP_URL_PATH);
$uriSegments = explode('/', trim($requestUri, '/'));

header('Content-Type: application/json');

if (isset($uriSegments[0]) && $uriSegments[0] === 'posts') {
    switch ($uriSegments[1] ?? '') {
        case '':
            if ($requestMethod === 'GET') {
                $postController->getPosts();
            } elseif ($requestMethod === 'POST') {
                $authMiddleware->__invoke($_SERVER, $_SERVER, function () {});
                $data = json_decode(file_get_contents('php://input'), true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $data = $_POST;
                }
                $postController->createPost($data);
            } else {
                http_response_code(405);
                echo json_encode(['message' => 'Method Not Allowed']);
            }
            break;

        default:
            if ($requestMethod === 'GET') {
                $postController->getPost($uriSegments[1]);
            } elseif ($requestMethod === 'PUT') {
                $authMiddleware->__invoke($_SERVER, $_SERVER, function () {});
                $data = json_decode(file_get_contents('php://input'), true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $data = $_POST;
                }
                $postController->updatePost($uriSegments[1], $data);
            } elseif ($requestMethod === 'DELETE') {
                $authMiddleware->__invoke($_SERVER, $_SERVER, function () {});
                $postController->deletePost($uriSegments[1]);
            } else {
                http_response_code(405);
                echo json_encode(['message' => 'Method Not Allowed']);
            }
            break;
    }
} elseif (isset($uriSegments[0]) && $uriSegments[0] === 'login') {
    if ($requestMethod === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $data = $_POST;
        }
        $authController->login($data);
    } else {
        http_response_code(405);
        echo json_encode(['message' => 'Method Not Allowed']);
    }
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Not Found']);
}
?>