<aside class="sidebar">
    <div class="sidebar-brand">PAYROLL</div>
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="/payroll2/index.php" class="nav-link active-nav">
                <i class="mdi mdi-speedometer"></i> Dashboard
            </a>
        </li>
        
        <li class="nav-item">
            <div class="nav-link has-dropdown"><i class="mdi mdi-account-group"></i> <span>Employees</span><i class="mdi mdi-chevron-down arrow"></i></div>
            <ul class="sub-menu">
                <li><a href="/payroll2/admindashboard/pages/layout.php" class="sub-link">Employee List</a></li>
                <li><a href="/payroll2/admindashboard/pages/add_employee.php" class="sub-link">Add New</a></li>
            </ul>
        </li>
        
        <li class="nav-item">
            <div class="nav-link has-dropdown"><i class="mdi mdi-cash-multiple"></i> <span>Payroll</span><i class="mdi mdi-chevron-down arrow"></i></div>
            <ul class="sub-menu">
                <li><a href="/payroll2/admindashboard/pages/monthly_salary.php" class="sub-link">Monthly Salary</a></li>
                <li><a href="/payroll2/admindashboard/pages/payslips.php" class="sub-link">Payslips</a></li>
            </ul>
        </li>
        
        <li class="nav-item">
            <a href="/payroll2/admindashboard/pages/add_employee.php" class="nav-link">
                <i class="mdi mdi-calendar-check"></i> Attendance
            </a>
        </li>
        
        <li class="nav-item"><a href="/payroll2/admindashboard/pages/reports.php" class="nav-link"><i class="mdi mdi-file-document"></i> Reports</a></li>
        <li class="nav-item"><a href="/payroll2/admindashboard/pages/settings.php" class="nav-link"><i class="mdi mdi-cog"></i> Settings</a></li>
    </ul>
</aside>

<header class="custom-navbar">
    <div class="nav-left">
        <div class="menu-toggle" id="toggleBtn"><i class="mdi mdi-menu"></i></div>
        <div class="search-box"><input type="text" placeholder="Search here..."></div>
    </div>
    <div class="nav-right">
        <div class="profile-wrapper" id="profileBtn">
            <div class="profile-info">
                <img src="https://via.placeholder.com/32" class="profile-img" alt="profile">
                <span class="profile-name">Henry Klein</span>
                <i class="mdi mdi-menu-down"></i>
            </div>
            <div class="dropdown-box" id="profileDropdown">
                <a href="/payroll2/admindashboard/pages/profile.php" class="dropdown-link"><i class="mdi mdi-account text-success"></i> My Profile</a>
                <a href="/payroll2/admindashboard/logout.php" class="dropdown-link"><i class="mdi mdi-logout text-danger"></i> Logout</a>
            </div>
        </div>
    </div>
</header>