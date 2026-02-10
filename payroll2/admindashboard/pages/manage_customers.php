<?php 
    session_start();
    require_once '../../config.php'; 

    // Security Check
    if (!isset($_SESSION['auth_user'])) {
        header("Location: ../../login.php");
        exit;
    }

    // Logged in Admin ID
    $admin_id = isset($_SESSION['auth_user_id']) ? $_SESSION['auth_user_id'] : $_SESSION['id'];

    // Delete Logic (Fix: Using standard mysqli query for consistency)
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
        $customer_id = mysqli_real_escape_string($conn, $_GET['id']);
        
        // Delete from bill_items first (to maintain database integrity if foreign keys are used)
        mysqli_query($conn, "DELETE FROM bill_items WHERE customer_id = '$customer_id'");
        
        // Delete from customers
        $delete_query = "DELETE FROM customers WHERE id = '$customer_id' AND admin_id = '$admin_id'";
        
        if (mysqli_query($conn, $delete_query)) {
            echo "<script>alert('Customer Deleted!'); window.location.href='manage_customer.php';</script>";
        } else {
            echo "<script>alert('Error deleting record: " . mysqli_error($conn) . "');</script>";
        }
    }

    // Query: Shudhu ei admin er customer-der niye asha
    $query = "SELECT * FROM customers WHERE admin_id = '$admin_id' ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);

    include('../includes/header.php'); 
    include('../includes/sidebar.php'); 
?>

<style>
    /* --- EXACT ADMIN_LIST DESIGN (NO PIXEL CHANGE) --- */
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

    .text-green { color: #4BB543; font-weight: bold; }
    .text-red { color: #ff4d4f; font-weight: bold; }

    /* --- MOBILE OPTIMIZATION --- */
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
            padding: 5px 0;
            overflow: visible !important;
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
    }
</style>

<main class="main-content">
    <div class="main-panel">
        <div class="content-wrapper">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding: 0 5px;">
                <h3 style="color: #fff; font-weight: 600; margin: 0;">Manage Customers</h3>
            </div>

            <div class="approval-container">
                <div class="table-responsive">
                    <table class="approval-table">
                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Mobile</th>
                                <th>Total Amount</th>
                                <th>Paid</th>
                                <th>Due</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($result) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td data-label="Customer"><?php echo htmlspecialchars($row['customer_name']); ?></td>
                                    <td data-label="Mobile"><?php echo htmlspecialchars($row['mobile']); ?></td>
                                    <td data-label="Total" class="text-green"><?php echo number_format($row['total_amount'], 2); ?></td>
                                    <td data-label="Paid"><?php echo number_format($row['paid_amount'], 2); ?></td>
                                    <td data-label="Due" class="<?php echo ($row['total_due'] > 0) ? 'text-red' : ''; ?>">
                                        <?php echo number_format($row['total_due'], 2); ?>
                                    </td>
                                    <td data-label="Action">
                                        <div class="action-dropdown">
                                            <button type="button" class="dropbtn" onclick="toggleDropdown(event, this)">
                                                Options <i class="mdi mdi-chevron-down"></i>
                                            </button>
                                            <div class="dropdown-content">
                                                <a href="edit_customer.php?id=<?php echo $row['id']; ?>">View Bill</a>
                                                <a href="edit_customer.php?id=<?php echo $row['id']; ?>">Edit Data</a>
                                                <a href="manage_customer.php?action=delete&id=<?php echo $row['id']; ?>" 
                                                   style="color: #ff4d4f;" 
                                                   onclick="return confirm('Are you sure you want to delete this customer?')">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="6" style="text-align: center; padding: 40px; color: #6c7293;">No customers found. Start by adding a new bill!</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Exact same JS as admin_list
function toggleDropdown(event, btn) {
    event.stopPropagation();
    var content = btn.nextElementSibling;
    document.querySelectorAll(".dropdown-content").forEach(function(dropdown) {
        if (dropdown !== content) dropdown.classList.remove("show");
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