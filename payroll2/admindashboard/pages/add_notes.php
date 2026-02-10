<?php 
    // Path: payroll2/admindashboard/pages/notes.php
    session_start();
    require_once '../../config.php'; 

    if (!isset($_SESSION['auth_user'])) {
        header("Location: ../../login.php");
        exit;
    }

    // --- ADMIN ID AUTO-DETECT ---
    $admin_id = null;
    if (isset($_SESSION['auth_user_id'])) {
        $admin_id = $_SESSION['auth_user_id'];
    } elseif (isset($_SESSION['id'])) {
        $admin_id = $_SESSION['id'];
    }

    // Data Insert Logic
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_note'])) {
        if ($admin_id === null) {
            $_SESSION['status_msg'] = "session_error";
        } else {
            $title = mysqli_real_escape_string($conn, $_POST['title']);
            $content = mysqli_real_escape_string($conn, $_POST['content']);

            if (!empty($content)) {
                $query = "INSERT INTO notes (admin_id, title, content) VALUES ('$admin_id', '$title', '$content')";
                if (mysqli_query($conn, $query)) {
                    $_SESSION['status_msg'] = "success";
                } else {
                    $_SESSION['status_msg'] = "error";
                    $_SESSION['db_error'] = mysqli_error($conn);
                }
            } else {
                $_SESSION['status_msg'] = "empty";
            }
        }
        // REDIRECT TO PREVENT DUPLICATE ON REFRESH
        header("Location: add_notes.php");
        exit;
    }

    // Get Status Message from Session
    $status_msg = "";
    if (isset($_SESSION['status_msg'])) {
        $status_msg = $_SESSION['status_msg'];
        unset($_SESSION['status_msg']);
    }

    include('../includes/header.php'); 
    include('../includes/sidebar.php'); 
?>

<style>
    .content-wrapper { padding: 20px; }
    
    .note-wrapper {
        max-width: 1100px; 
        margin: 0 auto;
        background: #191c24;
        border-radius: 15px;
        position: relative;
        padding: 2px; 
        background: linear-gradient(135deg, #4BB543 0%, #3da33a 50%, #4BB543 100%);
        box-shadow: 0 15px 35px rgba(0,0,0,0.5);
        width: 100%; 
    }

    .note-inner {
        background: #191c24;
        border-radius: 13px;
        padding: 40px; 
    }

    .note-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        border-bottom: 1px solid #2a3038;
        padding-bottom: 15px;
    }

    .note-header i {
        font-size: 30px;
        color: #4BB543;
    }

    .note-header h3 {
        margin: 0;
        color: #fff;
        font-weight: 600;
        letter-spacing: 1px;
    }

    .form-group {
        margin-bottom: 25px;
        width: 100%; 
    }

    .form-group label {
        display: block;
        color: #8f94a6;
        margin-bottom: 10px;
        font-size: 13px;
        text-transform: uppercase;
        font-weight: 600;
    }

    .note-input {
        width: 100%;
        background: #0f1015;
        border: 1px solid #2a3038;
        border-radius: 10px;
        padding: 15px;
        color: #fff;
        font-size: 15px;
        transition: all 0.3s ease;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.3);
        box-sizing: border-box;
    }

    .note-input:focus {
        outline: none;
        border-color: #4BB543;
        background: #15181e;
        box-shadow: 0 0 10px rgba(75, 181, 67, 0.2);
    }

    .note-textarea {
        min-height: 150px; 
        resize: vertical; 
        line-height: 1.8;
    }

    .btn-save-note {
        background: linear-gradient(90deg, #4BB543, #3da33a);
        color: white;
        border: none;
        padding: 12px 40px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-left: auto;
        transition: all 0.4s;
        box-shadow: 0 5px 15px rgba(75, 181, 67, 0.3);
    }

    .btn-save-note:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 20px rgba(75, 181, 67, 0.4);
    }

    /* Message Styling */
    .status-alert {
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-weight: 600;
        text-align: center;
    }
    .status-success { background: rgba(75, 181, 67, 0.15); color: #4BB543; border: 1px solid #4BB543; }
    .status-danger { background: rgba(255, 77, 79, 0.15); color: #ff4d4f; border: 1px solid #ff4d4f; }

    /* --- MOBILE RESPONSIVE MOOD --- */
    @media (max-width: 768px) {
        .content-wrapper { padding: 10px 5px; }
        .note-wrapper { max-width: 100%; width: 100%; border-radius: 8px; }
        .note-inner { padding: 20px 15px; border-radius: 6px; }
        .note-header h3 { font-size: 14px; }
        .btn-save-note { width: 100%; justify-content: center; padding: 12px 20px; }
    }
</style>

<main class="main-content">
    <div class="content-wrapper">
        <div class="note-wrapper">
            <div class="note-inner">
                <div class="note-header">
                    <i class="mdi mdi-notebook-edit"></i>
                    <h3>PERSONAL NOTEBOOK</h3>
                </div>

                <?php if($status_msg == "success"): ?>
                    <div class="status-alert status-success">Note Saved Successfully!</div>
                <?php elseif($status_msg == "empty"): ?>
                    <div class="status-alert status-danger">Description cannot be empty!</div>
                <?php elseif($status_msg == "session_error"): ?>
                    <div class="status-alert status-danger">Error: Admin ID not found in session!</div>
                <?php elseif($status_msg == "error"): ?>
                    <div class="status-alert status-danger">Error: <?php echo $_SESSION['db_error'] ?? 'Unknown Error'; unset($_SESSION['db_error']); ?></div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="form-group">
                        <label><i class="mdi mdi-format-title"></i> Note Subject</label>
                        <input type="text" name="title" class="note-input" placeholder="What is this about?">
                    </div>

                    <div class="form-group">
                        <label><i class="mdi mdi-pen"></i> Detailed Description</label>
                        <textarea name="content" class="note-input note-textarea" required placeholder="Write your thoughts here..."></textarea>
                    </div>

                    <button type="submit" name="save_note" class="btn-save-note">
                        <span>Save to Cloud</span>
                        <i class="mdi mdi-cloud-upload"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include('../includes/footer.php'); ?>