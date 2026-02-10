<?php
session_start(); 
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        
        if ($user['status'] === 'pending') {
            header("Location: index.php?error=Access pending approval");
            exit;
        }

        // --- SESSION DATA UPDATE (IMAGE ADD KORA HOLO) ---
        $_SESSION['auth_user'] = $user;
        $_SESSION['id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        // EI LINE TI LAGBE HEADER-E IMAGE DEKHANOR JONNO
        $_SESSION['profile_image'] = $user['profile_image']; 

       
        if ($user['role'] === 'admin' || $user['role'] === 'superadmin') {
            header("Location: admindashboard/index.php");
        } 
        exit;

    } else {
        header("Location: index.php?error=Invalid email or password");
        exit;
    }
}
?>