<?php
include_once 'models/Loan.php';

class LoanController extends Controller
{
    private $loan;

    public function __construct()
    {
        $this->loan = new Loan();
    }

    // Hiển thị danh sách phiếu mượn
    public function index()
    {
        $loans = $this->loan->getAllandUserName();
        $content = 'views/loans/index.php';
        include('views/layouts/base.php');
    }

    // Hiển thị chi tiết phiếu mượn
    public function show($id)
    {
        if (!isset($_GET['id'])) {
            $_SESSION['message'] = 'Không tìm thấy mã phiếu!';
            $_SESSION['message_type'] = 'danger';
            header('Location: index.php?model=loan&action=index');
            exit;
        }

        $loanId = $_GET['id'];
        $loan = $this->loan->getById($loanId);
        $books = $this->loan->getBooksByLoanId($loanId);

        if (!$loan) {
            $_SESSION['message'] = 'Phiếu mượn không tồn tại!';
            $_SESSION['message_type'] = 'danger';
            header('Location: index.php?model=loan&action=index');
            exit;
        }

        $content = 'views/loans/show.php';
        include('views/layouts/base.php');
    }

    // Cập nhật trạng thái phiếu mượn và trạng thái sách
public function update_status($status = null, $returnDate = null)
{
    if (!isset($_GET['id'])) {
        $_SESSION['message'] = 'Không tìm thấy mã phiếu!';
        $_SESSION['message_type'] = 'danger';
        header('Location: index.php?model=loan&action=index');
        exit;
    }

    $loanId = $_GET['id'];

    if (isset($_GET['status'])) {
        $status = $_GET['status'];
    } else {
        $status = null; 
    }

    if ($status === null) {
        $_SESSION['message'] = 'Trạng thái không hợp lệ!';
        $_SESSION['message_type'] = 'danger';
        header('Location: index.php?model=loan&action=index');
        exit;
    }

    // Xử lý khi trạng thái là null (phê duyệt)
    if ($status != 'issued' and $status != 'overdue' and $status != 'returned') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $this->loan->getBooksByLoanId($loanId);
            $allBooks = array_column($stmt, 'book_id');
    
            foreach ($allBooks as $bookId) {
                try {
                    if (isset($_POST['books'][$bookId])) {
                        // Nếu sách được chọn, cập nhật trạng thái sách
                        $this->loan->updateBookStatusInLoanDetail($loanId, $bookId, 'issued');
                    } else {
                        // Nếu sách không được chọn, chuyển sang bảng hẹn và xóa khỏi phiếu mượn
                        $this->loan->reserveBook($loanId, $bookId);
                        $this->loan->deleteBookFromLoan($loanId, $bookId);
                    }
                } catch (Exception $e) {
$_SESSION['message'] = 'Lỗi khi cập nhật: ' . $e->getMessage();
                    $_SESSION['message_type'] = 'danger';
                    header('Location: index.php?model=loan&action=show&id=' . $loanId);
                    exit;
                }
            }
        }
    }

    // Xử lý khi trạng thái là returned (trả sách)
    if ($status === 'returned') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $this->loan->getBooksByLoanId($loanId);
            $allBooks = array_column($stmt, 'book_id');
    
            $returnDate = date('Y-m-d H:i:s'); // Ngày trả là ngày hiện tại

            foreach ($allBooks as $bookId) {
                try {
                    if (isset($_POST['books'][$bookId])) {
                        // Lấy trạng thái sách từ POST
                        $bookStatus = $_POST['books'][$bookId];
                        
                        // Cập nhật trạng thái sách trong loan_detail
                        $this->loan->updateBookStatusInLoanDetail($loanId, $bookId, $bookStatus);
                        
                        // Cập nhật lại trạng thái sách trong bảng book
                        $this->loan->updateBookAvailability($bookId, $bookStatus);
                    }
                } catch (Exception $e) {
                    $_SESSION['message'] = 'Lỗi khi cập nhật: ' . $e->getMessage();
                    $_SESSION['message_type'] = 'danger';
                    header('Location: index.php?model=loan&action=show&id=' . $loanId);
                    exit;
                }
            }
        }
    }

    // Cập nhật trạng thái phiếu mượn
    $result = $this->loan->updateStatus($loanId, $status, $returnDate);

    if ($result) {
        $_SESSION['message'] = 'Cập nhật trạng thái phiếu mượn thành công!';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Cập nhật trạng thái thất bại!';
        $_SESSION['message_type'] = 'danger';
    }

    header('Location: index.php?model=loan&action=index');
    exit;
}
    public function updateBookStatus($loanId, $bookId, $status)
    {
        $result = $this->loan->updateBookStatusInLoanDetail($loanId, $bookId, $status);
        if ($result) {
            echo "Trạng thái sách đã được cập nhật thành công!";
        } else {
            echo "Cập nhật trạng thái sách thất bại!";
        }
    }
}
?>