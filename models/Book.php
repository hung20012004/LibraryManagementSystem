<?php
include_once 'Model.php';
class Book extends Model
{
    protected $table_name = 'book';

    public $book_id;
    public $publisher_id;
    public $title;
    public $publication_year;
    public $pages;
    public $language;
    public $description;
    public $cover_image;
    public $quantity;
    public $vailable_quantity;
    public $price;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct() {
        parent::__construct();
    }

    public function create() {
        $this->status = 'active'; 
        return parent::create();
    }

    public function read() {
        $query = "SELECT * FROM {$this->table_name}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readById($id) {
        $query = "SELECT * FROM {$this->table_name} WHERE book_id = :id";
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
    public function checkPhoneExists($phone) {
        $query = "SELECT COUNT(*) FROM {$this->table_name} WHERE phone = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $phone, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
    public function UpdateImage($id) {
        $query = "UPDATE " . $this->table_name . " SET cover_image = ? WHERE " . $this->table_name . "_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(1, $this->cover_image);
        $stmt->bindValue(2, $id);
        return $stmt->execute();
    }

    public function checkEmailExists($email) {
        $query = "SELECT COUNT(*) FROM {$this->table_name} WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}
?>