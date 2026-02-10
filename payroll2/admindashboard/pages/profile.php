<?php 
    session_start();
    require_once '../../config.php'; 

    if (!isset($_SESSION['auth_user'])) {
        header("Location: ../../login.php");
        exit;
    }

    $user_id = isset($_SESSION['auth_user_id']) ? $_SESSION['auth_user_id'] : $_SESSION['id'];
    
    $message = "";
    if (isset($_SESSION['success_msg'])) {
        $message = "<div class='alert success'>" . $_SESSION['success_msg'] . "</div>";
        unset($_SESSION['success_msg']);
    }
    if (isset($_SESSION['error_msg'])) {
        $message = "<div class='alert error'>" . $_SESSION['error_msg'] . "</div>";
        unset($_SESSION['error_msg']);
    }

    if (isset($_POST['update_profile'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
        $password = $_POST['password'];
        
        $old_image = $_POST['old_image'];
        $profile_image = $old_image;

        if (isset($_FILES['profile_image']['name']) && $_FILES['profile_image']['name'] != "") {
            $target_dir = "../../uploads/profile/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            $file_ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
            $new_filename = "user_" . $user_id . "_" . time() . "." . $file_ext;
            $target_file = $target_dir . $new_filename;

            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
                $profile_image = "uploads/profile/" . $new_filename;
                $_SESSION['profile_image'] = $profile_image;
            }
        }

        if (!empty($password)) {
            $hashed_pass = password_hash($password, PASSWORD_BCRYPT);
            $update_query = "UPDATE users SET name='$name', email='$email', mobile='$mobile', password='$hashed_pass', profile_image='$profile_image' WHERE id='$user_id'";
        } else {
            $update_query = "UPDATE users SET name='$name', email='$email', mobile='$mobile', profile_image='$profile_image' WHERE id='$user_id'";
        }

        if (mysqli_query($conn, $update_query)) {
            $_SESSION['name'] = $name;
            $_SESSION['success_msg'] = "Profile Updated Successfully!";
            header("Location: profile.php");
            exit;
        } else {
            $_SESSION['error_msg'] = "Error: " . mysqli_error($conn);
            header("Location: profile.php");
            exit;
        }
    }

    $fetch_user = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
    $user_data = mysqli_fetch_assoc($fetch_user);

    $u_name = $user_data['name'] ?? '';
    $u_email = $user_data['email'] ?? '';
    $u_mobile = $user_data['mobile'] ?? '';
    $u_image = $user_data['profile_image'] ?? 'https://via.placeholder.com/150';

    include('../includes/header.php'); 
    include('../includes/sidebar.php'); 
?>

<style>
.profile-card { background: #191C24; padding: 30px; border-radius: 12px; max-width: 900px; margin: 10px auto 30px; color: #fff; border: 1px solid #2c2e33; box-sizing:border-box; }
.profile-title-container { text-align: center; margin-bottom: 25px; }
.profile-title-container h3 { font-weight: 600; color: #fff; border-bottom: 2px solid #4BB543; display: inline-block; padding-bottom: 5px; margin-top: 0; }

.profile-flex { display: flex; flex-wrap: wrap; gap: 40px; align-items: flex-start; width:100%; box-sizing:border-box; }

.profile-left { flex: 1; min-width: 250px; text-align: center; border-right: 1px solid #2c2e33; padding-right: 20px; margin-top: -10px; box-sizing:border-box; }

.img-wrapper { width: 180px; height: 180px; border-radius: 50%; border: 4px solid #4BB543; overflow: hidden; background: #2A3038; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; }
.img-wrapper img { max-width: 100%; max-height: 100%; object-fit: contain; display: block; }

.profile-right { flex: 2; min-width: 300px; padding-right: 15px; box-sizing:border-box; }

.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; width:100%; }

.form-group { margin-bottom: 20px; width:100%; box-sizing:border-box; }

.form-group label { color: #4BB543; font-weight: 600; font-size: 12px; text-transform: uppercase; display: block; margin-bottom: 8px; }

.form-control { width: 100%; max-width:100%; padding: 12px; background: #2A3038; border: 1px solid #2c2e33; border-radius: 6px; color: #fff; outline: none; transition: 0.3s; box-sizing: border-box; }

.form-control:focus { border-color: #4BB543; box-shadow: 0 0 5px rgba(75, 181, 67, 0.2); }

.btn-update { background: #4BB543; color: #fff; border: none; padding: 14px 30px; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 16px; transition: 0.3s; width: 100%; box-sizing:border-box; }

.btn-update:hover { background: #3da336; transform: translateY(-2px); }

.alert { padding: 15px; border-radius: 6px; margin-bottom: 25px; text-align: center; font-size: 14px; width: 100%; box-sizing:border-box; }

.success { background: rgba(75, 181, 67, 0.1); color: #4BB543; border: 1px solid #4BB543; }
.error { background: rgba(255, 77, 79, 0.1); color: #ff4d4f; border: 1px solid #ff4d4f; }

/* ================= MOBILE PERFECT FIX ================= */
@media (max-width: 768px) {

    html, body {
        overflow-x: hidden;
    }

    .content-wrapper {
        padding: 10px !important;
        width: 100% !important;
        box-sizing: border-box;
    }

    .profile-card {
        margin: 10px auto !important;
        padding: 20px !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box;
    }

    .profile-flex {
        flex-direction: column;
        width: 100%;
        gap: 20px;
    }

    .profile-left {
        border-right: none;
        padding-right: 0;
        border-bottom: 1px solid #2c2e33;
        padding-bottom: 20px;
        width: 100%;
        min-width: 100%;
    }

    .profile-right {
        padding-right: 0;
        width: 100%;
        min-width: 100%;
    }

    .form-row {
        display: block;
        width: 100%;
    }

    .form-group,
    .form-control,
    .btn-update {
        width: 100% !important;
        max-width: 100% !important;
    }
}
</style>

<main class="main-content">
<div class="content-wrapper">
<div class="profile-card">

<div class="profile-title-container">
<h3>Profile Settings</h3>
</div>

<?php echo $message; ?>

<form action="" method="POST" enctype="multipart/form-data">
<div class="profile-flex">

<div class="profile-left">
<div class="img-wrapper">
<img src="<?php echo (strpos($u_image, 'http') !== false) ? $u_image : '../../'.$u_image; ?>" alt="Profile">
</div>

<input type="hidden" name="old_image" value="<?php echo $u_image; ?>">

<div class="form-group" style="text-align:left;">
<label>Change Profile Photo</label>
<input type="file" name="profile_image" class="form-control" style="padding:8px;">
</div>

<p style="font-size:12px;color:#6c7293;margin-top:10px;">
Upload a square image for best fit.
</p>
</div>

<div class="profile-right">

<div class="form-row">
<div class="form-group">
<label>Full Name</label>
<input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($u_name); ?>" required>
</div>

<div class="form-group">
<label>Mobile Number</label>
<input type="text" name="mobile" class="form-control" value="<?php echo htmlspecialchars($u_mobile); ?>" required>
</div>
</div>

<div class="form-group">
<label>Email Address</label>
<input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($u_email); ?>" required>
</div>

<div class="form-group">
<label>New Password</label>
<input type="password" name="password" class="form-control" placeholder="Leave blank to keep old password">
</div>

<button type="submit" name="update_profile" class="btn-update">
Save Changes
</button>

</div>
</div>
</form>

</div>
</div>
</main>

<?php include('../includes/footer.php'); ?>
