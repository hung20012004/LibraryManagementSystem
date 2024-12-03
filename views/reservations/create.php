<div class="container-fluid">
    <div class="row mt-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?model=reservation&action=index">Quản lý đặt sách</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tạo phiếu đặt sách</li>
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
                        <h5 class="card-title mb-0">Tạo phiếu đặt sách</h5>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <form action="index.php?model=reservation&action=create" method="POST">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="book_id" class="form-label">Chọn Sách:</label>
                                <select name="book_id" id="book_id" class="form-control" required>
                                    <?php foreach ($books as $book): ?>
                                        <option value="<?= $book['book_id'] ?>"><?= htmlspecialchars($book['title']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="reservation_date" class="form-label">Ngày Đặt:</label>
                                <input type="text" name="reservation_date" id="reservation_date" class="form-control" 
                                       value="<?= date('d-m-Y') ?>" readonly>
                            </div>
                            <div class="col">
                                <label for="expiry_date" class="form-label">Ngày Hết Hạn:</label>
                                <input type="text" name="expiry_date" id="expiry_date" class="form-control" 
                                       value="<?= date('d-m-Y', strtotime('+3 days')) ?>" readonly>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Ghi Chú:</label>
                            <textarea name="notes" id="notes" class="form-control" required
                                      placeholder="Vui lòng điền đầy đủ: Họ và Tên, Lớp, Mã SV (Bắt buộc)"></textarea>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="index.php?model=reservation&action=index" class="btn btn-secondary"> 
                            <i class="fa-solid fa-arrow-left"></i> Quay lại
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fa-regular fa-floppy-disk"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
