<?php

class PostController {
    private $db;

    public function __construct($database) {
        $this->db = $database;
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
        $stmt = $this->db->prepare("INSERT INTO posts (title, content, image, category) VALUES (:title, :content, :image, :category)");
        $stmt->bindParam(':title', htmlspecialchars($data['title']));
        $stmt->bindParam(':content', htmlspecialchars($data['content']));
        $stmt->bindParam(':image', htmlspecialchars($data['image']));
        $stmt->bindParam(':category', htmlspecialchars($data['category']));
        $stmt->execute();
        echo json_encode(['id' => $this->db->lastInsertId()]);
    }

    public function updatePost($id, $data) {
        $stmt = $this->db->prepare("UPDATE posts SET title = :title, content = :content, image = :image, category = :category WHERE id = :id");
        $stmt->bindParam(':title', htmlspecialchars($data['title']));
        $stmt->bindParam(':content', htmlspecialchars($data['content']));
        $stmt->bindParam(':image', htmlspecialchars($data['image']));
        $stmt->bindParam(':category', htmlspecialchars($data['category']));
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        echo json_encode(['message' => 'Post aggiornato con successo']);
    }

    public function deletePost($id) {
        // Recupera il percorso dell'immagine dal database
        $stmt = $this->db->prepare("SELECT image FROM posts WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($post && $post['image']) {
            // Elimina il file immagine dal server
            $imagePath = __DIR__ . '/../uploads/' . basename($post['image']);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Elimina il record del post dal database
        $stmt = $this->db->prepare("DELETE FROM posts WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        echo json_encode(['message' => 'Post eliminato con successo']);
    }
}
?>