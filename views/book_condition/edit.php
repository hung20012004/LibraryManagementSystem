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
                    <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa phiếu</li>
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
                    <h5 class="card-title mb-0">Chỉnh sửa phiếu kiểm tra</h5>
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
                    <form action="index.php?model=book_condition&action=edit&id=<?= htmlspecialchars($book_condition['condition_id']); ?>" method="POST" >
                              
                            <!-- Tiêu đề sách -->
                            <div class="mb-3">
                                <label for="book_id" class="form-label">Tên sách:</label>
                                <select class="form-control" id="book_title" disabled>
                                    <?php foreach ($books as $book): ?>
                                        <option value="<?= htmlspecialchars($book['book_id']); ?>" <?= $book['book_id'] == $book_condition['book_id'] ? 'selected' : ''; ?>>
                                            <?= htmlspecialchars($book['book_title']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <!-- Input ẩn để gửi book_id -->
                                <input type="hidden" name="book_id" value="<?= htmlspecialchars($book_condition['book_id']); ?>">
                            </div>

                    <!-- ID phiếu mượn -->
                    <div class="mb-3">
                        <label for="loan_id" class="form-label">ID Phiếu mượn:</label>
                        <input type="text" class="form-control" id="loan_id" 
                            value="<?= htmlspecialchars($book_condition['loan_id']); ?>" readonly>
                            <input type="hidden" name="loan_id" value="<?= htmlspecialchars($book_condition['loan_id']); ?>">
                    </div>

                    <!-- Tình trạng trước khi mượn -->
                    <div class="mb-3">
                        <label for="condition_before" class="form-label">Tình trạng trước khi mượn:</label>
                        <input type="text" class="form-control" id="condition_before" readonly value="<?= htmlspecialchars($book_condition['condition_before']); ?>"></input>
                    </div>

                    <div class="mb-3">
                        <label for="condition_after" class="form-label">Tình trạng sau khi mượn:</label>
                        <select name="condition_after" id="condition_after" class="form-control" required onchange="toggleDamageDescription()">
                            <option value="Intact" <?= $book_condition['condition_after'] == 'Intact' ? 'selected' : ''; ?>>Intact</option>
                            <option value="Damaged" <?= $book_condition['condition_after'] == 'Damaged' ? 'selected' : ''; ?>>Damaged</option>
                        </select>
                    </div>

                    <!-- Chi tiết hư hại -->
                    <div class="mb-3">
                        <label for="damage_description" class="form-label">Chi tiết hư hại:</label>
                        <textarea name="damage_description" id="damage_description" class="form-control" rows="3"><?= htmlspecialchars($book_condition['damage_description']); ?></textarea>
                    </div>

                    <!-- Ngày kiểm tra -->
                    <div class="mb-3">
                        <label for="assessed_date" class="form-label">Ngày kiểm tra:</label>
                        <input type="date" name="assessed_date" id="assessed_date" class="form-control" 
                            value="<?= htmlspecialchars($book_condition['assessed_date']); ?>" required readonly>
                    </div>

                    <!-- Ghi chú -->
                    <div class="mb-3">
                        <label for="notes" class="form-label">Ghi chú:</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3"><?= htmlspecialchars($book_condition['notes']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="assessed_by" class="form-label">Người kiểm tra:</label>
                        <input name="assessed_by_display" id="assessed_by" class="form-control" 
                            value="<?= htmlspecialchars($_SESSION['full_name'] ?? 'Chưa đăng nhập'); ?>" 
                            disable>
                        <input type="hidden" name="assessed_by" value="<?= htmlspecialchars($_SESSION['user_id']); ?>">
                    </div>

                            <div class="card-footer d-flex justify-content-between">
                            <a href="index.php?model=book_condition&action=index" class="btn btn-secondary">
                                <i class="fa-solid fa-arrow-left"></i> Trở lại
                            </a>
                            <button type="button" id="toggleEdit" class="btn btn-primary">
                                <i class="fa-solid fa-pencil"></i>
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fa-regular fa-floppy-disk"></i> Cập nhật
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
echo 'const selectedLoanId = ' . json_encode($bookCondition['loan_id']) . ';';
echo '</script>';
?>
<?php
// Lấy ID của người kiểm tra và ID người dùng hiện tại từ session
$assessedById = $book_condition['assessed_by']; // ID người kiểm tra trong bản ghi
$currentUserId = $_SESSION['user_id']; // ID người dùng hiện tại
?>
<script>
 const assessedById = <?= json_encode($assessedById); ?>; // ID người kiểm tra
 const currentUserId = <?= json_encode($currentUserId); ?>; // ID người dùng hiện tại
// Tải dữ liệu loan_id tương ứng khi chỉnh sửa
const loanSelect = document.getElementById('loan_id');
const selectedBookId = document.getElementById('book_id').value;

if (bookLoanMap[selectedBookId]) {
    bookLoanMap[selectedBookId].forEach(loanId => {
        const option = document.createElement('option');
        option.value = loanId;
        option.textContent = loanId;
        if (loanId == selectedLoanId) {
            option.selected = true;
        }
        loanSelect.appendChild(option);
    });
}

function toggleDamageDescription() {
    const conditionAfter = document.getElementById("condition_after").value;
    const damageDescription = document.getElementById("damage_description");

    if (conditionAfter === "Intact") {
        damageDescription.disabled = true;
        damageDescription.required = false;
        damageDescription.value = ""; // Xóa nội dung nếu không cần nhập
    } else {
        damageDescription.disabled = false;
        damageDescription.required = true;
    }
}

// Đảm bảo chạy khi trang vừa tải
document.addEventListener("DOMContentLoaded", () => {
    toggleDamageDescription();
});

document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const toggleEditBtn = document.getElementById('toggleEdit');
    const saveChangesBtn = document.getElementById('saveChanges');
    const allInputs = document.querySelectorAll('input, select, textarea');

    // Disable all inputs initially
    allInputs.forEach(input => {
        input.disabled = true;
    });

    // Kiểm tra quyền chỉnh sửa
    const canEdit = currentUserId === assessedById;

    if (!canEdit) {
        // Ẩn nút chỉnh sửa nếu không có quyền
        toggleEditBtn.style.display = 'none';
    } else {
        // Hiển thị nút chỉnh sửa nếu có quyền
        toggleEditBtn.addEventListener('click', function () {
            // Thay đổi trạng thái giữa chế độ chỉnh sửa và xem
            const isDisabled = allInputs[0].disabled;

            if (isDisabled) {
                // Chuyển sang chế độ chỉnh sửa
                toggleEditBtn.style.display = 'none';
                saveChangesBtn.style.display = 'block';
                allInputs.forEach(input => {
                    input.disabled = false;
                });
            } else {
                // Chuyển về chế độ xem
                toggleEditBtn.style.display = 'block';
                saveChangesBtn.style.display = 'none';
                allInputs.forEach(input => {
                    input.disabled = true;
                });
            }
        });
    }
});
</script>
