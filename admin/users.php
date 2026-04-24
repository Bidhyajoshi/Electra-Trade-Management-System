<?php
session_start();
require_once '../includes/db.php';

// Check if admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['user_id'])) {
        $target_id = (int)$_POST['user_id'];
        
        // Prevent admin from deleting themselves
        if ($target_id === $_SESSION['user_id']) {
            $_SESSION['message'] = "You cannot modify your own account from here.";
        } else {
            if ($_POST['action'] === 'delete') {
                $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
                $stmt->bind_param("i", $target_id);
                if ($stmt->execute()) {
                    $_SESSION['message'] = "User deleted successfully.";
                } else {
                    $_SESSION['message'] = "Error deleting user.";
                }
            } elseif ($_POST['action'] === 'toggle_role') {
                $stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
                $stmt->bind_param("i", $target_id);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($user = $res->fetch_assoc()) {
                    $new_role = ($user['role'] === 'admin') ? 'customer' : 'admin';
                    $update = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
                    $update->bind_param("si", $new_role, $target_id);
                    if ($update->execute()) {
                        $_SESSION['message'] = "User role updated successfully.";
                    }
                }
            }
        }
        header("Location: users.php");
        exit;
    }
}

// Fetch users
$users = $conn->query("SELECT id, email, role, created_at FROM users ORDER BY id DESC");
$fetch_error = '';
if (!$users) {
    $fetch_error = "Unable to fetch users: " . $conn->error;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Joshi Trading Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-layout {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
        }
        .sidebar {
            background-color: var(--navy-light);
            padding: 2rem 1rem;
            border-right: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar a {
            display: block;
            padding: 1rem;
            color: var(--light-slate);
            border-radius: 5px;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: rgba(255,193,7,0.1);
            color: var(--electric-gold);
        }
        .main-content {
            padding: 2rem;
        }
        
        /* Modern Striped Table with Hover Effects */
        .modern-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            color: #fff;
        }
        .modern-table th {
            background-color: rgba(0, 21, 41, 0.8);
            padding: 1rem;
            text-align: left;
            border-bottom: 2px solid var(--electric-gold);
            font-weight: 600;
        }
        .modern-table td {
            padding: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .modern-table tbody tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.02);
        }
        .modern-table tbody tr:hover {
            background-color: rgba(255, 193, 7, 0.05);
            transition: background-color 0.3s ease;
        }
        
        .badge {
            padding: 0.3rem 0.6rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .badge-admin {
            background-color: rgba(255, 193, 7, 0.2);
            color: var(--electric-gold);
            border: 1px solid var(--electric-gold);
        }
        .badge-customer {
            background-color: rgba(16, 185, 129, 0.2);
            color: #6ee7b7;
            border: 1px solid #10b981;
        }
        
        @media (max-width: 768px) {
            .admin-layout { grid-template-columns: 1fr; }
            .sidebar { display: none; }
            .modern-table { display: block; overflow-x: auto; }
        }
    </style>
</head>
<body>

<div class="admin-layout">
    <aside class="sidebar">
        <h2 style="color: var(--electric-gold); font-size: 1.5rem; margin-bottom: 2rem; text-align: center;">Admin Panel</h2>
        <a href="index.php">Dashboard</a>
        <a href="users.php" class="active">Manage Users</a>
        <a href="add_product.php">Add Product</a>
        <a href="../index.php">View Site</a>
        <a href="../logout.php" style="color: var(--danger);">Logout</a>
    </aside>
    
    <main class="main-content">
        <h2 style="margin-bottom: 2rem;">Manage Users</h2>
        
        <?php
        if (isset($_SESSION['message'])) {
            $is_error = strpos($_SESSION['message'], 'cannot') !== false || strpos($_SESSION['message'], 'Error') !== false;
            $alert_class = $is_error ? 'alert-danger' : 'alert-success';
            echo "<div class='alert {$alert_class}'>" . htmlspecialchars($_SESSION['message']) . "</div>";
            unset($_SESSION['message']);
        }
        ?>

        <div class="glass-panel" style="padding: 2rem; border-radius: 15px;">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email / Username</th>
                        <th>Role</th>
                        <th>Joined At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($fetch_error): ?>
                        <tr><td colspan="5" style="text-align:center; color: var(--danger);"><?= htmlspecialchars($fetch_error) ?></td></tr>
                    <?php elseif ($users && $users->num_rows > 0): ?>
                        <?php while($row = $users->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><strong><?= htmlspecialchars($row['email']) ?></strong></td>
                            <td>
                                <span class="badge <?= $row['role'] === 'admin' ? 'badge-admin' : 'badge-customer' ?>">
                                    <?= ucfirst($row['role']) ?>
                                </span>
                            </td>
                            <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                            <td>
                                <form action="users.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                                    <input type="hidden" name="action" value="toggle_role">
                                    <button type="submit" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; margin-right: 0.5rem;">
                                        Make <?= $row['role'] === 'admin' ? 'Customer' : 'Admin' ?>
                                    </button>
                                </form>
                                <form action="users.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="text-align:center;">No users found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

</body>
</html>
