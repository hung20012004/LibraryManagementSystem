<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row my-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 py-2" style="background-color: #f8f9fc;">
                    <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="index.php?model=loans">Quản lý phiếu mượn</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Thêm/Sửa Phiếu Mượn</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Form Thêm/Sửa Phiếu Mượn -->
    <div class="card shadow mb-4">
        <div class="card-header py-2">
            <h5 class="card-title mb-0">Phiếu Mượn Sách</h5>
        </div>
        <div class="card-body">
            <form action="index.php?model=loans&action=save" method="POST">
                <!-- ID Phiếu Mượn -->
                <input type="hidden" name="loan_id" value="<?= isset($loan['loan_id']) ? $loan['loan_id'] : '' ?>">

                <!-- Thông tin sách -->
                <div class="mb-3">
                    <label for="book_id" class="form-label">Sách</label>
                    <select name="book_id" id="book_id" class="form-select" required>
                        <option value="" disabled selected>Chọn sách</option>
                        <?php foreach ($books as $book): ?>
                            <option value="<?= $book['book_id'] ?>" <?= isset($loan['book_id']) && $loan['book_id'] == $book['book_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($book['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Thông tin thành viên -->
                <div class="mb-3">
                    <label for="member_id" class="form-label">Thành viên</label>
                    <select name="member_id" id="member_id" class="form-select" required>
                        <option value="" disabled selected>Chọn thành viên</option>
                        <?php foreach ($members as $member): ?>
                            <option value="<?= $member['member_id'] ?>" <?= isset($loan['member_id']) && $loan['member_id'] == $member['member_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($member['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Ngày mượn -->
                <div class="mb-3">
                    <label for="issued_date" class="form-label">Ngày mượn</label>
                    <input type="datetime-local" name="issued_date" id="issued_date" class="form-control"
                        value="<?= isset($loan['issued_date']) ? date('Y-m-d\TH:i', strtotime($loan['issued_date'])) : '' ?>">
                </div>

                <!-- Ngày trả -->
                <div class="mb-3">
                    <label for="due_date" class="form-label">Ngày phải trả</label>
                    <input type="datetime-local" name="due_date" id="due_date" class="form-control" required
                        value="<?= isset($loan['due_date']) ? date('Y-m-d\TH:i', strtotime($loan['due_date'])) : '' ?>">
                </div>

                <!-- Ghi chú -->
                <div class="mb-3">
                    <label for="notes" class="form-label">Ghi chú</label>
                    <textarea name="notes" id="notes" rows="4" class="form-control"><?= isset($loan['notes']) ? htmlspecialchars($loan['notes']) : '' ?></textarea>
                </div>

                <!-- Trạng thái -->
                <div class="mb-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select name="status" id="status" class="form-select">
                        <option value="borrowed" <?= isset($loan['status']) && $loan['status'] == 'borrowed' ? 'selected' : '' ?>>Đang mượn</option>
                        <option value="returned" <?= isset($loan['status']) && $loan['status'] == 'returned' ? 'selected' : '' ?>>Đã trả</option>
                        <option value="overdue" <?= isset($loan['status']) && $loan['status'] == 'overdue' ? 'selected' : '' ?>>Quá hạn</option>
                        <option value="lost" <?= isset($loan['status']) && $loan['status'] == 'lost' ? 'selected' : '' ?>>Mất</option>
                    </select>
                </div>

                <!-- Người thực hiện -->
                <div class="mb-3">
                    <label for="issued_by" class="form-label">Người cho mượn</label>
                    <select name="issued_by" id="issued_by" class="form-select" required>
                        <option value="" disabled selected>Chọn nhân viên</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['user_id'] ?>" <?= isset($loan['issued_by']) && $loan['issued_by'] == $user['user_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($user['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Nút hành động -->
                <div class="d-flex justify-content-between">
                    <a href="index.php?model=loans" class="btn btn-secondary">Hủy</a>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>
