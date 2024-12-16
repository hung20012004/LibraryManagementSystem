<?php
include_once 'models/Book.php';
include_once 'models/book_condition.php';
include_once 'models/User.php';

class BookConditionController extends Controller
{
    private $book;
    private $book_condition;

    private $user;

    public function __construct()
    {
        $this->book = new Book();
        $this->book_condition = new Condition();
        $this->user = new User();
    }

    public function index()
    {
        $book_conditions = $this->book_condition->read();
            //     echo '<pre>';
            //     // var_dump($books);
            //     var_dump($book_conditons);
            // //    var_dump($users);
            //     echo '</pre>';
            //    die(); 
        $content = 'views/book_condition/index.php';
        include('views/layouts/base.php');
    }


    public function create() 
    {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                //    echo '<pre>';
                //     var_dump($_POST);
                //     echo '</pre>';
                //     die(); 

                // Set book properties from POST data
                foreach ($_POST as $key => $value) {
                    if (property_exists($this->book_condition, $key)) {
                        $this->book_condition->$key = strip_tags(trim($value));
                    }
                }

                if ($this->book_condition->create()) {
                    $_SESSION['message'] = 'Thêm phiếu kiểm tra sách thành công!';
                    $_SESSION['message_type'] = 'success';
                    header("Location: index.php?model=book_condition&action=index");
                    exit();
                } else {
                    throw new Exception('Thêm phiếu kiểm tra sách không thành công.');
                }
                
            } catch (Exception $e) {
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }
        }
        // var_dump($publishers);
        // var_dump($authors);
        // var_dump($categories);
        // die();
        $books=$this->book->getUnassessedBooks();
        $loans=$this->book_condition->getLoans();
        // var_dump($books);
        // var_dump($loans);
        // die();
        $content = 'views/book_condition/create.php';
        include('views/layouts/base.php');
    }

    public function edit($id)
    {
        $this->book_condition = new Condition();
        $conditionData = $this->book_condition->readById($id);

        // Kiểm tra dữ liệu phiếu mượn
        if (!$conditionData) {
            $_SESSION['message'] = "Không tìm thấy dữ liệu cho ID: $id";
            $_SESSION['message_type'] = 'danger';
            header("Location: index.php?model=book_condition&action=index");
            exit();
        }

        // Kiểm tra quyền của người dùng hiện tại
        if ($_SESSION['user_id'] != $conditionData['assessed_by']) {
            $_SESSION['message'] = "Bạn không có quyền chỉnh sửa phiếu này.";
            $_SESSION['message_type'] = 'danger';
            header("Location: index.php?model=book_condition&action=index");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Duyệt qua dữ liệu từ form và gán giá trị
                foreach ($_POST as $key => $value) {
                    if (property_exists($this->book_condition, $key)) {
                        $this->book_condition->$key = strip_tags(trim($value));
                    }
                }

                // Nếu condition_after là Intact, xóa nội dung damage_description
                if ($this->book_condition->condition_after === 'Intact') {
                    $this->book_condition->damage_description = '';
                }

                // Thực hiện cập nhật dữ liệu
                if ($this->book_condition->update($id)) {
                    $_SESSION['message'] = 'Cập nhật phiếu thành công!';
                    $_SESSION['message_type'] = 'success';
                    header("Location: index.php?model=book_condition&action=index");
                    exit();
                } else {
                    throw new Exception('Cập nhật phiếu không thành công.');
                }
            } catch (Exception $e) {
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }
        }

        // Lấy danh sách sách và phiếu mượn
        $books = $this->book->getUnassessedBooks() ?: [];
        $loans = $this->book_condition->getLoans() ?: [];

        // Dữ liệu truyền vào view
        $book_condition = $conditionData;
        $content = 'views/book_condition/edit.php';
        include('views/layouts/base.php');
    }


    public function delete($id)
    {
        try {
            // Kiểm tra quyền của người dùng hiện tại
            if ($_SESSION['role_id'] !== 1) {
                throw new Exception('Bạn không có quyền xóa sách.');
            }

            // Thực hiện xóa sách nếu người dùng là admin
            if ($this->book_condition->delete($id)) {
                $_SESSION['message'] = 'Xóa phiếu thành công!';
                $_SESSION['message_type'] = 'success';
            } else {
                throw new Exception('Xóa sách không thành công.');
            }
        } catch (Exception $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = 'danger';
        }

        header("Location: index.php?model=book_condition&action=index");
        exit();
    }
   
}
?>