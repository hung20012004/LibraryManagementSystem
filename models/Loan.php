<?php
include_once 'Model.php';

class Loan extends Model
{
    protected $table_name = 'loan';

    public $loan_id;
    public $book_id;
    public $member_id;
    public $issued_date;
    public $due_date;
    public $notes;
    public $status;
    public $issued_by;
    public $created_at;
    public $updated_at;

    public function __construct()
    {
        parent::__construct();
    }

    // Tạo phiếu mượn
    public function create()
    {
        return parent::create();
    }

    // Đọc tất cả phiếu mượn
    public function read()
    {
        $query = "SELECT * FROM {$this->table_name}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin phiếu mượn theo ID
    public function readById($id)
    {
        $query = "SELECT * FROM {$this->table_name} WHERE loan_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllandUserName()
    {
        $query = "
            SELECT loan.*, user.username AS user_name
            FROM {$this->table_name} AS loan
            JOIN user ON loan.user_id = user.user_id
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT 
                    l.loan_id,
                    l.issued_by,
                    l.returned_to,
                    l.issued_date,
                    l.due_date,
                    l.returned_date,
                    l.status,
                    l.notes,
                    l.created_at,
                    l.updated_at,
                    l.user_id,
                    u.username AS borrower_name
                FROM loan l
                LEFT JOIN user u ON l.user_id = u.user_id
                WHERE l.loan_id = :id";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBooksByLoanId($loanId)
{
    $query = "
        SELECT ld.book_id, b.title AS book_title, b.quantity as book_quantity, ld.status, ld.quantity, ld.notes
        FROM loan_detail ld
        JOIN book b ON ld.book_id = b.book_id
        WHERE ld.loan_id = :loan_id
    ";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':loan_id', $loanId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

  // Cập nhật trạng thái phiếu mượn (issued, returned, overdue)
  public function updateStatus($loanId, $status, $returnDate = null)
{
    $validStatuses = ['issued', 'returned', 'overdue'];
    if (!in_array($status, $validStatuses)) {
        return false; // Trạng thái không hợp lệ
    }

    $query = "UPDATE {$this->table_name} 
              SET status = :status, 
                  updated_at = NOW()";

    if ($status === 'returned' && $returnDate) {
        $query .= ", returned_date = :returned_date";
    }

    $query .= " WHERE loan_id = :loan_id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmt->bindParam(':loan_id', $loanId, PDO::PARAM_INT);

    if ($status === 'returned' && $returnDate) {
        $stmt->bindParam(':returned_date', $returnDate, PDO::PARAM_STR);
    }

    return $stmt->execute();
}

public function delete($id)
    {
        return parent::delete($id);
    }

    public function updateBookStatusInLoanDetail($loanId, $bookId, $status)
{
    $sql = "UPDATE loan_detail SET status = :status WHERE loan_id = :loanId AND book_id = :bookId";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':status', $status,  PDO::PARAM_STR);
    $stmt->bindParam(':loanId', $loanId, PDO::PARAM_INT);
    $stmt->bindParam(':bookId', $bookId, PDO::PARAM_INT);
    return $stmt->execute();
}

    // 2. Chuyển sách vào bảng reservations
    public function reserveBook($loanId, $bookId)
    {
        $getUserQuery = "SELECT user_id FROM loan WHERE loan_id = :loan_id";
        $userStmt = $this->conn->prepare($getUserQuery);
        $userStmt->bindParam(':loan_id', $loanId, PDO::PARAM_INT);
        $userStmt->execute();
        $userData = $userStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$userData) {
            return false; // Không tìm thấy user_id
        }
        
        $userId = $userData['user_id'];
    
        $query = "INSERT INTO reservation (
            book_id, 
            user_id, 
            reservation_date, 
            status
        ) VALUES (
            :book_id, 
            :user_id, 
            NOW(), 
            'pending'
        )";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':book_id', $bookId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    // 3. Xóa sách khỏi loan_books
    public function deleteBookFromLoan($loanId, $bookId)
    {
        $query = "DELETE FROM loan_detail WHERE loan_id = :loan_id AND book_id = :book_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':loan_id', $loanId, PDO::PARAM_INT);
        $stmt->bindParam(':book_id', $bookId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updateBookAvailability($bookId, $status)
{
    $query = "";
    switch($status) {
        case 'returned':
            $query = "UPDATE book SET status = 'available', quantity = quantity + 1 WHERE book_id = :book_id";
            break;
        case 'lost':
            $query = "UPDATE book SET status = 'lost' WHERE book_id = :book_id";
            break;
        case 'damaged':
            $query = "UPDATE book SET status = 'damaged' WHERE book_id = :book_id";
            break;
    }

    if (!empty($query)) {
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':book_id', $bookId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    return false;
}
}