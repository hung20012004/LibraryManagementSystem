<?php
include_once 'Model.php';
class Condition extends Model
{
    protected $table_name = 'book_condition';

    public $condition_id;
    public $book_id;
    public $loan_id;
    public $condition_before;
    
    public $condition_after;
    public $damage_description;
    public $assessed_by;
    public $assessed_date;
    public $notes;
    public $created_at;

    public function __construct(){
        parent::__construct();
    }

    public function create()
    {
        $query = "INSERT INTO {$this->table_name} 
        (book_id, loan_id, condition_before, condition_after, damage_description, assessed_by, assessed_date, notes) 
        VALUES (:book_id, :loan_id, :condition_before, :condition_after, :damage_description, :assessed_by, :assessed_date, :notes)";

        $stmt = $this->conn->prepare($query);

        // Binding các tham số
        $stmt->bindParam(':book_id', $this->book_id);
        $stmt->bindParam(':loan_id', $this->loan_id);
        $stmt->bindParam(':condition_before', $this->condition_before);
        $stmt->bindParam(':condition_after', $this->condition_after);
        $stmt->bindParam(':damage_description', $this->damage_description);
        $stmt->bindParam(':assessed_by', $this->assessed_by);
        $stmt->bindParam(':assessed_date', $this->assessed_date);
        $stmt->bindParam(':notes', $this->notes);

        return $stmt->execute();
    }

    public function read() {
        $query = "
            SELECT 
                bc.*, 
                b.title AS book_title, 
                u.full_name AS user_name
            FROM 
                book_condition bc
            LEFT JOIN 
                book b ON bc.book_id = b.book_id
            LEFT JOIN 
                user u ON bc.assessed_by = u.user_id
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function readById($id) {
        $query = "SELECT * FROM {$this->table_name} WHERE condition_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id)
    {
        $sql = "UPDATE book_condition 
                SET condition_after = :condition_after, damage_description = :damage_description, notes = :notes
                WHERE condition_id = :condition_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':condition_after', $this->condition_after);
        $stmt->bindValue(':damage_description', $this->damage_description);
        $stmt->bindValue(':notes', $this->notes);
        $stmt->bindValue(':condition_id', $id);

        return $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM book_condition 
        WHERE condition_id = :condition_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':condition_id', $id);

        return $stmt->execute();
    }
    
    public function getLoans() {
        $query = "
        SELECT 
            l.loan_id, l.book_id 
        FROM 
            loan l
        LEFT JOIN 
            book_condition bc ON l.loan_id = bc.loan_id
        WHERE 
            l.status = 'returned' AND bc.loan_id IS NULL
    ";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAuthors() {
        $query = "SELECT author_id, name FROM author"; 
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}