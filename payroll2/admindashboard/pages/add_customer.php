<?php 
    // Path: payroll2/admindashboard/pages/add_customer.php
    session_start();
    require_once '../../config.php'; 

    if (!isset($_SESSION['auth_user'])) {
        header("Location: ../../login.php");
        exit;
    }

    // --- ADMIN ID FIX START ---
    // Apnar session-e jodi 'id' thake ba 'auth_user_id' thake, seta auto-detect hobe
    $admin_id = null;
    if (isset($_SESSION['auth_user_id'])) {
        $admin_id = $_SESSION['auth_user_id'];
    } elseif (isset($_SESSION['id'])) {
        $admin_id = $_SESSION['id'];
    }
    // --- ADMIN ID FIX END ---

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['customer_name'])) {
        
        if ($admin_id === null) {
            echo "<script>alert('Error: Admin ID not found in session!');</script>";
        } else {
            $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
            $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
            $total_amount = !empty($_POST['total_amount']) ? $_POST['total_amount'] : 0;
            $paid_amount = !empty($_POST['paid_amount']) ? $_POST['paid_amount'] : 0;
            $total_due = !empty($_POST['total_due']) ? $_POST['total_due'] : 0;

            // Database insert with detected admin_id
            $query = "INSERT INTO customers (admin_id, customer_name, mobile, total_amount, paid_amount, total_due) 
                      VALUES ('$admin_id', '$customer_name', '$mobile', '$total_amount', '$paid_amount', '$total_due')";
            
            if (mysqli_query($conn, $query)) {
                $customer_id = mysqli_insert_id($conn);

                if (isset($_POST['items']) && is_array($_POST['items'])) {
                    foreach ($_POST['items'] as $key => $item_name) {
                        $item_price = !empty($_POST['prices'][$key]) ? $_POST['prices'][$key] : 0;
                        
                        if (!empty(trim($item_name))) {
                            $item_name = mysqli_real_escape_string($conn, $item_name);
                            $item_query = "INSERT INTO bill_items (customer_id, customer_name, item_name, price) 
                                          VALUES ('$customer_id', '$customer_name', '$item_name', '$item_price')";
                            mysqli_query($conn, $item_query);
                        }
                    }
                }
                echo "<script>alert('Billing Information Saved Successfully!'); window.location.href='add_customer.php';</script>";
            } else {
                echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
            }
        }
    }

    include('../includes/header.php'); 
    include('../includes/sidebar.php'); 
?>

