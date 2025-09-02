<?php
session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simple authentication - in a real app, use proper password hashing
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Replace with secure authentication
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin_dashboard.php');
        exit;
    } else {
        $error_message = 'Invalid username or password';
    }
}

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin_dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Disaster Management System</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #2c3e50;
            color: white;
            padding: 10px 20px;
        }
        .navbar h1 {
            margin: 0;
            font-size: 24px;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .btn-login {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        .btn-login:hover {
            background-color: #2980b9;
        }
        .error-message {
            color: #e74c3c;
            margin-bottom: 15px;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Disaster Management System - Admin</h1>
    </div>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-login">Login</button>
        </form>
        <a href="index.php" class="back-link">Back to Homepage</a>
    </div>
</body>
</html>