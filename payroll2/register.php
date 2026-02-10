<?php require_once 'config.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Najmul Store | Admin Access</title>

    <style>
        :root {
            --bg-dark: #0f1015;
            --card-dark: #191c24;
            --accent-green: #00b894;
            --accent-hover: #009475;
            --text-gray: #6c7293;
            --border-color: #2c2e33;
        }

        body {
            background: radial-gradient(circle at center, #1e2129 0%, #0f1015 100%);
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 15px; /* Better spacing for mobile */
            color: #ffffff;
            box-sizing: border-box;
            overflow: hidden;
        }

        /* Compact Animated Border Container */
        .login-box {
            position: relative;
            width: 100%;
            max-width: 380px; /* Choto size optimized */
            background: var(--card-dark);
            border-radius: 15px;
            padding: 25px 30px; /* Reduced padding for compact look */
            box-sizing: border-box;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6);
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .login-box::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(transparent, transparent, transparent, var(--accent-green));
            animation: rotateBorder 4s linear infinite;
            z-index: 0;
        }

        .login-box::after {
            content: '';
            position: absolute;
            inset: 2px;
            background: var(--card-dark);
            border-radius: 13px;
            z-index: 0;
        }

        .login-header, form, .default-info {
            position: relative;
            z-index: 1;
        }

        @keyframes rotateBorder {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .login-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .login-header h2 {
            margin: 0;
            font-weight: 700;
            font-size: 22px; /* Slightly smaller for registration */
            letter-spacing: 1px;
            color: #ffffff;
            text-transform: uppercase;
        }

        .login-header p {
            color: var(--text-gray);
            font-size: 13px;
            margin-top: 5px;
        }

        .form-group {
            margin-bottom: 12px; /* Tighter spacing for more fields */
        }

        label {
            font-size: 12px;
            display: block;
            margin-bottom: 5px;
            color: #adb5bd;
            font-weight: 500;
        }

        input[type=email],
        input[type=password],
        input[type=text] {
            width: 100%;
            padding: 10px 14px; /* Slimmer inputs */
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background: #2a3038;
            color: #ffffff;
            font-size: 14px;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: var(--accent-green);
            background: #313741;
            box-shadow: 0 0 8px rgba(0, 184, 148, 0.2);
        }

        button {
            width: 100%;
            padding: 12px;
            background: var(--accent-green);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 8px;
            text-transform: uppercase;
        }

        button:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(0, 184, 148, 0.3);
        }

        .default-info {
            text-align: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid var(--border-color);
            font-size: 12px;
            color: var(--text-gray);
        }

        @media (max-width: 480px) {
            .login-box { padding: 20px; max-width: 100%; }
            .login-header h2 { font-size: 20px; }
        }
    </style>
</head>
<body>

<div class="login-box">
    <div class="login-header">
        <h2>REGISTER</h2>
        <p>Apply for Admin Access</p>
    </div>

    <form method="POST" action="register_process.php">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" placeholder="Enter your name" required>
        </div>
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="Enter email" required>
        </div>
        <div class="form-group">
            <label>Mobile Number</label>
            <input type="text" name="mobile" placeholder="Enter mobile number" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Choose password" required>
        </div>
        <button type="submit">Submit Request</button>
    </form>

    <div class="default-info">
        Already have an account? <br>
        <a href="index.php" style="color:var(--accent-green); text-decoration:none;"><strong>Back to Login</strong></a>
    </div>
</div>

</body>
</html>