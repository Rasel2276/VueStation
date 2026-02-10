<?php 
    // Path: payroll2/admindashboard/pages/manage_notes.php
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

    // --- DELETE LOGIC ---
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
        $note_id = $_GET['id'];
        $stmt = $conn->prepare("DELETE FROM notes WHERE id = ? AND admin_id = ?");
        $stmt->bind_param("ii", $note_id, $admin_id);
        
        if ($stmt->execute()) {
            header("Location: manage_notes.php?msg=Deleted");
            exit;
        }
    }

    $query = "SELECT * FROM notes WHERE admin_id = '$admin_id' ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);

    include('../includes/header.php'); 
    include('../includes/sidebar.php'); 
?>

<style>
    /* --- DESKTOP & GENERAL (NO PIXEL CHANGE) --- */
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
    .dropdown-content a, .dropdown-content button { color: #adb5bd; padding: 10px 12px; text-decoration: none; display: block; font-size: 13px; text-align: left; width: 100%; background: none; border: none; cursor: pointer; }
    .dropdown-content a:hover, .dropdown-content button:hover { background-color: #0f1015; color: #4BB543; }

    /* --- IMPROVED MODAL DESIGN --- */
    #noteModal {
        display: none; 
        position: fixed; 
        z-index: 99999; 
        left: 0; top: 0; 
        width: 100%; height: 100%; 
        background-color: rgba(0,0,0,0.85);
        overflow: auto;
        align-items: center; 
        justify-content: center;
    }
    #noteModal.show-modal {
        display: flex !important;
    }
    .modal-dialog-custom {
        width: 90%;
        max-width: 500px;
        background: #191c24;
        border-radius: 15px;
        border: 2px solid #4BB543;
        box-shadow: 0 0 20px rgba(75, 181, 67, 0.2);
        overflow: hidden;
        margin: auto;
    }
    .modal-header { 
        padding: 20px; 
        border-bottom: 1px solid #2c2e33; 
        text-align: center;
        position: relative;
    }
    .modal-body { 
        padding: 30px; 
        color: #adb5bd; 
        line-height: 1.6; 
        min-height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        font-size: 15px;
    }
    .modal-footer { 
        padding: 15px; 
        border-top: 1px solid #2c2e33; 
        text-align: center; 
    }
    .close-modal { 
        position: absolute;
        right: 15px;
        top: 15px;
        color: #ff4d4f; 
        font-size: 22px; 
        cursor: pointer; 
        background: none; 
        border: none; 
    }
    #modalContent {
        white-space: pre-wrap;
        word-break: break-word;
    }

    /* --- MOBILE OPTIMIZATION (FIXED OVERLAP) --- */
    @media (max-width: 768px) {
        .content-wrapper { padding: 10px !important; } 
        .approval-container { padding: 15px !important; margin: 10px auto !important; width: 100% !important; box-sizing: border-box; border-radius: 8px; }
        .approval-table thead { display: none; }
        .approval-table, .approval-table tbody, .approval-table tr, .approval-table td { display: block; width: 100% !important; box-sizing: border-box; }
        .approval-table tr { margin-bottom: 20px; border: 1px solid #353a45; border-radius: 8px; background: #12151e; overflow: visible !important; padding: 5px 0; }
        
        .approval-table td { 
            text-align: right; 
            padding: 12px 15px; 
            position: relative; 
            border-bottom: 1px solid #2c2e33; 
            display: flex; 
            justify-content: space-between; /* Space between Label and Content */
            align-items: flex-start; /* Align to top if text is long */
            word-break: break-word;
            white-space: normal;
        }
        
        /* Label Styling */
        .approval-table td::before { 
            content: attr(data-label); 
            font-weight: 700; 
            color: #4BB543; 
            text-transform: uppercase; 
            font-size: 11px; 
            min-width: 100px; /* Fixed width for label to prevent overlap */
            text-align: left;
            flex-shrink: 0; /* Label will not shrink */
        }
        
        .approval-table td:last-child { border-bottom: none; }
        .dropdown-content { right: 0; }
        .modal-dialog-custom { width: 85%; }
    }
</style>

<main class="main-content">
    <div class="main-panel">
        <div class="content-wrapper">
            <h3 style="margin-bottom: 10px; color: #fff; font-weight: 600; padding-left: 5px;">Manage My Notes</h3>

            <div class="approval-container">
                <div class="table-responsive">
                    <table class="approval-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Subject</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($result) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td data-label="Date"><?php echo date('d M, Y', strtotime($row['created_at'])); ?></td>
                                    <td data-label="Subject"><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td data-label="Description">
                                        <?php 
                                            $desc = htmlspecialchars($row['content']);
                                            echo (strlen($desc) > 40) ? substr($desc, 0, 40).'...' : $desc; 
                                        ?>
                                    </td>
                                    <td data-label="Action" style="overflow: visible;">
                                        <div class="action-dropdown">
                                            <button type="button" class="dropbtn" onclick="toggleDropdown(event, this)">
                                                Manage <i class="mdi mdi-chevron-down"></i>
                                            </button>
                                            <div class="dropdown-content">
                                                <button type="button" onclick="viewNote('<?php echo htmlspecialchars(addslashes($row['title'])); ?>', '<?php echo htmlspecialchars(addslashes($row['content'])); ?>')">
                                                    <i class="mdi mdi-eye"></i> View Full Note
                                                </button>
                                                <a href="manage_notes.php?action=delete&id=<?php echo $row['id']; ?>" 
                                                   style="color: #ff4d4f;" 
                                                   onclick="return confirm('Delete this note forever?')">
                                                   <i class="mdi mdi-delete"></i> Delete Note
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="4" style="text-align: center; padding: 30px;">No notes found in your notebook.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<div id="noteModal">
  <div class="modal-dialog-custom">
      <div class="modal-header">
        <button type="button" onclick="closeNote()" class="close-modal">&times;</button>
        <h4 id="modalTitle" style="color: #4BB543; margin:10px 0 0 0; font-weight:700; text-transform: uppercase; letter-spacing: 1px;"></h4>
      </div>
      <div class="modal-body">
        <div id="modalContent"></div>
      </div>
      <div class="modal-footer">
        <button type="button" onclick="closeNote()" style="background: #4BB543; border: none; color:white; padding: 10px 25px; border-radius:30px; cursor:pointer; font-weight:600; font-size:13px;">GOT IT</button>
      </div>
  </div>
</div>

<script>
function viewNote(title, content) {
    document.getElementById('modalTitle').innerText = title;
    document.getElementById('modalContent').innerText = content;
    document.getElementById('noteModal').classList.add("show-modal");
    document.body.style.overflow = "hidden"; 
}

function closeNote() {
    document.getElementById('noteModal').classList.remove("show-modal");
    document.body.style.overflow = "auto"; 
}

window.onclick = function(event) {
    var modal = document.getElementById('noteModal');
    if (event.target == modal) {
        closeNote();
    }
}

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