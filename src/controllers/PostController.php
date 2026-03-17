<?php

class PostController {
    private $db;
    private $uploadsDir;
    private $uploadsUrl;

    public function __construct($database, $uploadsDir, $uploadsUrl) {
        $this->db = $database;
        $this->uploadsDir = rtrim($uploadsDir, '/\\');
        $this->uploadsUrl = rtrim($uploadsUrl, '/');
    }

    public function getPosts() {
        $posts = $this->db->query("SELECT * FROM posts")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($posts);
    }

    public function getPost($id) {
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($post);
    }

    public function createPost($data) {
        $title    = htmlspecialchars($data['title']    ?? '');
        $content  = htmlspecialchars($data['content']  ?? '');
        $category = htmlspecialchars($data['category'] ?? '');
        $image    = $this->handleImageUpload($data['image'] ?? '');

        $stmt = $this->db->prepare("INSERT INTO posts (title, content, image, category) VALUES (:title, :content, :image, :category)");
        $stmt->bindParam(':title',    $title);
        $stmt->bindParam(':content',  $content);
        $stmt->bindParam(':image',    $image);
        $stmt->bindParam(':category', $category);
        $stmt->execute();
        echo json_encode(['id' => $this->db->lastInsertId(), 'image' => $image]);
    }

    public function updatePost($id, $data) {
        // Recupera immagine attuale prima di sovrascriverla
        $stmt = $this->db->prepare("SELECT image FROM posts WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $existing     = $stmt->fetch(PDO::FETCH_ASSOC);
        $currentImage = $existing['image'] ?? '';

        $title    = htmlspecialchars($data['title']    ?? '');
        $content  = htmlspecialchars($data['content']  ?? '');
        $category = htmlspecialchars($data['category'] ?? '');
        // Se arriva un file nuovo lo usa; altrimenti mantiene l'URL già in $data o quello attuale
        $image = $this->handleImageUpload($data['image'] ?? $currentImage);

        // Cancella il vecchio file locale solo se è cambiata l'immagine
        if ($image !== $currentImage) {
            $this->deleteLocalImage($currentImage);
        }

        $stmt = $this->db->prepare("UPDATE posts SET title = :title, content = :content, image = :image, category = :category WHERE id = :id");
        $stmt->bindParam(':title',    $title);
        $stmt->bindParam(':content',  $content);
        $stmt->bindParam(':image',    $image);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':id',       $id, PDO::PARAM_INT);
        $stmt->execute();
        echo json_encode(['message' => 'Post aggiornato con successo', 'image' => $image]);
    }

    public function deletePost($id) {
        $stmt = $this->db->prepare("SELECT image FROM posts WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($post && !empty($post['image'])) {
            $this->deleteLocalImage($post['image']);
        }

        $stmt = $this->db->prepare("DELETE FROM posts WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        echo json_encode(['message' => 'Post eliminato con successo']);
    }

    // -----------------------------------------------------------------
    // Helpers privati
    // -----------------------------------------------------------------

    /**
     * Gestisce l'upload del file immagine.
     * Se non arriva nessun file, restituisce $fallback (URL già presente o stringa vuota).
     */
    private function handleImageUpload($fallback = '') {
        if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
            return $fallback;
        }

        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['message' => 'Errore upload immagine (codice: ' . $_FILES['image']['error'] . ')']);
            exit;
        }

        // Validazione tipo MIME reale (non solo estensione)
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $finfo        = new finfo(FILEINFO_MIME_TYPE);
        $mimeType     = $finfo->file($_FILES['image']['tmp_name']);
        if (!in_array($mimeType, $allowedMimes, true)) {
            http_response_code(400);
            echo json_encode(['message' => 'Tipo file non consentito. Formati accettati: jpg, png, gif, webp']);
            exit;
        }

        // Dimensione massima 5 MB
        if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
            http_response_code(400);
            echo json_encode(['message' => 'File troppo grande (massimo 5 MB)']);
            exit;
        }

        $ext         = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $filename    = uniqid('img_', true) . '.' . $ext;
        $destination = $this->uploadsDir . DIRECTORY_SEPARATOR . $filename;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
            http_response_code(500);
            echo json_encode(['message' => 'Errore nel salvataggio del file']);
            exit;
        }

        return $this->uploadsUrl . '/' . $filename;
    }

    /**
     * Cancella il file fisico solo se l'URL appartiene alla cartella uploads locale.
     * Gli URL remoti (http/https di altri host) vengono ignorati.
     */
    private function deleteLocalImage($imageUrl) {
        if (empty($imageUrl)) return;
        // Appartiene ai nostri uploads? (strpos compatibile PHP 7)
        if (strpos($imageUrl, $this->uploadsUrl) !== 0) return;

        $filename = basename(parse_url($imageUrl, PHP_URL_PATH));
        $filePath = $this->uploadsDir . DIRECTORY_SEPARATOR . $filename;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
?>