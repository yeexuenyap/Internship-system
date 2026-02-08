<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role'])) {
    header("Location: ../auth/login.php");
    exit();
}

$role = $_SESSION['role'];

// User sees: target_role = 'all' OR their role
$stmt = $conn->prepare(
    "SELECT id, title, message, target_role, created_at
     FROM notifications
     WHERE target_role = 'all' OR target_role = ?
     ORDER BY created_at DESC"
);
$stmt->bind_param("s", $role);
$stmt->execute();
$notes = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Notifications</title>
    <link rel="stylesheet" href="../assets/dashboard.css">
    <style>
        body { background:#f6f7fb; font-family:Arial; margin:0; }
        .navbar { background:#111827; color:#fff; padding:12px 16px; display:flex; justify-content:space-between; align-items:center; }
        .navbar a { color:#fff; text-decoration:none; margin-left:12px; }
        .container { max-width:1000px; margin:16px auto; padding:0 16px; }
        .card { background:#fff; border-radius:12px; padding:14px; box-shadow:0 8px 20px rgba(17,24,39,0.06); margin-bottom:14px; }
        .tag { display:inline-block; padding:4px 8px; border-radius:999px; font-size:12px; background:#f3f4f6; }
        .meta { color:#6b7280; font-size:13px; margin-top:6px; }
    </style>
</head>
<body>

<div class="navbar">
    <div><b><?= htmlspecialchars(ucfirst($role)) ?></b> — Notifications</div>
    <div>
        <?php if ($role === 'student'): ?>
            <a href="student.php">Dashboard</a>
        <?php elseif ($role === 'company'): ?>
            <a href="company.php">Dashboard</a>
        <?php elseif ($role === 'academic'): ?>
            <a href="academic.php">Dashboard</a>
        <?php elseif ($role === 'admin'): ?>
            <a href="admin.php">Admin</a>
        <?php endif; ?>
        <a href="../auth/logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <div class="card">
        <h2 style="margin:0;">Notifications</h2>
        <div class="meta">You will see announcements sent to <b>All</b> or to your role (<b><?= htmlspecialchars($role) ?></b>).</div>
    </div>

    <?php if ($notes->num_rows === 0): ?>
        <div class="card"><i>No notifications yet.</i></div>
    <?php endif; ?>

    <?php while ($n = $notes->fetch_assoc()): ?>
        <div class="card">
            <h3 style="margin:0;"><?= htmlspecialchars($n['title']) ?></h3>
            <div class="meta">
                <span class="tag"><?= htmlspecialchars($n['target_role']) ?></span>
                • <?= htmlspecialchars($n['created_at']) ?>
            </div>
            <p style="margin-top:10px; white-space:pre-wrap;"><?= htmlspecialchars($n['message']) ?></p>
        </div>
    <?php endwhile; ?>

</div>
</body>
</html>
