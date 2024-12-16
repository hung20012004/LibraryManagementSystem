<?php

// echo '<pre>';
// var_dump($_SESSION);
// echo '</pre>';
?>
<div class="container-fluid">
    <div class="row mt-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?model=book_condition&action=index">Quản lý phiếu tình trạng sách</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tạo phiếu mới</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
 
<!-- Thông báo lỗi -->
<?php if (isset($_SESSION['message'])): ?>
    <div id="alert-message" class="alert alert-<?= $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
        <?= $_SESSION['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <script>
        setTimeout(() => {
            document.getElementById('alert-message')?.classList.add('fade');
        }, 2000);
    </script>
<?php endif; ?>


<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-2">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Quản lý tình trạng sách</h5>
                <div class="d-flex align-items-center">
                    <input type="search" id="searchInput" class="form-control me-3" placeholder="Tìm kiếm sách...">
                    <a href="index.php?model=book_condition&action=create" class="btn btn-primary ml-3">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-striped table-bordered text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID phiếu</th>
                            <th>Tên sách</th>
                            <th>Ngày kiểm tra</th>
                            <th>Tình trạng trả về</th>
                            <th>Người kiểm tra</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($book_conditions as $book_condition): ?>
                            <tr>
                                <td><?= $book_condition['condition_id'] ?></td>
                                <td><?= htmlspecialchars($book_condition['book_title']) ?></td>
                                <td><?= htmlspecialchars($book_condition['assessed_date']) ?></td>
                                <td><?= htmlspecialchars($book_condition['condition_after']) ?></td>
                                <td><?= htmlspecialchars($book_condition['user_name']) ?></td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <a href="index.php?model=book_condition&action=edit&id=<?= $book_condition['condition_id'] ?>" class="btn btn-sm btn-outline-primary me-2" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="index.php?model=book_condition&action=delete&id=<?= $book_condition['condition_id'] ?>" method="POST" class="d-inline" onsubmit="return confirmDelete();">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#dataTable').DataTable({
            dom: 'rtp',
            language: {
                processing: "Đang xử lý...",
                search: '<i class="fas fa-search"></i>',
                lengthMenu: "Hiển thị _MENU_ dòng",
                info: "Đang hiển thị _START_ đến _END_ của _TOTAL_ bản ghi",
                infoEmpty: "Không có dữ liệu",
                infoFiltered: "(Được lọc từ _MAX_ bản ghi)",
                zeroRecords: "Không tìm thấy sách nào",
                emptyTable: "Không có dữ liệu trong bảng",
                paginate: {
                    first: "Đầu",
                    previous: "Trước",
                    next: "Tiếp",
                    last: "Cuối"
                }
            },
            columnDefs: [
                { targets: -1, orderable: false, searchable: false }
            ]
        });

        // Search functionality
        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Confirm delete
        window.confirmDelete = function() {
            return confirm('Bạn có chắc muốn xóa sách này?');
        };
    });
</script>