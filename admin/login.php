<?php
require_once '../includes/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if already logged in as admin
if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin') {
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
                if ($user['role'] === 'admin') {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['full_name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];
                    
                    header("Location: index.php");
                    exit;
                } else {
                    $error = 'Access denied. Admin privileges required.';
                }
            } else {
                $error = 'Incorrect email or password.';
            }
        } else {
            $error = 'Incorrect email or password.';
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
    <title>Admin Portal - Joshi Trading Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: radial-gradient(circle at top right, #001f3f, #000c18);
            margin: 0;
            overflow: hidden;
        }
        .admin-login-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
            position: relative;
        }
        .admin-login-card {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 193, 7, 0.2);
            border-radius: 24px;
            padding: 3.5rem 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 20px rgba(255, 193, 7, 0.1);
            position: relative;
            z-index: 1;
        }
        .admin-login-card::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, transparent, rgba(255, 193, 7, 0.3), transparent);
            border-radius: 24px;
            z-index: -1;
            opacity: 0.5;
        }
        .portal-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        .portal-header h1 {
            font-size: 1.8rem;
            color: #fff;
            margin-bottom: 0.5rem;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .portal-header p {
            color: var(--electric-gold);
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .form-control {
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            padding: 1rem 1.2rem;
            height: auto;
            font-size: 1rem;
            border-radius: 12px;
        }
        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.08);
            border-color: var(--electric-gold);
            box-shadow: 0 0 0 4px rgba(255, 193, 7, 0.1);
        }
        .btn-admin {
            background: linear-gradient(135deg, var(--electric-gold), #e6ae00);
            color: var(--navy-blue);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 1rem;
            border-radius: 12px;
            width: 100%;
            margin-top: 1rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 193, 7, 0.4);
            background: linear-gradient(135deg, #ffca2c, var(--electric-gold));
        }
        .error-message {
            background: rgba(239, 68, 68, 0.15);
            border-left: 4px solid var(--danger);
            color: #fca5a5;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .back-link {
            text-align: center;
            margin-top: 2rem;
        }
        .back-link a {
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.85rem;
            transition: color 0.3s;
        }
        .back-link a:hover {
            color: #fff;
        }
        /* Background decorative elements */
        .glow-sphere {
            position: absolute;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 193, 7, 0.1), transparent 70%);
            border-radius: 50%;
            z-index: 0;
            filter: blur(50px);
        }
        .glow-1 { top: -100px; right: -100px; }
        .glow-2 { bottom: -100px; left: -100px; }
    </style>
</head>
<body>

<div class="glow-sphere glow-1"></div>
<div class="glow-sphere glow-2"></div>

<div class="admin-login-container">
    <div class="admin-login-card">
        <div class="portal-header">
            <h1>Joshi Trading</h1>
            <p>Admin Portal</p>
        </div>

        <?php if ($error): ?>
            <div class="error-message">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label style="color: rgba(255,255,255,0.6); font-size: 0.85rem; margin-bottom: 0.5rem; display: block;">Admin Email</label>
                <input type="email" name="email" class="form-control" placeholder="admin@joshitrading.com" required autofocus>
            </div>
            <div class="form-group">
                <label style="color: rgba(255,255,255,0.6); font-size: 0.85rem; margin-bottom: 0.5rem; display: block;">Secure Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-admin">Authenticate</button>
        </form>

        <div class="back-link">
            <a href="../index.php">&larr; Return to Public Site</a>
        </div>
    </div>
</div>

</body>
</html>
