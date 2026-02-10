<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Admin Dashboard</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    
    <style>
        :root {
            --bg-body: #000000;
            --bg-sidebar: #191c24;
            --navbar-bg: #191c24;
            --text-muted: #6c7293;
            --text-white: #ffffff;
            --border-color: #2c2e33;
            --success: #00d25b;
            --danger: #fc424a;
            --sidebar-width: 244px;
            --header-height: 70px;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", sans-serif;
            background-color: var(--bg-body);
            color: var(--text-white);
            overflow-x: hidden;
            transition: all 0.3s ease;
        }

        /* --- SIDEBAR STYLES --- */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--bg-sidebar);
            height: 100vh;
            position: fixed;
            top: 0; left: 0;
            z-index: 1001;
            transition: all 0.3s ease;
            overflow-y: auto;
            border-right: 1px solid var(--border-color);
        }

        body.sidebar-hidden .sidebar { left: calc(-1 * var(--sidebar-width)); }

        .sidebar-brand {
            height: var(--header-height);
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; font-weight: bold; color: var(--success);
            border-bottom: 1px solid var(--border-color);
        }

        .nav-menu { list-style: none; padding: 20px 0; margin: 0; }
        .nav-link {
            display: flex; align-items: center; padding: 12px 25px;
            color: var(--text-muted); text-decoration: none;
            transition: 0.3s; font-size: 15px; cursor: pointer;
            border-left: 3px solid transparent;
        }
        .nav-link i:first-child { margin-right: 15px; font-size: 18px; width: 20px; }
        .nav-link .arrow { margin-left: auto; transition: 0.3s; }

        .nav-link:hover, .nav-item.open > .nav-link, .nav-link.active-nav {
            background: #0f1116; color: var(--text-white);
            border-left: 3px solid var(--success);
            border-radius: 0 50px 50px 0;
        }

        .sub-menu {
            list-style: none; padding: 0; margin: 0;
            max-height: 0; overflow: hidden;
            transition: max-height 0.3s ease-in-out;
            background-color: var(--bg-sidebar);
        }
        .sub-menu li a {
            display: block; padding: 10px 25px 10px 60px;
            color: var(--text-muted); text-decoration: none;
            font-size: 13px; transition: 0.3s;
        }
        .sub-menu li a:hover, .sub-menu li a.active-sub { color: var(--success); }
        .nav-item.open .sub-menu { max-height: 200px; }
        .nav-item.open .arrow { transform: rotate(180deg); }

        /* --- HEADER STYLES --- */
        .custom-navbar {
            background-color: var(--navbar-bg);
            height: var(--header-height);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 1.5rem; position: fixed;
            top: 0; right: 0; left: var(--sidebar-width);
            z-index: 1000; border-bottom: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }
        body.sidebar-hidden .custom-navbar { left: 0; }

        .nav-left, .nav-right { display: flex; align-items: center; }
        .menu-toggle { color: var(--text-muted); font-size: 24px; cursor: pointer; margin-right: 20px; }
        .search-box input {
            background: transparent; border: 1px solid var(--border-color);
            border-radius: 4px; padding: 10px 15px; color: white; width: 300px; outline: none;
        }

        .profile-wrapper { position: relative; cursor: pointer; }
        .profile-info { display: flex; align-items: center; gap: 10px; padding: 5px 10px; }
        .profile-img { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; }
        .profile-name { font-size: 14px; font-weight: 500; }
        
        .dropdown-box {
            position: absolute; top: 60px; right: 0;
            background: var(--bg-sidebar); border: 1px solid var(--border-color);
            width: 180px; border-radius: 4px; display: none;
            flex-direction: column; box-shadow: 0 10px 20px rgba(0,0,0,0.5);
            padding: 5px 0;
        }
        .dropdown-box.active { display: flex; }
        .dropdown-link { padding: 12px 20px; color: white; text-decoration: none; font-size: 13px; display: flex; align-items: center; gap: 10px; }
        .dropdown-link:hover { background: #0f1116; }

        /* --- MAIN CONTENT & 4 COMPACT CARDS IN ONE ROW --- */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 100px 30px 30px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        body.sidebar-hidden .main-content { margin-left: 0; }

        .dashboard-row {
            display: flex;
            flex-wrap: nowrap;
            gap: 12px;
            padding: 15px 0;
            overflow-x: auto;
        }

        .stat-card {
            background-color: var(--bg-sidebar);
            border: 1px solid var(--border-color);
            border-radius: 5px;
            padding: 15px;
            flex: 1;
            min-width: 180px;
            height: 80px;
            color: var(--text-white);
            transition: transform 0.3s ease;
        }

        .stat-card:hover { transform: translateY(-3px); }

        .card-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .value-box { display: flex; align-items: center; gap: 8px; }
        .value-box h3 { margin: 0; font-size: 25px; color: var(--text-white); }
        .value-box .amount { font-size: 16px; font-weight: 600; margin: 0; }

        .trend-icon {
            width: 28px; height: 28px; border-radius: 4px;
            display: flex; align-items: center; justify-content: center; font-size: 16px;
        }
        .bg-success-soft { background: rgba(0, 210, 91, 0.1); color: var(--success); }
        .bg-danger-soft { background: rgba(252, 66, 74, 0.1); color: var(--danger); }
        .text-success { color: var(--success); }
        .text-danger { color: var(--danger); }
        .card-label { color: var(--text-muted); font-size: 15px; margin: 0; font-weight: normal; }

        .sidebar-overlay {
            display: none; position: fixed; top: 0; left: 0;
            width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;
        }

        /* Responsive adjustments */
        @media (max-width: 991px) {
            .sidebar { left: calc(-1 * var(--sidebar-width)); }
            .custom-navbar { left: 0; }
            .main-content { margin-left: 0; }
            body.sidebar-open .sidebar { left: 0; }
            body.sidebar-open .sidebar-overlay { display: block; }
            .search-box input { width: 150px; }
            .profile-name { display: none; }
            .dashboard-row { flex-wrap: wrap; }
            .stat-card { flex: 1 1 calc(50% - 12px); }
        }
        
        @media (max-width: 480px) {
            .stat-card { flex: 1 1 100%; }
        }
    </style>
</head>
<body id="body">
    <div class="sidebar-overlay" id="overlay"></div>