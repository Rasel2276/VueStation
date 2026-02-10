<?php
session_start();
require_once '../../config.php'; 

// 1. Security Check
if (!isset($_SESSION['auth_user']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: /payroll2/admindashboard/index.php");
    exit;
}

// 2. Logic: Action handling
if (isset($_GET['action']) && isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $action = $_GET['action'];

    if ($action == 'approve') {
        $stmt = $conn->prepare("UPDATE users SET status = 'active' WHERE id = ?");
    } elseif ($action == 'reject') {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    }
    
    if(isset($stmt)) {
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            header("Location: admin_approvals.php?msg=Updated");
            exit;
        }
    }
}

// 3. Pending List Fetch
$query = "SELECT * FROM users WHERE status = 'pending' AND role = 'admin' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

include('../includes/header.php'); 
include('../includes/sidebar.php'); 
?>

<style>
    /* --- EXACT SAME DESIGN AS ADMIN LIST --- */
    .approval-container {
        background: #191C24;
        padding: 40px;
        border-radius: 10px;
        max-width: 100%;
        margin-top: 20px;
    }
    .table-responsive { width: 100%; overflow: visible !important; } 
    .approval-table { width: 100%; border-collapse: collapse; color: #ffffff; }
    .approval-table thead tr { border-bottom: 1px solid #353a45; }
    .approval-table th { color: #4BB543; padding: 15px; text-align: left; font-size: 14px; text-transform: uppercase; font-weight: 600; }
    .approval-table td { padding: 15px; border-bottom: 1px solid #2c2e33; color: #adb5bd; font-size: 14px; }

    /* Dropdown CSS */
    .action-dropdown { position: relative; display: inline-block; }
    .dropbtn { background: #0090e7; color: white; padding: 8px 16px; font-size: 13px; border: none; border-radius: 4px; cursor: pointer; position: relative; z-index: 10; }
    .dropdown-content { 
        display: none; 
        position: absolute; 
        right: 0; 
        top: 100%; 
        background-color: #191c24; 
        min-width: 140px; 
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.8); 
        z-index: 9999; 
        border: 1px solid #2c2e33; 
        border-radius: 4px; 
        margin-top: 5px;
    }
    .dropdown-content.show { display: block !important; }
    .dropdown-content a { color: #adb5bd; padding: 10px 12px; text-decoration: none; display: block; font-size: 13px; text-align: left; }
    .dropdown-content a:hover { background-color: #0f1015; color: #4BB543; }

    .status-badge-pending { background: rgba(255, 171, 0, 0.1); color: #ffab00; padding: 4px 8px; border-radius: 4px; font-size: 11px; }

    /* --- MOBILE OPTIMIZATION (EXACT SAME FIT) --- */
    @media (max-width: 768px) {
        .content-wrapper { padding: 10px !important; } 
        .approval-container { 
            padding: 15px !important; 
            margin: 10px auto !important; 
            width: 100% !important; 
            box-sizing: border-box;
            border-radius: 8px;
        }
        
        .approval-table thead { display: none; }
        .approval-table, .approval-table tbody, .approval-table tr, .approval-table td { display: block; width: 100% !important; box-sizing: border-box; }
        .approval-table tr { 
            margin-bottom: 20px; 
            border: 1px solid #353a45; 
            border-radius: 8px; 
            background: #12151e; 
            overflow: visible !important;
            padding: 5px 0;
        }
        .approval-table td { 
            text-align: right; 
            padding: 15px 15px; 
            position: relative; 
            border-bottom: 1px solid #2c2e33; 
            display: flex; 
            justify-content: flex-end; 
            align-items: center; 
        }
        .approval-table td::before { 
            content: attr(data-label); 
            position: absolute; 
            left: 15px; 
            font-weight: 700; 
            color: #4BB543; 
            text-transform: uppercase; 
            font-size: 11px; 
        }
        .approval-table td:last-child { border-bottom: none; }
        .dropdown-content { right: 0; }
    }
</style>

<main class="main-content">
    <div class="main-panel">
        <div class="content-wrapper">
            <h3 style="margin-bottom: 10px; color: #fff; font-weight: 600; padding-left: 5px;">Admin Approval Requests</h3>

            <div class="approval-container">
                <div class="table-responsive">
                    <table class="approval-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($result) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td data-label="Name"><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td data-label="Email"><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td data-label="Mobile"><?php echo htmlspecialchars($row['mobile']); ?></td>
                                    <td data-label="Status">
                                        <span class="status-badge-pending">Pending</span>
                                    </td>
                                    <td data-label="Action" style="overflow: visible;">
                                        <div class="action-dropdown">
                                            <button type="button" class="dropbtn" onclick="toggleDropdown(event, this)">
                                                Manage <i class="mdi mdi-chevron-down"></i>
                                            </button>
                                            <div class="dropdown-content">
                                                <a href="admin_approvals.php?action=approve&id=<?php echo $row['id']; ?>" style="color: #4BB543;" onclick="return confirm('Approve this Admin?')">Approve</a>
                                                <a href="admin_approvals.php?action=reject&id=<?php echo $row['id']; ?>" style="color: #ff4d4f;" onclick="return confirm('Are you sure you want to reject?')">Reject</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="5" style="text-align: center; padding: 30px;">No pending requests found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Same JavaScript Logic
function toggleDropdown(event, btn) {
    event.stopPropagation();
    var content = btn.nextElementSibling;
    var allDropdowns = document.querySelectorAll(".dropdown-content");
    
    allDropdowns.forEach(function(dropdown) {
        if (dropdown !== content) {
            dropdown.classList.remove("show");
        }
    });
    content.classList.toggle("show");
}

document.addEventListener("click", function(event) {
    if (!event.target.closest('.action-dropdown')) {
        document.querySelectorAll(".dropdown-content").forEach(function(dropdown) {
            dropdown.classList.remove("show");
        });
    }
});
</script>

<?php include('../includes/footer.php'); ?>