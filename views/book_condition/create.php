<?php
// Kiểm tra session
//echo '<pre>';
//var_dump($_SESSION);
//echo '</pre>';
?>
<div class="container-fluid">
    <div class="row mt-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?model=book_condition&action=index">Quản lý phiếu kiểm tra sách</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tạo phiếu mới</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="container">
    <div class="row justify-content-center mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tạo phiếu mới</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['message'])): ?>
                        <div id="alert-message" class="alert alert-<?= $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                            <?= $_SESSION['message']; ?>
                        </div>
                        <?php   
                        unset($_SESSION['message']);
                        unset($_SESSION['message_type']);
                        ?>
                    <?php endif; ?>
                    <form action="index.php?model=book_condition&action=create" method="POST" >
                              
                    <div class="mb-3">
                        <label for="book_id" class="form-label">Tiêu đề sách:</label>
                        <select name="book_id" id="book_id" class="form-control" required>
                            <option value="">Chọn sách</option>
                            <?php foreach ($books as $book): ?>
                                <option value="<?= htmlspecialchars($book['book_id']); ?>">
                                    <?= htmlspecialchars($book['book_title']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="loan_id" class="form-label">ID phiếu mượn:</label>
                        <select name="loan_id" id="loan_id" class="form-control" required>
                            <option value="">Chọn phiếu mượn</option>
                        </select>
                    </div>


                        <div class="mb-3">
                            <label for="condition_before" class="form-label">Tình trạng sách trước khi mượn:</label>
                            <select name="condition_before" id="condition_before" class="form-control" required>
                                <option value="Perfect">Perfect</option>
                                <option value="Damaged">Damaged</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="condition_after" class="form-label">Tình trạng sách sau khi mượn:</label>
                            <select name="condition_after" id="condition_after" class="form-control" required onchange="toggleDamageDescription()">
                                <option value="Intact">Intact</option>
                                <option value="Damaged">Damaged</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="damage_description" class="form-label">Chi tiết hư hại:</label>
                            <textarea name="damage_description" id="damage_description" class="form-control" rows="3" disabled></textarea>
                            </div>
                        
                        <div class="mb-3">
                            <label for="assessed_date" class="form-label">Ngày kiểm tra:</label>
                            <input  class="form-control" 
                                type="date" 
                                id="assessed_date" 
                                name="assessed_date" 
                                value="<?php echo date('Y-m-d'); ?>" 
                                readonly
                            >
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Ghi chú:</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="assessed_by" class="form-label">Người kiểm tra:</label>
                                <input name="assessed_by" id="assessed_by" class="form-control" 
                                    placeholder="<?= htmlspecialchars($_SESSION['full_name'] ?? 'Chưa đăng nhập'); ?>" 
                                    readonly>
                                <input type="hidden" name="assessed_by" value="<?= htmlspecialchars($_SESSION['user_id']); ?>">
                            </div>

                            <div class="card-footer d-flex justify-content-between">
                            <a href="index.php?model=book&action=index" class="btn btn-secondary">
                                <i class="fa-solid fa-arrow-left"></i> Trở lại
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fa-regular fa-floppy-disk"></i> Lưu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Chuẩn bị danh sách liên kết book_id -> loan_id
$bookLoanMap = [];
foreach ($loans as $loan) {
    $bookId = $loan['book_id'];
    $loanId = $loan['loan_id'];
    if (!isset($bookLoanMap[$bookId])) {
        $bookLoanMap[$bookId] = [];
    }
    $bookLoanMap[$bookId][] = $loanId;
}

// Truyền dữ liệu sang JavaScript
echo '<script>';
echo 'const bookLoanMap = ' . json_encode($bookLoanMap) . ';';
echo '</script>';
?>

<script>

document.getElementById('book_id').addEventListener('change', function() {
    const selectedBookId = this.value;
    const loanSelect = document.getElementById('loan_id');

    // Tìm loan_id tương ứng
    loanSelect.value = bookLoanMap[selectedBookId] || '';
});

function toggleDamageDescription() {
    const conditionAfter = document.getElementById("condition_after").value;
    const damageDescription = document.getElementById("damage_description");

    // Kích hoạt trường nhập nếu tình trạng là 'Damaged', ngược lại vô hiệu hóa
    if (conditionAfter === "Damaged") {
        damageDescription.disabled = false; // Bật trường nhập
        damageDescription.required = true; // Yêu cầu người dùng nhập
    } else {
        damageDescription.disabled = true; // Tắt trường nhập
        damageDescription.required = false; // Không bắt buộc nhập
        damageDescription.value = ""; // Xóa nội dung nếu có
    }
}



document.getElementById('book_id').addEventListener('change', function () {
    const selectedBookId = this.value;
    const loanSelect = document.getElementById('loan_id');

    // Xóa tất cả các option hiện có trong loan_id
    loanSelect.innerHTML = '<option value="">Chọn phiếu mượn</option>';

    // Kiểm tra nếu có loan_id liên kết với book_id được chọn
    if (bookLoanMap[selectedBookId]) {
        bookLoanMap[selectedBookId].forEach(loanId => {
            const option = document.createElement('option');
            option.value = loanId;
            option.textContent = loanId;
            loanSelect.appendChild(option);
        });
    }
});
</script>
