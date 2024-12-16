<?php
include_once 'Model.php';
class Loan extends Model
{
    protected $table_name = 'loan';

    public $loan_id;
    public $book_id;
    public $issued_by;
    public $returned_to;
    public $issued_date;
    public $due_date;
    public $returned_date;
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
        $query = "SELECT * FROM {$this->table_name}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readById($id) {
        $query = "SELECT * FROM {$this->table_name} WHERE loan_id = :id";
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


}