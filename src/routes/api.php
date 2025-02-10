<?php
require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/PostController.php';

$controller = new PostController($db);

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uriSegments = explode('/', trim($requestUri, '/'));

header('Content-Type: application/json');
/* prova su corso
if($uriSegments[0] === 'wd2' && $uriSegments[1] === 'api' && isset($uriSegments[2])) {
*/
if ($uriSegments[0] === 'simple-api' && isset($uriSegments[1])) {
    switch ($uriSegments[1]) {
        case 'posts':
            if ($requestMethod === 'GET' && !isset($uriSegments[2])) {
                $controller->getPosts();
            } elseif ($requestMethod === 'GET' && isset($uriSegments[2])) {
                $controller->getPost($uriSegments[2]);
            } elseif ($requestMethod === 'POST') {
                // Controlla se i dati sono stati inviati come JSON nel corpo della richiesta
                $data = json_decode(file_get_contents('php://input'), true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    // Se non sono dati JSON, utilizza $_POST
                    $data = $_POST;
                }
                
                // Sanitizza e valida i dati
                $data = array_map('htmlspecialchars', $data);
                
                $controller->createPost($data);
            } elseif ($requestMethod === 'PUT' && isset($uriSegments[2])) {
                // Controlla se i dati sono stati inviati come JSON nel corpo della richiesta
                $data = json_decode(file_get_contents('php://input'), true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    // Se non sono dati JSON, utilizza $_POST
                    $data = $_POST;
                }
                
                // Sanitizza e valida i dati
                $data = array_map('htmlspecialchars', $data);
                
                $controller->updatePost($uriSegments[2], $data);
            } elseif ($requestMethod === 'DELETE' && isset($uriSegments[2])) {
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