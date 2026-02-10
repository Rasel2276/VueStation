<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name']; 
    $email = $_POST['email'];
    $mobile = $_POST['mobile']; 
    // পাসওয়ার্ড হ্যাশ করা হচ্ছে
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, mobile, password, role, status) VALUES (?, ?, ?, ?, 'admin', 'pending')");
    $stmt->bind_param("ssss", $name, $email, $mobile, $password);
    
    if ($stmt->execute()) {
        header("Location: index.php?success=Registration success! Wait for approval.");
    } else {
        header("Location: register.php?error=Email already exists");
    }
    exit;
}
?>