<div class="container-fluid">
    <div class="row my-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 py-2 bg-light rounded">
                    <li class="breadcrumb-item">
                        <a href="index.php?" class="text-decoration-none text-primary">Trang chủ</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="index.php?model=loan&action=index" class="text-decoration-none text-primary">Quản lý phiếu</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Chi tiết phiếu mượn</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="container-fluid">
    <!-- Thông báo lỗi -->
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
    <div class="card shadow mb-4">
    <div class="card-header py-2">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Chi tiết phiếu mượn</h5>
                <!-- <div class="d-flex align-items-center">
                    <div class="me-3">
                        <input type="search" id="searchInput" class="form-control" placeholder="Tìm kiếm...">
                    </div>
                    <a href="index.php?model=loan&action=create" class="btn btn-primary ml-3">
                        <i class="fas fa-plus"></i> Thêm phiếu
                    </a>
                </div> -->
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Mã Phiếu:</strong> <?= htmlspecialchars($loan['loan_id']); ?></p>
                    <p><strong>Người Mượn:</strong> <?= htmlspecialchars($loan['borrower_name']); ?></p>
                    <!-- <p><strong>Sách Mượn:</strong> <?= htmlspecialchars($loan['book_title']); ?></p> -->
                    <p><strong>Ngày Mượn:</strong> <?= htmlspecialchars((new DateTime($loan['issued_date']))->format('d/m/Y')); ?></p>
                    <p><strong>Ngày Đến Hạn:</strong> <?= htmlspecialchars((new DateTime($loan['due_date']))->format('d/m/Y')); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Ngày Trả (nếu có):</strong> <?= $loan['returned_date'] ? htmlspecialchars((new DateTime($loan['returned_date']))->format('d/m/Y')) : 'Chưa trả'; ?></p>
                    <p><strong>Người Trả (nếu có):</strong> <?= $loan['returned_to'] ? htmlspecialchars($loan['returned_to']) : 'Chưa trả'; ?></p>
                    <p><strong>Trạng Thái:</strong>
                        <span class="badge <?= $loan['status'] === 'issued' ? 'bg-warning' : ($loan['status'] === 'returned' ? 'bg-success' : 'bg-danger'); ?>">
                        <td class="text-center align-middle">
                                        <?php
                                        $displayStatus = $loan['status'];
                                        if ($loan['status'] === 'issued') {
                                            $displayStatus = 'Đã phê duyệt';
                                        } elseif ($loan['status'] === 'overdue') {
                                            $displayStatus = 'Quá hạn';
                                        } elseif ($loan['status'] === 'returned') {
                                            $displayStatus = 'Đã trả';
                                        }
                                        echo htmlspecialchars($displayStatus);
                                        ?>
                                    </td>
                        </span>
                    </p>
                    <p><strong>Ghi Chú:</strong> <?= htmlspecialchars($loan['notes']); ?></p>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" class="table table-hover table-striped table-bordered">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>Tên sách</th>
                                <th>Số lượng</th>
                                <th>Trạng thái</th>
                                <th>Số lượng hiện có</th>
                                <th>Trạng thái sách</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($books as $book_detail): ?>
                                <tr>
                                    <td class="text-center align-middle"><?= htmlspecialchars($book_detail['book_title']); ?></td>
                                    <td class="text-center align-middle"><?= htmlspecialchars($book_detail['quantity']); ?></td>
                                     <td class="text-center align-middle">
                                        <?php
                                        $displayStatus = $book_detail['status'];
                                        if ($book_detail['status'] === 'issued') {
                                            $displayStatus = 'Đã phê duyệt';
                                        } elseif ($book_detail['status'] === 'overdue') {
                                            $displayStatus = 'Quá hạn';
                                        } elseif ($book_detail['status'] === 'returned') {
                                            $displayStatus = 'Đã trả';
                                        }
                                        echo htmlspecialchars($displayStatus);
                                        ?>
                                    </td>
                                    <td class="text-center align-middle"><?= htmlspecialchars($book_detail['book_quantity']); ?></td>
                                    <td class="text-center align-middle">
                                        <?php if ($loan['status'] != 'issued'and $loan['status'] != 'overdue' and $loan['status'] != 'returned'): ?>
                                            <input type="checkbox" name="books[<?= $book_detail['book_id']; ?>]" value="1">
                                        <?php endif;?>
                                        <?php if ($loan['status'] === 'issued'): ?>
                                            <select class="form-select" name="books[<?= $book_detail['book_id']; ?>]">
                                                <option value="returned">Đã trả</option>
                                                <option value="lost">Mất</option>
                                                <option value="damaged">Hư hỏng</option>
                                            </select>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="d-flex justify-content-center gap-4 mt-3">
                <?php if ($loan['status'] != 'issued'and $loan['status'] != 'overdue' and $loan['status'] != 'returned'): ?>
                    <button class="btn btn-warning btn-lg" onclick="handleAction('issued', <?= $loan['loan_id']; ?>)">Phê duyệt</button>
                 <?php endif; ?>
                <?php if ($loan['status'] === 'issued'): ?>
                    <button class="btn btn-success btn-lg" onclick="handleAction('returned', <?= $loan['loan_id']; ?>)">Đã trả</button>
                    <button class="btn btn-danger btn-lg" onclick="handleAction('overdue', <?= $loan['loan_id']; ?>)">Quá hạn</button>
                <?php endif; ?>
            </div>
    </div>
