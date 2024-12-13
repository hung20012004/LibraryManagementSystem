    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?model=category&action=index">Quản lý thể loại</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tạo thể loại mới</li>
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
                            <h5 class="card-title mb-0">Tạo thể loại</h5>
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
                        <form action="index.php?model=category&action=create" method="POST">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Tên thể loại:</label>
                                    <input type="text" name="name" id="name" class="form-control" required 
                                        value="<?php echo isset($_SESSION['form_data']['name']) ? htmlspecialchars($_SESSION['form_data']['name']) : ''; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="description" class="form-label">Mô tả:</label>
                                    <input type="text" name="description" id="description" class="form-control" 
                                        value="<?php echo isset($_SESSION['form_data']['description']) ? htmlspecialchars($_SESSION['form_data']['description']) : ''; ?>">
                                </div>
                            </div>
                        
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="index.php?model=category&action=index" class="btn btn-secondary"> 
                                <i class="fa-solid fa-arrow-left"></i>
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