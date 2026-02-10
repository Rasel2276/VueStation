<aside class="sidebar">
    <div class="sidebar-brand">NAJMUL SOTRE</div>
    <ul class="nav-menu">
        <?php
            // Current page-er filename check korar jonno
            $current_page = $_SERVER['REQUEST_URI']; 
        ?>
        
        <li class="nav-item">
            <a href="/payroll2/admindashboard/index.php" 
               class="nav-link <?php echo (strpos($current_page, 'index.php') !== false) ? 'active-nav' : ''; ?>">
                <i class="mdi mdi-speedometer"></i> Dashboard
            </a>
        </li>
        
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'superadmin'): ?>
        <?php 
            // Admin related page active kina check
            $admin_active = (strpos($current_page, 'admin_approvals.php') !== false || strpos($current_page, 'admin_list.php') !== false);
        ?>
        <li class="nav-item">
            <div class="nav-link has-dropdown <?php echo $admin_active ? 'active-nav' : ''; ?>">
                <i class="mdi mdi-account-group"></i> <span>Admin</span>
                <i class="mdi mdi-chevron-down arrow"></i>
            </div>
            <ul class="sub-menu" style="<?php echo $admin_active ? 'display: block;' : ''; ?>">
                <li><a href="/payroll2/admindashboard/pages/admin_approvals.php" 
                       class="sub-link <?php echo (strpos($current_page, 'admin_approvals.php') !== false) ? 'text-success' : ''; ?>">Admin Approval</a></li>
                <li><a href="/payroll2/admindashboard/pages/admin_list.php" 
                       class="sub-link <?php echo (strpos($current_page, 'admin_list.php') !== false) ? 'text-success' : ''; ?>">Admin List</a></li>
            </ul>
        </li>
        <?php endif; ?>
        
        <?php 
            // Customer related page active kina check
            $customer_active = (strpos($current_page, 'add_customer.php') !== false || strpos($current_page, 'manage_customers.php') !== false);
        ?>
        <li class="nav-item">
            <div class="nav-link has-dropdown <?php echo $customer_active ? 'active-nav' : ''; ?>">
                <i class="mdi mdi-cash-multiple"></i> <span>Customer</span>
                <i class="mdi mdi-chevron-down arrow"></i>
            </div>
            <ul class="sub-menu" style="<?php echo $customer_active ? 'display: block;' : ''; ?>">
                <li><a href="/payroll2/admindashboard/pages/add_customer.php" 
                       class="sub-link <?php echo (strpos($current_page, 'add_customer.php') !== false) ? 'text-success' : ''; ?>">Add Customer</a></li>
                <li><a href="/payroll2/admindashboard/pages/manage_customers.php" 
                       class="sub-link <?php echo (strpos($current_page, 'manage_customers.php') !== false) ? 'text-success' : ''; ?>">Manage Customer</a></li>
            </ul>
        </li>


        <li class="nav-item">
            <div class="nav-link has-dropdown <?php echo $product_active ? 'active-nav' : ''; ?>">
                <i class="mdi mdi-cash-multiple"></i> <span>Personal Notes</span>
                <i class="mdi mdi-chevron-down arrow"></i>
            </div>
            <ul class="sub-menu" style="<?php echo $product_active ? 'display: block;' : ''; ?>">
                <li><a href="/payroll2/admindashboard/pages/add_notes.php" 
                       class="sub-link <?php echo (strpos($current_page, 'add_notes.php') !== false) ? 'text-success' : ''; ?>">Add Product</a></li>
                <li><a href="/payroll2/admindashboard/pages/manage_notes.php" 
                       class="sub-link <?php echo (strpos($current_page, 'manage_notes.php') !== false) ? 'text-success' : ''; ?>">Manage Notes</a></li>
            </ul>
        </li>

        
        <li class="nav-item">
            <a href="/payroll2/admindashboard/pages/profile.php" 
               class="nav-link <?php echo (strpos($current_page, 'profile.php') !== false) ? 'active-nav' : ''; ?>">
                <i class="mdi mdi-cog"></i> Profile
            </a>
        </li>
    </ul>
</aside>

<header class="custom-navbar">
    <div class="nav-left">
        <div class="menu-toggle" id="toggleBtn"><i class="mdi mdi-menu"></i></div>
        <div class="search-box"><input type="text" placeholder="Search here..."></div>
    </div>
    <div class="nav-right">
        <div class="profile-wrapper" id="profileBtn">
            <div class="profile-info" style="display: flex; align-items: center; cursor: pointer; gap: 5px;">
                <?php 
                    $display_img = 'https://via.placeholder.com/40';
                    if(isset($_SESSION['profile_image']) && !empty($_SESSION['profile_image'])) {
                        $display_img = '/payroll2/' . $_SESSION['profile_image'];
                    }
                ?>
                <div style="width: 40px; height: 45px; min-width: 45px; overflow: hidden; border-radius: 50%; border: 2px solid #4BB543; background: #2A3038; display: flex; align-items: center; justify-content: center;">
                    <img src="<?php echo $display_img; ?>" alt="profile" 
                         style="max-width: 100%; max-height: 100%; object-fit: contain; display: block;">
                </div>
                
                <span class="profile-name" style="font-weight: 600; color: #fff; white-space: nowrap; margin-left: 2px;">
                    <?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'Guest'; ?>
                </span>
                <i class="mdi mdi-menu-down" style="color: #fff;"></i>
            </div>
            <div class="dropdown-box" id="profileDropdown">
                <a href="/payroll2/admindashboard/pages/profile.php" class="dropdown-link"><i class="mdi mdi-account text-success"></i> My Profile</a>
                <a href="/payroll2/logout.php" class="dropdown-link"><i class="mdi mdi-logout text-danger"></i> Logout</a>
            </div>
        </div>
    </div>
</header>