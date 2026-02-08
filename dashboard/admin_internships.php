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

// Optional filters
$q = trim($_GET['q'] ?? '');
$faculty = $_GET['faculty'] ?? '';
$status = $_GET['status'] ?? '';

$valid_faculties = ['','FCI','FCM','FOM','FIST'];
$valid_statuses = ['','applied','in_progress','completed','dropped','rejected'];

if (!in_array($faculty, $valid_faculties, true)) $faculty = '';
if (!in_array($status, $valid_statuses, true)) $status = '';

$sql = "
SELECT 
    i.id,
    i.title,
    i.description,
    i.location,
    i.duration,
    i.faculty,
    c.id AS company_id,
    c.name AS company_name,
    c.email AS company_email,
    si.student_id,
    s.name AS student_name,
    s.email AS student_email,
    si.status AS student_status
FROM internships i
JOIN users c ON i.company_id = c.id
LEFT JOIN student_internships si ON si.internship_id = i.id
LEFT JOIN users s ON si.student_id = s.id
WHERE 1=1
";

$params = [];
$types = "";

if ($q !== '') {
    $sql .= " AND (i.title LIKE ? OR c.name LIKE ? OR c.email LIKE ? OR s.name LIKE ? OR s.email LIKE ?)";
    $like = "%$q%";
    $params = array_merge($params, [$like, $like, $like, $like, $like]);
    $types .= "sssss";
}

if ($faculty !== '') {
    $sql .= " AND i.faculty = ?";
    $params[] = $faculty;
    $types .= "s";
}

if ($status !== '') {
    $sql .= " AND si.status = ?";
    $params[] = $status;
    $types .= "s";
}

$sql .= " ORDER BY i.id DESC, si.status ASC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Internship Management</title>
    <link rel="stylesheet" href="../assets/dashboard.css">
    <style>
        body { background:#f6f7fb; font-family: Arial, sans-serif; margin:0; }
        .navbar { background:#111827; color:#fff; padding:12px 16px; display:flex; justify-content:space-between; align-items:center; }
        .navbar a { color:#fff; text-decoration:none; margin-left:12px; }
        .container { padding:16px; max-width:1200px; margin:0 auto; }

        .card { background:#fff; border-radius:12px; padding:14px; margin:12px 0; box-shadow:0 8px 20px rgba(17,24,39,0.06); }
        .row { display:flex; gap:10px; flex-wrap:wrap; align-items:end; }
        .row > div { flex:1; min-width:220px; }
        label { font-size:12px; color:#6b7280; display:block; margin-bottom:6px; }
        input, select { width:100%; padding:10px; border:1px solid #e5e7eb; border-radius:10px; }

        table { width:100%; border-collapse:separate; border-spacing:0; background:#fff; border-radius:12px; overflow:hidden; }
        th, td { padding:10px; border-bottom:1px solid #f1f5f9; text-align:left; font-size:14px; vertical-align:top; }
        th { background:#f8fafc; font-size:12px; color:#6b7280; text-transform:uppercase; letter-spacing:0.04em; }
        tr:hover td { background:#fafafa; }

        .btn { padding:8px 10px; border:none; border-radius:10px; color:#fff; cursor:pointer; font-size:13px; }
        .btn-blue { background:#2563eb; }
        .btn-red { background:#ef4444; }
        .btn-gray { background:#6b7280; }
        .btn-green { background:#16a34a; }

        .msg { padding:10px 12px; border-radius:12px; margin-bottom:12px; font-size:14px; }
        .ok { background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; }
        .err { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }

        .tag { display:inline-block; padding:4px 8px; border-radius:999px; font-size:12px; background:#f3f4f6; }
    </style>
</head>
<body>

<div class="navbar">
    <div><b>Admin</b> — Internship Management</div>
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
        <h2 style="margin:0 0 10px;">Search & Filter</h2>
        <form method="GET" class="row">
            <div>
                <label>Search (internship/company/student)</label>
                <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="e.g. Web Dev, ABC Company, student name">
            </div>
            <div>
                <label>Faculty</label>
                <select name="faculty">
                    <option value="">All</option>
                    <?php foreach (['FCI','FCM','FOM','FIST'] as $f): ?>
                        <option value="<?= $f ?>" <?= ($faculty===$f?'selected':'') ?>><?= $f ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>Student Status</label>
                <select name="status">
                    <option value="">All</option>
                    <?php foreach (['applied','in_progress','completed','dropped','rejected'] as $st): ?>
                        <option value="<?= $st ?>" <?= ($status===$st?'selected':'') ?>><?= $st ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="flex:0; min-width:140px;">
                <button class="btn btn-blue" type="submit" style="width:100%;">Apply</button>
            </div>
        </form>
    </div>

    <div class="card">
        <h2 style="margin:0 0 10px;">All Internships (with Company + Student)</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Internship</th>
                <th>Company Supervisor</th>
                <th>Student (who took it)</th>
                <th>Status</th>
                <th>Manage</th>
            </tr>

            <?php if ($result->num_rows === 0): ?>
                <tr><td colspan="6"><i>No records found.</i></td></tr>
            <?php endif; ?>

            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= (int)$row['id'] ?></td>

                    <td>
                        <b><?= htmlspecialchars($row['title']) ?></b><br>
                        <span class="tag"><?= htmlspecialchars($row['faculty'] ?? '') ?></span><br>
                        <?php if (!empty($row['location']) || !empty($row['duration'])): ?>
                            <small><?= htmlspecialchars($row['location'] ?? '') ?><?= (!empty($row['location']) && !empty($row['duration'])) ? " | " : "" ?><?= htmlspecialchars($row['duration'] ?? '') ?></small><br>
                        <?php endif; ?>
                        <small><?= nl2br(htmlspecialchars($row['description'] ?? '')) ?></small>
                    </td>

                    <td>
                        <b><?= htmlspecialchars($row['company_name']) ?></b><br>
                        <small><?= htmlspecialchars($row['company_email'] ?? '') ?></small><br>
                        <small>ID: <?= (int)$row['company_id'] ?></small>
                    </td>

                    <td>
                        <?php if ($row['student_id']): ?>
                            <b><?= htmlspecialchars($row['student_name']) ?></b><br>
                            <small><?= htmlspecialchars($row['student_email'] ?? '') ?></small><br>
                            <small>ID: <?= (int)$row['student_id'] ?></small>
                        <?php else: ?>
                            <i>No student yet</i>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if ($row['student_status']): ?>
                            <span class="tag"><?= htmlspecialchars($row['student_status']) ?></span>
                        <?php else: ?>
                            <span class="tag">—</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <a class="btn btn-blue" style="text-decoration:none;"
                           href="admin_internship_edit.php?id=<?= (int)$row['id'] ?>">
                            Edit
                        </a>

                        <form method="POST" action="admin_internship_delete.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                            <button class="btn btn-red"
                                    onclick="return confirm('Delete internship #<?= (int)$row['id'] ?>? This will also affect student applications linked to it.');">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <p style="margin-top:10px; color:#6b7280; font-size:13px;">
            Note: If you want to keep student application history, don’t hard delete—use an “archived” column instead.
        </p>
    </div>

</div>
</body>
</html>