</div>

<script>
   function handleAction(action, id) {
    if (confirm('Bạn có chắc chắn muốn thực hiện hành động này?')) {
        // Tạo form động để gửi dữ liệu
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = `index.php?model=loan&action=update_status&status=${action}&id=${id}`;
        
        // Xử lý checkbox hoặc select tùy theo trạng thái
        var elements = document.querySelectorAll('input[type="checkbox"][name^="books"], select[name^="books"]');
        elements.forEach(function(element) {
            // Nếu là checkbox ở trạng thái null, chỉ thêm checkbox đã chọn
            // Nếu là select ở trạng thái issued, luôn thêm
            if (element.type === 'checkbox') {
                if (element.checked) {
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = element.name;
                    input.value = element.value;
                    form.appendChild(input);
                }
            } else if (element.type === 'select-one') {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = element.name;
                input.value = element.value;
                form.appendChild(input);
            }
        });

        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<style>
    .breadcrumb a {
        font-weight: 500;
    }

    .card {
        border-radius: 10px;
    }

    .btn {
        font-size: 0.9rem; /* Tăng kích thước chữ */
        font-weight: bold; /* In đậm chữ */
        transition: background-color 0.3s ease;
    }

    .btn:hover {
        filter: brightness(0.9);
    }

    /* Căn giữa các nút và tạo khoảng cách giữa chúng */
    .d-flex {
    justify-content: center; /* Căn giữa tất cả các nút */
    gap: 20px; /* Khoảng cách giữa các nút */
    flex-wrap: wrap; /* Cho phép các nút xuống dòng khi không đủ không gian */
}

.d-flex button {
    font-size: 0.9rem; /* Kích thước chữ */
    font-weight: bold; /* In đậm chữ */
    transition: background-color 0.3s ease;
    margin: 10px; /* Thêm margin để có khoảng cách giữa các nút */
}

/* Đảm bảo các nút vẫn giữ kiểu hover */
.btn:hover {
    filter: brightness(0.9);
}

/* Tối ưu hiển thị khi màn hình nhỏ */
@media (max-width: 768px) {
    .d-flex {
        flex-direction: column; /* Đặt các nút theo cột khi màn hình nhỏ */
        gap: 10px; /* Khoảng cách nhỏ hơn giữa các nút */
    }

    .d-flex button {
        width: 100%; /* Đảm bảo nút chiếm toàn bộ chiều rộng khi màn hình nhỏ */
        margin-left: 0; /* Loại bỏ margin-left để các nút chiếm hết không gian */
    }
}
</style>
