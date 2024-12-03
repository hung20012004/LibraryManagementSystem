<?php
include_once 'Model.php';
class Reservation extends Model
{
    protected $table_name = 'reservation';

    public $reservation_id;
    public $book_id;
    public $user_id;
    public $reservation_date;
    public $expiry_date;
    public $fulfilled_date;
    public $status;
    public $notes;
    public $created_at;
    public $updated_at;

    public function __construct(){
        parent::__construct();
    }

    public function create()
    {
        return parent::create();
    }

    public function read() {
        $query = "SELECT r.*, b.book_id, b.title, u.user_id, u.username, u.full_name FROM {$this->table_name} r 
                  JOIN user u ON u.user_id = r.user_id
                  JOIN book b ON b.book_id = r.book_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readById($id) {
        $query = "SELECT r.*, b.book_id, b.title, b.status as book_status, u.user_id, u.username, u.full_name FROM {$this->table_name} r 
                  JOIN user u ON u.user_id = r.user_id
                  JOIN book b ON b.book_id = r.book_id
                --   JOIN author a ON b.author_id = a.author_id
                  WHERE reservation_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id)
    {
        return parent::update($id);
    }

    public function delete($id)
    {
        return parent::delete($id);
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE `" . $this->table_name . "` SET status = ? WHERE reservation_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(1, $status);
        $stmt->bindValue(2, $id);
        return $stmt->execute();
    }

    public function readByUserId($user_id) {
        $query = "SELECT * FROM {$this->table_name} r 
                  JOIN user u ON u.user_id = r.user_id
                  JOIN book b ON b.book_id = r.book_id 
                  WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function checkBookReserved($book_id) {
        $query = "SELECT COUNT(*) FROM {$this->table_name} WHERE book_id = ? AND status = 'active'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $book_id, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count > 0;
    }
}
?>