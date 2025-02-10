<?php
require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/PostController.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();
$remoteUrl = $_ENV['REMOTE_URL'];

$controller = new PostController($db);
$authMiddleware = new AuthMiddleware();

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uriSegments = explode('/', trim($requestUri, '/'));

header('Content-Type: application/json');

if ($uriSegments[0] === 'simple-api' && isset($uriSegments[1])) {
    switch ($uriSegments[1]) {
        case 'posts':
            if ($requestMethod === 'GET' && !isset($uriSegments[2])) {
                $controller->getPosts();
            } elseif ($requestMethod === 'GET' && isset($uriSegments[2])) {
                $controller->getPost($uriSegments[2]);
            } elseif ($requestMethod === 'POST') {
                $authMiddleware->__invoke($_SERVER, $_SERVER, function() {});
                $data = json_decode(file_get_contents('php://input'), true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $data = $_POST;
                }

                // Gestisci l'upload dell'immagine
                if (isset($_FILES['image'])) {
                    $targetDir = __DIR__ . '/uploads/';
                    $targetFile = $targetDir . basename($_FILES['image']['name']);
                    $remoteName = $remoteUrl . "/uploads/" . basename($_FILES['image']['name']);
                    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                    // Controlla il tipo di file
                    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                    if (!in_array($imageFileType, $allowedTypes)) {
                        http_response_code(400);
                        echo json_encode(['message' => 'Tipo di file non consentito']);
                        exit;
                    }

                    // Controlla la dimensione del file
                    if ($_FILES['image']['size'] > 5000000) { // 5MB
                        http_response_code(400);
                        echo json_encode(['message' => 'File troppo grande']);
                        exit;
                    }

                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                        http_response_code(500);
                        echo json_encode(['message' => 'Errore durante il caricamento del file']);
                        exit;
                    }

                    $data['image'] = $remoteName;
                }

                // Sanitizza e valida i dati
                $data = array_map('htmlspecialchars', $data);

                $controller->createPost($data);
            } elseif ($requestMethod === 'PUT' && isset($uriSegments[2])) {
                $authMiddleware->__invoke($_SERVER, $_SERVER, function() {});
                $data = json_decode(file_get_contents('php://input'), true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $data = $_POST;
                }

                // Recupera il percorso dell'immagine esistente dal database
                $existingPost = $controller->getPost($uriSegments[2]);
                if ($existingPost && isset($existingPost['image'])) {
                    $existingImagePath = __DIR__ . '/uploads/' . basename($existingPost['image']);
                    if (file_exists($existingImagePath)) {
                        unlink($existingImagePath);
                    }
                }

                // Gestisci l'upload della nuova immagine
                if (isset($_FILES['image'])) {
                    $targetDir = __DIR__ . '/uploads/';
                    $targetFile = $targetDir . basename($_FILES['image']['name']);
                    $remoteName = $remoteUrl . "/uploads/" . basename($_FILES['image']['name']);
                    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                    // Controlla il tipo di file
                    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                    if (!in_array($imageFileType, $allowedTypes)) {
                        http_response_code(400);
                        echo json_encode(['message' => 'Tipo di file non consentito']);
                        exit;
                    }

                    // Controlla la dimensione del file
                    if ($_FILES['image']['size'] > 5000000) { // 5MB
                        http_response_code(400);
                        echo json_encode(['message' => 'File troppo grande']);
                        exit;
                    }

                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                        http_response_code(500);
                        echo json_encode(['message' => 'Errore durante il caricamento del file']);
                        exit;
                    }

                    $data['image'] = $remoteName;
                }

                // Sanitizza e valida i dati
                $data = array_map('htmlspecialchars', $data);

                $controller->updatePost($uriSegments[2], $data);
            } elseif ($requestMethod === 'DELETE' && isset($uriSegments[2])) {
                $authMiddleware->__invoke($_SERVER, $_SERVER, function() {});
                $controller->deletePost($uriSegments[2]);
            } else {
                http_response_code(405);
                echo json_encode(['message' => 'Method Not Allowed']);
            }
            break;
        default:
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
            break;
    }
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Not Found']);
}
?>