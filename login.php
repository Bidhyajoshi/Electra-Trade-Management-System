<?php
require_once 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, full_name, email, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                
                if ($user['role'] === 'admin') {
                    header("Location: admin/index.php");
                } else {
                    header("Location: index.php");
                }
                exit;
            } else {
                $error = 'Incorrect email or password. Please try again.';
            }
        } else {
            $error = 'Incorrect email or password. Please try again.';
        }
    } else {
        $error = 'Please fill in all fields.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Joshi Trading Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--navy-blue), #003366);
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            /* Enhanced glassmorphism */
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            border-radius: 20px;
            padding: 3rem;
        }
        .form-control {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        .alert-danger {
            background-color: rgba(220, 38, 38, 0.2);
            border-left: 4px solid var(--danger);
            color: #fca5a5;
            padding: 1rem;
            border-radius: 4px;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>

<div class="glass-panel login-card reveal active">
    <h2 style="text-align: center; margin-bottom: 2rem; color: #fff;">Welcome Back</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <div class="form-group">
            <label for="email" style="color: #cbd5e1;">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="Enter your registered email" required autofocus>
        </div>
        <div class="form-group">
            <label for="password" style="color: #cbd5e1;">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1.5rem; border-radius: 8px;">Login</button>
    </form>
    
    <div style="text-align: center; margin-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1.5rem;">
        <p style="color: #cbd5e1; font-size: 0.9rem; margin-bottom: 0.5rem;">Don't have an account?</p>
        <a href="register.php" style="color: var(--electric-gold); font-weight: 600;">Register here</a>
    </div>
    <div style="text-align: center; margin-top: 1rem;">
        <a href="index.php" style="color: rgba(255,255,255,0.5); font-size: 0.8rem;">&larr; Back to Home</a>
    </div>
</div>

</body>
</html>