<style>
    .employee-form { background: #191C24; padding: 40px; border-radius: 10px; color: #fff; }
    .input-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 25px; margin-bottom: 35px; }
    .form-group { display: flex; flex-direction: column; gap: 8px; }
    .form-group label { color: #6c7293; font-size: 13px; }
    input { background: #2a3038; border: 1px solid #353a45; color: #fff; padding: 12px; border-radius: 5px; outline: none; }
    .item-row { display: grid; grid-template-columns: 1.2fr 1fr auto; gap: 15px; margin-bottom: 12px; align-items: center; }
    .btn-add-inline { background: #ffab00; color: #fff; border: none; padding: 12px 15px; border-radius: 5px; cursor: pointer; font-weight: 600; white-space: nowrap; }
    .btn-remove { background: rgba(255, 77, 79, 0.1); color: #ff4d4f; border: 1px solid #ff4d4f; padding: 10px; border-radius: 5px; cursor: pointer; }
    .summary-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 40px; padding-top: 25px; border-top: 1px solid #353a45; }
    .summary-box { background: #0f1116; padding: 15px; border-radius: 8px; text-align: center; }
    .summary-box label { display: block; font-size: 12px; color: #6c7293; margin-bottom: 5px; }
    .summary-box input { background: transparent; border: none; text-align: center; font-size: 20px; font-weight: bold; width: 100%; display: block; }
    .total-green { color: #4BB543; }
    .due-red { color: #ff4d4f; }
    .btn-save { background: #4BB543; color: #fff; border: none; padding: 15px; width: 100%; border-radius: 5px; font-size: 16px; font-weight: 600; cursor: pointer; margin-top: 30px; }
    @media screen and (max-width: 768px) {
        .employee-form { padding: 20px; }
        .input-grid { grid-template-columns: 1fr; gap: 15px; }
        .item-row { grid-template-columns: 1fr; gap: 8px; background: #252b35; padding: 12px; border-radius: 8px; }
        .btn-add-inline, .btn-remove { width: 100%; margin-top: 5px; }
        .summary-grid { grid-template-columns: 1fr; gap: 15px; }
        .summary-box input { font-size: 18px; text-align: center; }
    }
</style>

<main class="main-content">
    <div class="content-wrapper">
        <h3 style="margin-bottom: 30px; color: #fff; font-weight: 600;">New Bill Entry</h3>
        <form action="" method="POST" class="employee-form">
            <div class="input-grid">
                <div class="form-group">
                    <label>Customer Name</label>
                    <input type="text" name="customer_name" required placeholder="Enter Name">
                </div>
                <div class="form-group">
                    <label>Mobile Number</label>
                    <input type="text" name="mobile" placeholder="Enter Mobile">
                </div>
            </div>

            <h4 style="color: #4BB543; margin-bottom: 15px; font-weight: 600;">Purchase Details</h4>
            
            <div id="dynamic-items">
                <div class="item-row">
                    <input type="text" name="items[]" placeholder="Item Name">
                    <input type="number" step="0.01" name="prices[]" class="item-price" placeholder="Price" oninput="calculateBilling()">
                    <div class="action-area">
                        <button type="button" class="btn-add-inline" onclick="addNewRow()">+ Add More</button>
                    </div>
                </div>
            </div>

            <div class="summary-grid">
                <div class="summary-box">
                    <label>Total Amount</label>
                    <input type="number" step="0.01" name="total_amount" id="show_total" class="total-green" placeholder="0.00" oninput="calculateBilling()">
                </div>
                <div class="summary-box">
                    <label>Paid Amount</label>
                    <input type="number" step="0.01" name="paid_amount" id="get_paid" placeholder="0.00" oninput="calculateBilling()">
                </div>
                <div class="summary-box">
                    <label>Total Due</label>
                    <input type="number" name="total_due" id="show_due" class="due-red" readonly placeholder="0.00">
                </div>
            </div>
            <button type="submit" class="btn-save">Save Billing Information</button>
        </form>
    </div>
</main>

<script>
    function addNewRow() {
        const container = document.getElementById('dynamic-items');
        const rows = container.getElementsByClassName('item-row');
        const lastRowAction = rows[rows.length - 1].querySelector('.action-area');
        lastRowAction.innerHTML = `<button type="button" class="btn-remove" onclick="removeThisRow(this)">✕</button>`;
        const newRow = document.createElement('div');
        newRow.className = 'item-row';
        newRow.innerHTML = `
            <input type="text" name="items[]" placeholder="Item Name">
            <input type="number" step="0.01" name="prices[]" class="item-price" placeholder="Price" oninput="calculateBilling()">
            <div class="action-area">
                <button type="button" class="btn-add-inline" onclick="addNewRow()">+ Add More</button>
            </div>
        `;
        container.appendChild(newRow);
    }
    function removeThisRow(btn) { btn.closest('.item-row').remove(); calculateBilling(); }
    function calculateBilling() {
        let itemsTotal = 0;
        const prices = document.getElementsByClassName('item-price');
        for (let i = 0; i < prices.length; i++) { itemsTotal += parseFloat(prices[i].value) || 0; }
        const totalField = document.getElementById('show_total');
        if(itemsTotal > 0) { totalField.value = itemsTotal.toFixed(2); }
        const currentTotal = parseFloat(totalField.value) || 0;
        const paid = parseFloat(document.getElementById('get_paid').value) || 0;
        const due = currentTotal - paid;
        document.getElementById('show_due').value = due.toFixed(2);
    }
</script>

<?php include('../includes/footer.php'); ?>