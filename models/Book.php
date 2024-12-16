<?php
include_once 'Model.php';
class Book extends Model
{
    protected $table_name = 'book';

    public $book_id;
    public $publisher_id;
    public $title;
    public $publication_year;
    public $edition;
    public $pages;
    public $language;
    public $description;
    public $quantity;
    public $available_quantity;
    public $price;
    public $status;
    public $cover_image;
    public $created_at;
    public $updated_at;

    public function __construct() {
        parent::__construct();
    }

    public function create() {
        $query = "INSERT INTO {$this->table_name} 
        (publisher_id, title, publication_year, edition, pages, language, description, quantity, available_quantity, price, status, cover_image) 
        VALUES (:publisher_id, :title, :publication_year, :edition, :pages, :language, :description, :quantity, :available_quantity, :price, :status, :cover_image)";

        $stmt = $this->conn->prepare($query);

        if (empty($this->status)) {
            $this->status = 'available';
        }

        // Binding các tham số
        $stmt->bindParam(':publisher_id', $this->publisher_id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':publication_year', $this->publication_year);
        $stmt->bindParam(':edition', $this->edition);
        $stmt->bindParam(':pages', $this->pages);
        $stmt->bindParam(':language', $this->language);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':available_quantity', $this->available_quantity);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':cover_image', $this->cover_image);

        return $stmt->execute();
    }

    public function read() {
        $query = "
            SELECT 
                b.*, 
                p.name AS publisher_name, 
                GROUP_CONCAT(DISTINCT a.name SEPARATOR ', ') AS authors,
                GROUP_CONCAT(DISTINCT c.name SEPARATOR ', ') AS categories
            FROM 
                {$this->table_name} b
            LEFT JOIN 
                publisher p ON b.publisher_id = p.publisher_id
            LEFT JOIN 
                book_author ba ON b.book_id = ba.book_id
            LEFT JOIN 
                author a ON ba.author_id = a.author_id
            LEFT JOIN 
                book_category bc ON b.book_id = bc.book_id
            LEFT JOIN 
                category c ON bc.category_id = c.category_id
            GROUP BY 
                b.book_id
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readById($id) {
        $query = "
        SELECT 
            b.*, 
            p.name AS publisher_name, 
            GROUP_CONCAT(DISTINCT a.name SEPARATOR ', ') AS authors,
            GROUP_CONCAT(DISTINCT c.name SEPARATOR ', ') AS categories
        FROM 
            {$this->table_name} b
        LEFT JOIN 
            publisher p ON b.publisher_id = p.publisher_id
        LEFT JOIN 
            book_author ba ON b.book_id = ba.book_id
        LEFT JOIN 
            author a ON ba.author_id = a.author_id
        LEFT JOIN 
            book_category bc ON b.book_id = bc.book_id
        LEFT JOIN 
            category c ON bc.category_id = c.category_id
        WHERE b.book_id = :id
        GROUP BY 
            b.book_id
    ";
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

    public function updateImage($id) {
        $query = "UPDATE `" . $this->table_name . "` SET cover_image = ? WHERE " . $this->table_name . "_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(1, $this->avatar_url);
        $stmt->bindValue(2, $id);
        return $stmt->execute();
    }
  
    public function getPublishers() {
        $query = "SELECT publisher_id, name FROM publisher ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAuthors() {
        $query = "SELECT author_id, name FROM author"; 
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAuthorsByBookId($id) {
        $query = "
            SELECT a.author_id, a.name 
            FROM book_author ba
            JOIN author a ON ba.author_id = a.author_id
            WHERE ba.book_id = :book_id
        ";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':book_id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    

    public function getCategories() {
        $query = "SELECT category_id, name FROM category";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getLastInsertedId() {
        return $this->conn->lastInsertId(); // PDO method để lấy ID của bản ghi vừa được thêm
    }

    public function getCategoriesByBookId($id) {
        $query = "
            SELECT c.category_id, c.name
            FROM book_category bc
            JOIN category c ON bc.category_id = c.category_id
            WHERE bc.book_id = :book_id
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':book_id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateBookAuthor($id, $author_ids) {
        // Xóa tất cả các tác giả hiện tại chỉ một lần
        $sqlDelete = "DELETE FROM book_author WHERE book_id = :book_id";
        $stmtDelete = $this->conn->prepare($sqlDelete);
        $stmtDelete->bindParam(':book_id', $id, PDO::PARAM_INT);
        $stmtDelete->execute();
    
        // Thêm mới các tác giả
        $sqlInsert = "INSERT INTO book_author (book_id, author_id) VALUES (:book_id, :author_id)";
        $stmtInsert = $this->conn->prepare($sqlInsert);
        $stmtInsert->bindParam(':book_id', $id, PDO::PARAM_INT);
        
        foreach ($author_ids as $author_id) {
            $stmtInsert->bindParam(':author_id', $author_id, PDO::PARAM_INT);
            $stmtInsert->execute();
        }
    }

    public function updateBookCategory($id, $category_ids) {
        // Xóa tất cả các danh mục hiện tại chỉ một lần
        $sqlDelete = "DELETE FROM book_category WHERE book_id = :book_id";
        $stmtDelete = $this->conn->prepare($sqlDelete);
        $stmtDelete->bindParam(':book_id', $id, PDO::PARAM_INT);
        $stmtDelete->execute();
    
        // Thêm mới các thể loại
        $sqlInsert = "INSERT INTO book_category (book_id, category_id) VALUES (:book_id, :category_id)";
        $stmtInsert = $this->conn->prepare($sqlInsert);
        $stmtInsert->bindParam(':book_id', $id, PDO::PARAM_INT);
    
        foreach ($category_ids as $category_id) {
            $stmtInsert->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            $stmtInsert->execute();
        }
    }

    public function getUnassessedBooks() {
        $query = "
            SELECT 
                b.book_id, 
                b.title AS book_title
            FROM 
                loan l
            INNER JOIN 
                book b ON l.book_id = b.book_id
            LEFT JOIN 
                book_condition bc ON l.loan_id = bc.loan_id
            WHERE 
                bc.loan_id IS NULL
            AND 
                l.status = 'returned'
            GROUP BY 
                b.book_id, b.title
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
