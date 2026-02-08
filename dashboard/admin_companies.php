<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$q = trim($_GET['q'] ?? '');

$sql = "SELECT id, name, email FROM users WHERE role='company'";
$params = [];
$types = "";

if ($q !== "") {
    $sql .= " AND (name LIKE ? OR email LIKE ?)";
    $like = "%$q%";
    $params = [$like, $like];
    $types = "ss";
}

$sql .= " ORDER BY name ASC";

$stmt = $conn->prepare($sql);
if (!empty($params)) $stmt->bind_param($types, ...$params);
$stmt->execute();
$companies = $stmt->get_result();

$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Company Supervisors</title>
    <style>
        body { background:#f6f7fb; font-family:Arial; margin:0; }
        .navbar { background:#111827; color:#fff; padding:12px 16px; display:flex; justify-content:space-between; align-items:center; }
        .navbar a { color:#fff; text-decoration:none; margin-left:12px; }
        .container { max-width:1100px; margin:16px auto; padding:0 16px; }

        .card { background:#fff; border-radius:12px; padding:14px; box-shadow:0 8px 20px rgba(17,24,39,0.06); margin-bottom:14px; }
        .row { display:flex; gap:10px; flex-wrap:wrap; align-items:end; }
        .row > div { flex:1; min-width:220px; }

        input { width:100%; padding:10px; border:1px solid #e5e7eb; border-radius:10px; }

        table { width:100%; border-collapse:separate; border-spacing:0; border-radius:12px; overflow:hidden; background:#fff; }
        th, td { padding:10px; border-bottom:1px solid #f1f5f9; text-align:left; }
        th { background:#f8fafc; font-size:12px; color:#6b7280; text-transform:uppercase; letter-spacing:.04em; }
        tr:hover td { background:#fafafa; }

        .btn { background:#2563eb; color:#fff; border:none; padding:8px 10px; border-radius:10px; cursor:pointer; text-decoration:none; display:inline-block; }
        .msg { padding:10px 12px; border-radius:12px; margin-bottom:12px; font-size:14px; }
        .ok { background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; }
        .err { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }
    </style>
</head>
<body>

<div class="navbar">
    <div><b>Admin</b> — Company Supervisors</div>
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
        <h2 style="margin:0 0 10px;">Search Company Supervisors</h2>
        <form method="GET" class="row">
            <div>
                <label style="font-size:12px;color:#6b7280;">Search</label>
                <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Search name or email">
            </div>
            <div style="flex:0; min-width:160px;">
                <button class="btn" type="submit" style="width:100%;">Search</button>
            </div>
        </form>
    </div>

    <div class="card">
        <table>
            <tr>
                <th>ID</th>
                <th>Company Supervisor</th>
                <th>Email</th>
                <th>Action</th>
            </tr>

            <?php if ($companies->num_rows === 0): ?>
                <tr><td colspan="4"><i>No company supervisors found.</i></td></tr>
            <?php endif; ?>

            <?php while ($c = $companies->fetch_assoc()): ?>
                <tr>
                    <td><?= (int)$c['id'] ?></td>
                    <td><?= htmlspecialchars($c['name']) ?></td>
                    <td><?= htmlspecialchars($c['email']) ?></td>
                    <td>
                        <a class="btn" href="admin_company_records.php?company_id=<?= (int)$c['id'] ?>&tab=evaluations">
                            View Evaluations / Feedback
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

</body>
</html>
