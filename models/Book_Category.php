<?php
include_once 'Model.php';
class Book_Category extends Model
{
    protected $table_name = 'book_category';

    public $book_id;
    public $category_id;
    public $role;
    public $created_at;
    public $updated_at;

    public function __construct() {
        parent::__construct();
    }

    public function insertBookCategory($bookId, $categoryId) {
        $sql = "INSERT INTO book_category (book_id, category_id) VALUES (:book_id, :category_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':book_id', $bookId, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function read() {
        $query = "SELECT * FROM {$this->table_name}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readByBookId($id) {
        $query = "SELECT * FROM {$this->table_name} WHERE book_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function readByCategoryId($id) {
        $query = "SELECT * FROM {$this->table_name} WHERE author_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id) {
        return parent::update($id);
    }

    public function delete($id) {
        return parent::delete($id);
    }
  
}
