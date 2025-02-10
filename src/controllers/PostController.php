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
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($post);
    }

    public function createPost($data) {
        $stmt = $this->db->prepare("INSERT INTO posts (title, content, image, category) VALUES (:title, :content, :image, :category)");
       
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':image', $data['image']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->execute();
        echo json_encode(['id' => $this->db->lastInsertId()]);
    }

    public function updatePost($id, $data) {
        $stmt = $this->db->prepare("UPDATE posts SET title = :title, content = :content, image = :image, category = :category WHERE id = :id");
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':image', $data['image']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function deletePost($id) {
        $stmt = $this->db->prepare("DELETE FROM posts WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}
?>