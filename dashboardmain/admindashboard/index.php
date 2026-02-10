<?php 
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
                    <p class="amount text-success">150</p>
                </div>
                <div class="trend-icon bg-success-soft"><span class="mdi mdi-arrow-top-right"></span></div>
            </div>
            <h6 class="card-label">Total Employee</h6>
        </div>

        <div class="stat-card">
            <div class="card-top">
                <div class="value-box">
                    <h3><i class="mdi mdi-cash-multiple text-white"></i></h3>
                    <p class="amount text-success">৳ 5.50k</p>
                </div>
                <div class="trend-icon bg-success-soft"><span class="mdi mdi-arrow-top-right"></span></div>
            </div>
            <h6 class="card-label">Total Paid</h6>
        </div>

        <div class="stat-card">
            <div class="card-top">
                <div class="value-box">
                    <h3><i class="mdi mdi-calendar-minus text-white"></i></h3>
                    <p class="amount text-danger">-12</p>
                </div>
                <div class="trend-icon bg-danger-soft"><span class="mdi mdi-arrow-bottom-left"></span></div>
            </div>
            <h6 class="card-label">Leave</h6>
        </div>

        <div class="stat-card">
            <div class="card-top">
                <div class="value-box">
                    <h3><i class="mdi mdi-account-off text-white"></i></h3>
                    <p class="amount text-danger">05</p>
                </div>
                <div class="trend-icon bg-danger-soft"><span class="mdi mdi-arrow-bottom-left"></span></div>
            </div>
            <h6 class="card-label">Absent</h6>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>