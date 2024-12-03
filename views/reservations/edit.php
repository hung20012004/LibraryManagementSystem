<div class="container-fluid">
    <div class="row mt-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?model=reservation&action=index">Quản lý đặt sách</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chi tiết phiếu đặt sách</li>
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
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Chi tiết phiếu đặt sách</h5>
                    </div>
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
                        <script>
                            setTimeout(function() {
                                var alert = document.getElementById('alert-message');
                                if (alert) {
                                    alert.classList.remove('show');
                                    alert.classList.add('fade');
                                    setTimeout(function() {
                                        alert.style.display = 'none';
                                    }, 150); 
                                }
                            }, 2000);
                        </script>
                    <?php endif; ?>

                    <form action="index.php?model=reservation&action=edit&id=<?= $reservation['reservation_id']; ?>" method="POST">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="creator" class="form-label">Người tạo phiếu:</label>
                                <input type="text" name="creator" id="creator" class="form-control" value="<?= htmlspecialchars($reservation['full_name']); ?>" >
                            </div>
                            <div class="col-md-6">
                                <label for="reservation_date" class="form-label">Ngày đặt:</label>
                                <input type="date" name="reservation_date" id="reservation_date" class="form-control" value="<?= htmlspecialchars($reservation['reservation_date']); ?>" >
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="expiry_date" class="form-label">Ngày hết hạn:</label>
                                <input type="date" name="expiry_date" id="expiry_date" class="form-control" value="<?= htmlspecialchars($reservation['expiry_date']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="fulfilled_date" class="form-label">Ngày hoàn tất:</label>
                                <input type="date" name="fulfilled_date" id="fulfilled_date" class="form-control" value="<?= htmlspecialchars($reservation['fulfilled_date']); ?>">
                            </div>
                        </div>
                        <div class="row-md-6">
                            <label for="notes" class="form-label">Ghi chú:</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3"><?= htmlspecialchars($reservation['notes']); ?></textarea>
                        </div>
                        <div class="row-md-6" style = "margin-top: 20px">
                            <h6>Danh sách sách đã đặt</h6>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tên sách</th>
                                        <th>Tình trạng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?= htmlspecialchars($reservation['title']); ?></td>
                                        <td><?= htmlspecialchars($reservation['book_status'] === 'available' ? 'Có sẵn' : 'Hết sách'); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row-md-6">
                            <label for="status" class="form-label">Tình trạng:</label>
                            <select name="status" id="status" class="form-control">
                                <option value="pending" <?= $reservation['status'] === 'pending' ? 'selected' : '' ?>>Đang xử lý</option>
                                <option value="confirmed" <?= $reservation['status'] === 'confirmed' ? 'selected' : '' ?>>Đã xác nhận</option>
                                <option value="completed" <?= $reservation['status'] === 'fulfilled' ? 'selected' : '' ?>>Hoàn thành</option>
                                <option value="expired" <?= $reservation['status'] === 'expired' ? 'selected' : '' ?>>Hết hạn</option>
                                <option value="canceled" <?= $reservation['status'] === 'canceled' ? 'selected' : '' ?>>Bị hủy</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="index.php?model=reservation&action=index" class="btn btn-secondary">
                            <i class="fa-solid fa-arrow-left"></i> Quay lại
                        </a>
                        <button type="button" id="toggleEdit" class="btn btn-primary">
                            <i class="fa-solid fa-pencil"></i>
                        </button>
                        <button type="submit" id="saveChanges" class="btn btn-success" style="display: none;"> 
                            <i class="fa-regular fa-floppy-disk"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const roleSelect = document.getElementById('role_id');
    const toggleEditBtn = document.getElementById('toggleEdit');
    const saveChangesBtn = document.getElementById('saveChanges');
    const allInputs = document.querySelectorAll('input, select, textarea');

    // Disable all inputs initially
    allInputs.forEach(input => {
        input.disabled = true;
    });

    // Toggle edit mode
    toggleEditBtn.addEventListener('click', function() {
        // Thay đổi logic kiểm tra trạng thái
        const isDisabled = allInputs[0].disabled;
        
        if (isDisabled) {
            // Chuyển sang chế độ edit
            toggleEditBtn.style.display = 'none';
            saveChangesBtn.style.display = 'block';
            // Enable all inputs
            allInputs.forEach(input => {
                input.disabled = false;
            });
        } else {
            // Chuyển sang chế độ view
            toggleEditBtn.style.display = 'block';
            saveChangesBtn.style.display = 'none';
            // Disable all inputs
            allInputs.forEach(input => {
                input.disabled = true;
            });
        }
    });
});

</script>