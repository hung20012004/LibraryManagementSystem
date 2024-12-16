<!-- <li class="nav-item <?= ($current_model == 'user' && $current_action == 'index') ? 'active' : '' ?>">
    <a class="nav-link" href="route.php?model=user&action=index" >
        <i class="fa-solid fa-user-group"style="color: #423b8e; font-size: 1.1rem; margin-right: 0.75rem;"></i>
        <span style="color: #423b8e; font-size: 0.9rem;">Quản lý người dùng</span>
    </a>
</li> -->
<li class="nav-item <?= ($current_model == 'taisan' || $current_model == 'khauhao' || $current_model == 'loaitaisan' || $current_model == 'vitri') ? 'active' : '' ?>">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo" 
    style="color: #423b8e; position: relative;"
    data-custom-color="#423b8e">
        <i class="fa-solid fa-user-group" style="color: #423b8e; font-size: 1.1rem; margin-right: 0.75rem;"></i>
        <span style="color: #423b8e; font-size: 0.9rem; margin-right: 0.75rem;">Tài khoản & Quyền</span>
    </a>
    <div id="collapseTwo" class="collapse <?= ($current_model == 'role' || $current_model == 'permission' || $current_model == 'user' ) ? 'show' : '' ?>" aria-labelledby="headingTwo">
        <div class="bg-transparent py-2 collapse-inner rounded">
            <a class="collapse-item <?= ($current_model == 'user' && $current_action == 'index') ? 'active' : '' ?>" 
               href="route.php?model=user&action=index" 
               style="color: #423b8e; font-size: 0.9rem;">Người dùng</a>
            <a class="collapse-item <?= ($current_model == 'role' && $current_action == 'index') ? 'active' : '' ?>" 
               href="route.php?model=role&action=index" 
               style="color: #423b8e; font-size: 0.9rem;">Vai trò</a>
            <a class="collapse-item <?= ($current_model == 'permission' && $current_action == 'index') ? 'active' : '' ?>" 
               href="route.php?model=permission&action=index" 
               style="color: #423b8e; font-size: 0.9rem;">Quyền</a>
            
        </div>
        
    </div>
    <a class="nav-link" href="route.php?model=publisher&action=index" >
        <i class="fa-solid fa-user-group"style="color: #423b8e; font-size: 1.1rem; margin-right: 0.75rem;"></i>
        <span style="color: #423b8e; font-size: 0.9rem;">Quản lý NXB</span>
    </a>
   <a class="nav-link" href="route.php?model=book&action=index" >
        <i class="fa-solid fa-user-group"style="color: #423b8e; font-size: 1.1rem; margin-right: 0.75rem;"></i>
        <span style="color: #423b8e; font-size: 0.9rem;">Quản lý sách</span>
    </a> 
    <a class="nav-link" href="route.php?model=book_condition&action=index" >
        <i class="fa-solid fa-user-group"style="color: #423b8e; font-size: 1.1rem; margin-right: 0.75rem;"></i>
        <span style="color: #423b8e; font-size: 0.9rem;">Quản lý phiểu kiểm tra sách</span>
    </a>
</li>
