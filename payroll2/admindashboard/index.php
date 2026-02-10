<?php
session_start();
require_once '../config.php'; 

if (!isset($_SESSION['auth_user'])) {
    header("Location: /payroll2/index.php");
    exit;
}

/** * SESSION THEKE ID NEWA 
 * Login process-e jodi $_SESSION['auth_user_id'] thake seta nibe, 
 * na thakle $_SESSION['id'] nibe.
 */
$user_id = $_SESSION['auth_user_id'] ?? $_SESSION['id'] ?? 0;

// 1. Total Customer (Ei Admin-er niche koyjon customer ace)
$cust_res = mysqli_query($conn, "SELECT COUNT(id) as total_cust FROM customers WHERE admin_id = '$user_id'");
$cust_data = mysqli_fetch_assoc($cust_res);
$total_customer = $cust_data['total_cust'] ?? 0;

// 2. Total Paid & Total Due (Nijessho customer-der total calculation)
$amounts_res = mysqli_query($conn, "SELECT SUM(paid_amount) as total_paid, SUM(total_due) as total_due FROM customers WHERE admin_id = '$user_id'");
$amounts_data = mysqli_fetch_assoc($amounts_res);
$total_paid = $amounts_data['total_paid'] ?? 0;
$total_due = $amounts_data['total_due'] ?? 0;

// 3. Total Admin (System-e kotojon active admin ace)
$admin_res = mysqli_query($conn, "SELECT COUNT(id) as total_adm FROM users WHERE role = 'admin'");
$admin_data = mysqli_fetch_assoc($admin_res);
$total_admins = $admin_data['total_adm'] ?? 0;

include('includes/header.php'); 
include('includes/sidebar.php'); 
?>

<main class="main-content">
    <h2 style="margin-bottom: 5px; font-size: 22px;">Dashboard Overview</h2>
    
    <div class="dashboard-row">
        <div class="stat-card">
            <div class="card-top">
                <div class="value-box">
                    <h3><i class="mdi mdi-account-multiple text-white"></i></h3>
                    <p class="amount text-success"><?php echo $total_customer; ?></p>
                </div>
                <div class="trend-icon bg-success-soft"><span class="mdi mdi-arrow-top-right"></span></div>
            </div>
            <h6 class="card-label">Total Customer</h6>
        </div>

        <div class="stat-card">
            <div class="card-top">
                <div class="value-box">
                    <h3><i class="mdi mdi-cash-multiple text-white"></i></h3>
                    <p class="amount text-success">৳ <?php echo number_format($total_paid, 0); ?></p>
                </div>
                <div class="trend-icon bg-success-soft"><span class="mdi mdi-arrow-top-right"></span></div>
            </div>
            <h6 class="card-label">Total Paid</h6>
        </div>

        <div class="stat-card">
            <div class="card-top">
                <div class="value-box">
                    <h3><i class="mdi mdi-calendar-minus text-white"></i></h3>
                    <p class="amount text-danger">৳ <?php echo number_format($total_due, 0); ?></p>
                </div>
                <div class="trend-icon bg-danger-soft"><span class="mdi mdi-arrow-bottom-left"></span></div>
            </div>
            <h6 class="card-label">Due</h6>
        </div>

        <div class="stat-card">
            <div class="card-top">
                <div class="value-box">
                    <h3><i class="mdi mdi-account-off text-white"></i></h3>
                    <p class="amount text-danger"><?php echo sprintf("%02d", $total_admins); ?></p>
                </div>
                <div class="trend-icon bg-danger-soft"><span class="mdi mdi-arrow-bottom-left"></span></div>
            </div>
            <h6 class="card-label">Total Admin</h6>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>