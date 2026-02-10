<?php 
    include('../includes/header.php'); 
    include('../includes/sidebar.php'); 
?>

<main class="main-content">
   <div class="main-panel">
    <div class="content-wrapper">
        <h3 style="margin-bottom: 30px; color: #fff;">Add Employee</h3>

        <?php if(isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <?php if(isset($success)): ?>
            <p class="success"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="employee-form">
            <div class="input-grid">
                <div class="floating-label">
                    <input type="text" name="name" required placeholder=" ">
                    <label>Name</label>
                </div>
                <div class="floating-label">
                    <input type="email" name="email" required placeholder=" ">
                    <label>Email</label>
                </div>
                <div class="floating-label">
                    <input type="text" name="contact_no" placeholder=" ">
                    <label>Contact Number</label>
                </div>
            </div>

            <div class="input-grid">
                <div class="floating-label">
                    <input type="text" name="designation" placeholder=" ">
                    <label>Designation</label>
                </div>
                <div class="floating-label">
                    <input type="number" step="0.01" name="basic_salary" placeholder=" ">
                    <label>Basic Salary</label>
                </div>
                <div class="floating-label">
                    <input type="text" name="bank_name" placeholder=" ">
                    <label>Bank Name</label>
                </div>
            </div>

            <div class="input-grid">
                <div class="floating-label">
                    <input type="text" name="bank_account" placeholder=" ">
                    <label>Bank Account</label>
                </div>
                <div class="floating-label">
                    <textarea name="present_address" placeholder=" "></textarea>
                    <label>Present Address</label>
                </div>
                <div class="floating-label">
                    <textarea name="permanent_address" placeholder=" "></textarea>
                    <label>Permanent Address</label>
                </div>
            </div>

            <div class="row">
                <div class="col-full">
                    <div class="floating-label" style="max-width: 95%;">
                        <input type="file" name="profile_image" accept="image/*">
                        <label style="top: -10px; font-size: 12px; color: #4BB543;">Profile Image</label>
                    </div>
                </div>
            </div>

            <div class="submit-row" style="margin-top: 30px;">
                <button type="submit">Create Employee</button>
            </div>
        </form>

        <style>
            .employee-form {
                background: #191C24;
                padding: 40px; /* প্যাডিং বাড়ানো হয়েছে */
                border-radius: 10px;
                max-width: 100%; /* কন্টেইনার ফুল উইথ থাকবে */
            }

            /* গ্রিড সিস্টেম ব্যবহার করে গ্যাপ বাড়ানো হয়েছে */
            .input-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr); /* এক লাইনে ৩টি ফিল্ড */
                gap: 40px; /* ফিল্ডগুলোর মাঝখানের গ্যাপ বাড়ানো হয়েছে */
                margin-bottom: 35px; /* সারিগুলোর মাঝখানের গ্যাপ */
                padding-right: 5%; /* ডান দিকে মার্জিন/গ্যাপ রাখার জন্য */
            }

            .col-full {
                width: 100%;
                padding-right: 5%;
            }

            .floating-label {
                position: relative;
            }

            .floating-label input,
            .floating-label textarea {
                width: 100%;
                padding: 15px 12px; /* ইনপুটের ভেতরে স্পেস বাড়ানো হয়েছে */
                border: 1px solid #353a45;
                border-radius: 5px;
                background: transparent;
                color: #fff;
                outline: none;
            }

            .floating-label textarea {
                height: 60px;
                resize: none;
            }

            .floating-label label {
                position: absolute;
                left: 12px;
                top: 15px;
                color: #6c7293;
                background: #191C24;
                padding: 0 5px;
                transition: .2s;
                pointer-events: none;
            }

            .floating-label input:focus + label,
            .floating-label input:not(:placeholder-shown) + label,
            .floating-label textarea:focus + label,
            .floating-label textarea:not(:placeholder-shown) + label {
                top: -10px;
                font-size: 12px;
                color: #4BB543;
            }

            button {
                background: #4BB543;
                color: #fff;
                border: none;
                padding: 12px 30px;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
                font-weight: 500;
            }

            .error { color: #ff4d4f; font-weight: bold; margin-bottom: 15px; }
            .success { color: #4BB543; font-weight: bold; margin-bottom: 15px; }

            /* রেসপন্সিভ: স্ক্রিন ছোট হলে ১টি করে ফিল্ড আসবে */
            @media (max-width: 992px) {
                .input-grid {
                    grid-template-columns: 1fr;
                    gap: 20px;
                    padding-right: 0;
                }
            }
        </style>
    </div>
</div>
</main>

<?php include('../includes/footer.php'); ?>