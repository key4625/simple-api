<?php
class Post {
    private $conn;
    private $table_name = "posts";

    public $id;
    public $title;
    public $content;
    public $image;
    public $category;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function all() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function find($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (title, content, image, category) VALUES (:title, :content, :image, :category)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', htmlspecialchars($this->title));
        $stmt->bindParam(':content', htmlspecialchars($this->content));
        $stmt->bindParam(':image', htmlspecialchars($this->image));
        $stmt->bindParam(':category', htmlspecialchars($this->category));
        return $stmt->execute();
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET title = :title, content = :content, image = :image, category = :category WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':title', htmlspecialchars($this->title));
        $stmt->bindParam(':content', htmlspecialchars($this->content));
        $stmt->bindParam(':image', htmlspecialchars($this->image));
        $stmt->bindParam(':category', htmlspecialchars($this->category));
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>