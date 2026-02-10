<?php session_start(); ?>
<?php require_once 'config.php'; ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Najmul Store </title>
    <style>
        :root {
            --bg-dark: #0f1015;
            --card-dark: #191c24;
            --accent-green: #00b894;
            --text-gray: #6c7293;
            --border-color: #2c2e33;
        }
        body {
            background: radial-gradient(circle at center, #1e2129 0%, #0f1015 100%);
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 15px; /* Mobile spacing added */
            overflow: hidden;
            box-sizing: border-box;
        }
        .login-box {
            position: relative;
            width: 100%; /* Changed for responsiveness */
            max-width: 380px; /* Kept your original width */
            background: var(--card-dark);
            border-radius: 15px;
            padding: 30px;
            box-sizing: border-box;
            box-shadow: 0 15px 35px rgba(0,0,0,0.6);
            overflow: hidden;
            border: 1px solid var(--border-color);
        }
        .login-box::before {
            content: '';
            position: absolute;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: conic-gradient(transparent, transparent, transparent, var(--accent-green));
            animation: rotateBorder 4s linear infinite;
        }
        .login-box::after {
            content: '';
            position: absolute;
            inset: 2px;
            background: var(--card-dark);
            border-radius: 13px;
        }
        @keyframes rotateBorder {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .content-wrap { position: relative; z-index: 1; }
        .login-header h2 { text-align: center; color: #fff; margin: 0 0 20px 0; }
        .form-group { margin-bottom: 15px; }
        label { color: #adb5bd; font-size: 12px; display: block; margin-bottom: 5px; }
        
        /* Fixed input sizing for mobile */
        input { 
            width: 100%; 
            padding: 12px; 
            background: #2a3038; 
            border: 1px solid var(--border-color); 
            color: #fff; 
            border-radius: 8px; 
            box-sizing: border-box; 
            display: block;
        }
        
        button { 
            width: 100%; 
            padding: 12px; 
            background: var(--accent-green); 
            color: #fff; 
            border: none; 
            border-radius: 8px; 
            font-weight: 600; 
            cursor: pointer; 
            margin-top: 10px; 
            box-sizing: border-box;
        }
        
        .error-msg { color: #ff4d4d; font-size: 13px; text-align: center; margin-bottom: 10px; }
        .success-msg { color: var(--accent-green); font-size: 13px; text-align: center; margin-bottom: 10px; }
        .footer-link { text-align: center; margin-top: 15px; font-size: 12px; color: var(--text-gray); }
        .footer-link a { color: var(--accent-green); text-decoration: none; }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .login-box { padding: 25px 20px; }
            .login-header h2 { font-size: 20px; }
        }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="content-wrap">
            <div class="login-header"><h2>NAJMUL STORE</h2></div>
            <?php if(isset($_GET['error'])) echo '<div class="error-msg">'.$_GET['error'].'</div>'; ?>
            <?php if(isset($_GET['success'])) echo '<div class="success-msg">'.$_GET['success'].'</div>'; ?>
            
            <form method="POST" action="login_process.php">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit">Log In</button>
            </form>
            <div class="footer-link">
                Don't have an account? <br>
                <a href="register.php">Register as Admin</a>
            </div>
        </div>
    </div>
</body>
</html>