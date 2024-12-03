<?php
include_once 'models/Reservation.php';
include_once 'models/Book.php';

class ReservationController extends Controller
{
    private $reservation;
    private $book;

    public function __construct()
    {
        $this->reservation = new Reservation();
        $this->book = new Book();
    }

    public function index()
    {
        $reservations = $this->reservation->read();
        $content = 'views/reservations/index.php';
        include('views/layouts/base.php');
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Lưu dữ liệu form vào session trước khi xử lý
                $_SESSION['form_data'] = $_POST;

                foreach ($_POST as $key => $value) {
                    if (property_exists($this->reservation, $key)) {
                        $this->reservation->$key = strip_tags(trim($value));
                    }
                }

                $this->reservation->book_id = $_POST['book_id'];
                $this->reservation->user_id = $_SESSION['user_id'];
                $this->reservation->reservation_date = date('Y-m-d');
                $this->reservation->expiry_date = date('Y-m-d', strtotime('+3 days'));
                $this->reservation->notes = $_POST['notes'];
                $this->reservation->status = 'pending';

                // var_dump($this->reservation);
                // exit();
                if ($this->reservation->create()) {
                    $_SESSION['message'] = 'Tạo phiếu đặt sách thành công!';
                    $_SESSION['message_type'] = 'success';
                    unset($_SESSION['form_data']); // Xóa dữ liệu form sau khi thành công
                    header("Location: index.php?model=reservation&action=index");
                    exit();
                } else {
                    throw new Exception('Không thể tạo phiếu đặt sách');
                }
            } catch (Exception $e) {
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }
        }

        $books = $this->book->read();
        $content = 'views/reservations/create.php';
        include('views/layouts/base.php');
    }

    public function edit($id)
    {
        $reservationData = $this->reservation->readById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                foreach ($_POST as $key => $value) {
                    if (property_exists($this->reservation, $key)) {
                        $this->reservation->$key = strip_tags(trim($value));
                    }
                }

                $this->reservation->book_id = $_POST['book_id'];
                $this->reservation->user_id = $_SESSION['user_id'];
                $this->reservation->reservation_date = $_POST['reservation_date'];
                $this->reservation->expiry_date = date('Y-m-d', strtotime($_POST['reservation_date'] . ' +3 days'));
                $this->reservation->notes = $_POST['notes'];
                $this->reservation->status = $_POST['status'];

                if ($this->reservation->update($id)) {
                    $_SESSION['message'] = 'Cập nhật phiếu đặt sách thành công!';
                    $_SESSION['message_type'] = 'success';
                    header("Location: index.php?model=reservation&action=index");
                    exit();
                } else {
                    throw new Exception('Không thể cập nhật phiếu đặt sách');
                }
            } catch (Exception $e) {
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }
        }

        $reservation = $reservationData;
        $content = 'views/reservations/edit.php';
        include('views/layouts/base.php');
    }

    public function delete($id)
    {
        try {
            if ($this->reservation->delete($id)) {
                $_SESSION['message'] = 'Xóa phiếu đặt sách thành công!';
                $_SESSION['message_type'] = 'success';
            } else {
                throw new Exception('Không thể xóa phiếu đặt sách');
            }
        } catch (Exception $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = 'danger';
        }

        header("Location: index.php?model=reservation&action=index");
        exit();
    }

    public function statistics()
    {
        // Ví dụ logic thống kê: Lấy tổng số đặt chỗ
        try {
            $reservations = $this->reservation->read();
            $totalReservations = count($reservations);
            $content = 'views/reservations/statistics.php';
            include('views/layouts/base.php');
        } catch (Exception $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = 'danger';
            header("Location: index.php?model=reservation&action=index");
        }
    }
}
?>
