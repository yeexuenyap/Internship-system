<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$success = $_SESSION['success'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

// Load notifications history
$stmt = $conn->prepare("SELECT * FROM notifications ORDER BY created_at DESC");
$stmt->execute();
$notes = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Notifications</title>
    <style>
        body { background:#f6f7fb; font-family:Arial; margin:0; }
        .navbar { background:#111827; color:#fff; padding:12px 16px; display:flex; justify-content:space-between; align-items:center; }
        .navbar a { color:#fff; text-decoration:none; margin-left:12px; }
        .container { max-width:1100px; margin:16px auto; padding:0 16px; }

        .card { background:#fff; border-radius:12px; padding:14px; box-shadow:0 8px 20px rgba(17,24,39,0.06); margin-bottom:14px; }
        label { font-size:12px; color:#6b7280; display:block; margin:10px 0 6px; }
        input, select, textarea { width:100%; padding:10px; border:1px solid #e5e7eb; border-radius:10px; }
        textarea { min-height:110px; resize:vertical; }

        .row { display:flex; gap:10px; flex-wrap:wrap; }
        .row > div { flex:1; min-width:220px; }

        .btn { background:#2563eb; color:#fff; border:none; padding:10px 12px; border-radius:10px; cursor:pointer; }
        .btn-red { background:#ef4444; }

        table { width:100%; border-collapse:separate; border-spacing:0; border-radius:12px; overflow:hidden; background:#fff; }
        th, td { padding:10px; border-bottom:1px solid #f1f5f9; text-align:left; vertical-align:top; }
        th { background:#f8fafc; font-size:12px; color:#6b7280; text-transform:uppercase; letter-spacing:.04em; }
        tr:hover td { background:#fafafa; }

        .msg { padding:10px 12px; border-radius:12px; margin-bottom:12px; font-size:14px; }
        .ok { background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; }
        .err { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }

        .tag { display:inline-block; padding:4px 8px; border-radius:999px; font-size:12px; background:#f3f4f6; }
    </style>
</head>
<body>

<div class="navbar">
    <div><b>Admin</b> — Notifications</div>
    <div>
        <a href="admin.php">User Management</a>
        <a href="admin_internships.php">Internships</a>
        <a href="admin_companies.php">Companies</a>
        <a href="admin_students.php">Students</a>
        <a href="admin_notifications.php">Notifications</a>
        <a href="admin_analysis.php"><b>Analysis</b></a>
        <a href="../auth/logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <?php if ($success): ?><div class="msg ok"><?= htmlspecialchars($success) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="msg err"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="card">
        <h2 style="margin:0 0 10px;">Send Notification</h2>

        <form method="POST" action="admin_notification_send.php">
            <div class="row">
                <div>
                    <label>Title</label>
                    <input type="text" name="title" maxlength="150" required placeholder="e.g. System Maintenance">
                </div>
                <div>
                    <label>Send To</label>
                    <select name="target_role" required>
                        <option value="all">All Users</option>
                        <option value="student">Students</option>
                        <option value="company">Company Supervisors</option>
                        <option value="academic">Academic Supervisors</option>
                    </select>
                </div>
            </div>

            <label>Message</label>
            <textarea name="message" required placeholder="Write announcement here..."></textarea>

            <button class="btn" type="submit">Send</button>
        </form>
    </div>

    <div class="card">
        <h2 style="margin:0 0 10px;">Sent Notifications</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Target</th>
                <th>Message</th>
                <th>Date</th>
                <th>Action</th>
            </tr>

            <?php if ($notes->num_rows === 0): ?>
                <tr><td colspan="6"><i>No notifications yet.</i></td></tr>
            <?php endif; ?>

            <?php while ($n = $notes->fetch_assoc()): ?>
                <tr>
                    <td><?= (int)$n['id'] ?></td>
                    <td><b><?= htmlspecialchars($n['title']) ?></b></td>
                    <td><span class="tag"><?= htmlspecialchars($n['target_role']) ?></span></td>
                    <td><?= nl2br(htmlspecialchars($n['message'])) ?></td>
                    <td><?= htmlspecialchars($n['created_at']) ?></td>
                    <td>
                        <form method="POST" action="admin_notification_delete.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?= (int)$n['id'] ?>">
                            <button class="btn btn-red" onclick="return confirm('Delete this notification?');">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>

        </table>
    </div>
</div>

</body>
</html>
