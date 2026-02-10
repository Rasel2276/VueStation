<?php 
    session_start();
    require_once '../../config.php'; 

    $admin_id = $_SESSION['auth_user_id'] ?? $_SESSION['id'];

    if (isset($_GET['id'])) {
        $customer_id = mysqli_real_escape_string($conn, $_GET['id']);
        
        $res = mysqli_query($conn, "SELECT * FROM customers WHERE id = '$customer_id' AND admin_id = '$admin_id'");
        $customer = mysqli_fetch_assoc($res);

        if (!$customer) { echo "Customer not found!"; exit; }

        $items_res = mysqli_query($conn, "SELECT * FROM bill_items WHERE customer_id = '$customer_id' ORDER BY created_at DESC");
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_ledger'])) {
        $new_paid = !empty($_POST['new_paid']) ? (float)$_POST['new_paid'] : 0;
        $added_amount = 0;
        $item_names_array = [];

        if (isset($_POST['items'])) {
            foreach ($_POST['items'] as $key => $name) {
                $price = !empty($_POST['prices'][$key]) ? (float)$_POST['prices'][$key] : 0;
                if (!empty(trim($name))) {
                    $item_names_array[] = trim($name) . " (".number_format($price, 2).")";
                    $added_amount += $price;
                }
            }
        }

        if ($added_amount > 0 || $new_paid > 0) {
            $final_item_label = "";
            if (!empty($item_names_array)) {
                $final_item_label = implode(", ", $item_names_array);
            }
            if ($new_paid > 0) {
                $final_item_label .= (!empty($final_item_label) ? " | " : "") . "Payment Received: " . number_format($new_paid, 2);
            }

            $row_price_effect = $added_amount - $new_paid;
            $safe_label = mysqli_real_escape_string($conn, $final_item_label);
            $customer_name = mysqli_real_escape_string($conn, $customer['customer_name']);

            mysqli_query($conn, "INSERT INTO bill_items (customer_id, customer_name, item_name, price) 
                                VALUES ('$customer_id', '$customer_name', '$safe_label', '$row_price_effect')");
        }

        $final_total = $customer['total_amount'] + $added_amount;
        $final_paid = $customer['paid_amount'] + $new_paid;
        $final_due = $final_total - $final_paid;

        mysqli_query($conn, "UPDATE customers SET 
                            total_amount = '$final_total', 
                            paid_amount = '$final_paid', 
                            total_due = '$final_due' 
                            WHERE id = '$customer_id'");

        echo "<script>alert('Ledger Updated Successfully!'); window.location.href='edit_customer.php?id=$customer_id';</script>";
    }

    // Header and Sidebar Included exactly where they belong
    include('../includes/header.php'); 
    include('../includes/sidebar.php'); 
?>

<style>
    /* Desktop & Laptop View (Large Screens) */
    .content-wrapper { padding: 20px; }
    .ledger-grid { display: grid; grid-template-columns: 1.6fr 1fr; gap: 20px; align-items: start; }
    .card { background: #191c24; border-radius: 12px; padding: 25px; color: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.3); margin-bottom: 20px; }
    
    /* Transaction History */
    .log-container { max-height: 450px; overflow-y: auto; margin-top: 15px; padding-right: 5px; }
    .log-item { 
        background: #0f1116; border-left: 4px solid #ffab00; padding: 12px 15px; 
        margin-bottom: 10px; border-radius: 0 8px 8px 0; display: flex; 
        justify-content: space-between; align-items: center; transition: 0.3s;
    }
    .log-item.is-payment { border-left-color: #00d25b; }
    .log-item.is-due { border-left-color: #ff4d4f; }
    
    .date-text { font-size: 11px; color: #6c7293; margin-bottom: 2px; display: block; }
    .particulars { font-weight: 500; font-size: 14px; display: block; color: #e4e6ef; }
    .amount-text { font-size: 15px; font-weight: 700; white-space: nowrap; }
    .payment-text { color: #00d25b; }
    .item-text { color: #ff4d4f; }

    /* Forms & Inputs */
    .input-box { background: #2a3038; border: 1px solid #353a45; color: #fff; padding: 10px; border-radius: 5px; width: 100%; margin-bottom: 10px; box-sizing: border-box; }
    .summary-card { background: #0f1116; padding: 15px; border-radius: 10px; text-align: center; border-bottom: 3px solid #353a45; }
    .btn-update { background: #00d25b; border: none; padding: 12px; color: #fff; width: 100%; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 16px; }
    .btn-add-row { background: #ffab00; border: none; color: #fff; padding: 5px 12px; border-radius: 4px; cursor: pointer; height: 40px; }

    /* Info Lines */
    .info-line { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
    .info-line i { color: #ffab00; font-size: 20px; width: 25px; text-align: center; }
    .info-line span.label { color: #6c7293; font-size: 14px; min-width: 60px; }
    .info-line span.value { color: #fff; font-size: 18px; font-weight: 600; word-break: break-word; }

    .log-container::-webkit-scrollbar { width: 5px; }
    .log-container::-webkit-scrollbar-thumb { background: #353a45; border-radius: 10px; }

    /* MOBILE & TABLET RESPONSIVE */
    @media screen and (max-width: 991px) {
        .content-wrapper { padding: 10px; }
        .ledger-grid { grid-template-columns: 1fr; gap: 15px; }
        .card { padding: 15px; margin-bottom: 15px; }
        
        .summary-grid { display: grid; grid-template-columns: repeat(3, 1fr) !important; gap: 8px !important; }
        .summary-card { padding: 10px 5px; }
        .summary-card div { font-size: 14px !important; }
        
        .info-line span.value { font-size: 16px; }
        .particulars { font-size: 12px; }
        .amount-text { font-size: 13px; margin-left: 5px; }
        
        .input-group-mobile { display: flex; flex-direction: row !important; gap: 5px !important; }
        .input-box { font-size: 14px; padding: 12px 8px; }
    }

    @media screen and (max-width: 375px) {
        .summary-grid { gap: 5px !important; }
        .summary-card small { font-size: 8px; }
        .summary-card div { font-size: 12px !important; }
        .amount-text { font-size: 11px; }
    }
</style>

<main class="main-content">
    <div class="content-wrapper">
        <h3 style="margin-bottom: 25px; color: #fff; font-weight: 300;">Customer <span style="color: #ffab00;">Ledger</span></h3>

        <div class="ledger-grid">
            <div class="card">
                <div style="background: rgba(255,171,0,0.05); padding: 15px; border-radius: 10px; border: 1px dashed #353a45; margin-bottom: 20px;">
                    <div class="info-line">
                        <i class="mdi mdi-account"></i>
                        <span class="label">Name:</span>
                        <span class="value"><?php echo htmlspecialchars($customer['customer_name']); ?></span>
                    </div>
                    <div class="info-line">
                        <i class="mdi mdi-phone"></i>
                        <span class="label">Mobile:</span>
                        <span class="value" style="color: #00d25b;"><?php echo htmlspecialchars($customer['mobile']); ?></span>
                    </div>
                </div>

                <div class="summary-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 25px;">
                    <div class="summary-card" style="border-bottom-color: #ffab00;">
                        <small style="color: #6c7293; text-transform: uppercase; font-size: 10px;">Total Bill</small>
                        <div style="font-weight: 700; font-size: 18px; margin-top: 5px;"><?php echo number_format($customer['total_amount'], 2); ?></div>
                    </div>
                    <div class="summary-card" style="border-bottom-color: #00d25b;">
                        <small style="color: #6c7293; text-transform: uppercase; font-size: 10px;">Total Paid</small>
                        <div style="font-weight: 700; font-size: 18px; margin-top: 5px; color: #00d25b;"><?php echo number_format($customer['paid_amount'], 2); ?></div>
                    </div>
                    <div class="summary-card" style="border-bottom-color: #ff4d4f; background: rgba(255,77,79,0.05);">
                        <small style="color: #6c7293; text-transform: uppercase; font-size: 10px;">Current Due</small>
                        <div style="font-weight: 700; font-size: 18px; margin-top: 5px; color: #ff4d4f;"><?php echo number_format($customer['total_due'], 2); ?></div>
                    </div>
                </div>

                <h4 style="color: #ffab00; margin: 0 0 10px 0; font-size: 16px; border-bottom: 1px solid #2c2e33; padding-bottom: 10px;">Transaction History</h4>

                <div class="log-container">
                    <?php while($item = mysqli_fetch_assoc($items_res)): 
                        $isPayment = ($item['price'] <= 0); 
                    ?>
                    <div class="log-item <?php echo $isPayment ? 'is-payment' : 'is-due'; ?>">
                        <div style="max-width: 70%;">
                            <span class="date-text"><?php echo date('d M Y | h:i A', strtotime($item['created_at'])); ?></span>
                            <span class="particulars"><?php echo htmlspecialchars($item['item_name']); ?></span>
                        </div>
                        <div class="amount-text <?php echo $isPayment ? 'payment-text' : 'item-text'; ?>">
                            <?php 
                                if($item['price'] <= 0) echo "Paid: " . number_format(abs($item['price']), 2);
                                else echo "Due: " . number_format($item['price'], 2);
                            ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <div class="card">
                <h4 style="color: #00d25b; margin-bottom: 25px; border-bottom: 1px solid #2c2e33; padding-bottom: 10px;">New Entry</h4>
                <form action="" method="POST">
                    <div id="item-fields">
                        <label style="font-size: 12px; color: #6c7293;">Items Info</label>
                        <div class="input-group-mobile" style="display: flex; gap: 8px; margin-bottom: 10px;">
                            <input type="text" name="items[]" class="input-box" placeholder="Item Name" style="flex: 2; margin-bottom: 0;">
                            <input type="number" step="0.01" name="prices[]" class="input-box item-p" placeholder="Price" style="flex: 1; margin-bottom: 0;">
                            <button type="button" class="btn-add-row" onclick="addRow()">+</button>
                        </div>
                    </div>

                    <div style="margin-top: 15px;">
                        <label style="font-size: 12px; color: #6c7293;">Payment Amount (if any)</label>
                        <input type="number" name="new_paid" class="input-box" style="border: 1px solid #00d25b; background: rgba(0,210,91,0.05); width: 100%; display: block;" placeholder="0.00">
                    </div>

                    <input type="hidden" name="update_ledger" value="1">
                    <button type="submit" class="btn-update" style="margin-top: 30px;">Update Ledger</button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    function addRow() {
        const div = document.createElement('div');
        div.className = "input-group-mobile";
        div.style = "display: flex; gap: 8px; margin-bottom: 10px;";
        div.innerHTML = `
            <input type="text" name="items[]" class="input-box" placeholder="Item Name" style="flex: 2; margin-bottom: 0;">
            <input type="number" step="0.01" name="prices[]" class="input-box item-p" placeholder="Price" style="flex: 1; margin-bottom: 0;">
            <button type="button" style="background:#ff4d4f; border:none; color:#fff; padding:5px 12px; border-radius:4px; height:40px;" onclick="this.parentElement.remove();">✕</button>
        `;
        document.getElementById('item-fields').appendChild(div);
    }
</script>

<?php include('../includes/footer.php'); ?>