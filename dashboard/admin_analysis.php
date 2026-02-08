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

/* -----------------------------
   USERS COUNT (TOTAL + BY ROLE)
-------------------------------- */
$totalUsers = 0;
$usersByRole = [
    'admin' => 0,
    'student' => 0,
    'company' => 0,
    'academic' => 0
];

$res = $conn->query("SELECT COUNT(*) AS c FROM users");
if ($res) $totalUsers = (int)$res->fetch_assoc()['c'];

$res = $conn->query("SELECT role, COUNT(*) AS c FROM users GROUP BY role");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $role = $row['role'];
        $usersByRole[$role] = (int)$row['c'];
    }
}

/* --------------------------------
   STUDENT INTERNSHIP STATUS COUNTS
----------------------------------- */
$studentCounts = [
    'in_progress' => 0,
    'completed' => 0,
    'no_internship' => 0
];

// In progress students (distinct)
$stmt = $conn->prepare("
    SELECT COUNT(DISTINCT si.student_id) AS c
    FROM student_internships si
    JOIN users u ON u.id = si.student_id
    WHERE u.role='student' AND si.status='in_progress'
");
$stmt->execute();
$studentCounts['in_progress'] = (int)$stmt->get_result()->fetch_assoc()['c'];

// Completed students (distinct), excluding those currently in_progress
$stmt = $conn->prepare("
    SELECT COUNT(DISTINCT si.student_id) AS c
    FROM student_internships si
    JOIN users u ON u.id = si.student_id
    WHERE u.role='student'
      AND si.status='completed'
      AND si.student_id NOT IN (
        SELECT student_id FROM student_internships WHERE status='in_progress'
      )
");
$stmt->execute();
$studentCounts['completed'] = (int)$stmt->get_result()->fetch_assoc()['c'];

// Students with no internship record at all
$stmt = $conn->prepare("
    SELECT COUNT(*) AS c
    FROM users u
    WHERE u.role='student'
      AND u.id NOT IN (SELECT DISTINCT student_id FROM student_internships)
");
$stmt->execute();
$studentCounts['no_internship'] = (int)$stmt->get_result()->fetch_assoc()['c'];

/* -----------------------------
   INTERNSHIPS POSTED (TOTAL)
-------------------------------- */
$totalInternships = 0;
$res = $conn->query("SELECT COUNT(*) AS c FROM internships");
if ($res) $totalInternships = (int)$res->fetch_assoc()['c'];

/* ------------------------------------
   ATTENDANCE TOTALS + PRESENT/ABSENT
------------------------------------- */
$attendanceTotals = [
    'total' => 0,
    'present' => 0,
    'absent' => 0,
    'present_rate' => 0.0,
    'absent_rate' => 0.0
];

$res = $conn->query("
    SELECT
        COUNT(*) AS total,
        SUM(CASE WHEN status='present' THEN 1 ELSE 0 END) AS present_count,
        SUM(CASE WHEN status='absent' THEN 1 ELSE 0 END) AS absent_count
    FROM attendance
");

if ($res) {
    $row = $res->fetch_assoc();
    $attendanceTotals['total'] = (int)($row['total'] ?? 0);
    $attendanceTotals['present'] = (int)($row['present_count'] ?? 0);
    $attendanceTotals['absent'] = (int)($row['absent_count'] ?? 0);

    if ($attendanceTotals['total'] > 0) {
        $attendanceTotals['present_rate'] = ($attendanceTotals['present'] / $attendanceTotals['total']) * 100.0;
        $attendanceTotals['absent_rate'] = ($attendanceTotals['absent'] / $attendanceTotals['total']) * 100.0;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - System Analysis</title>
    <style>
        body { background:#f6f7fb; font-family:Arial; margin:0; color:#111827; }
        .navbar { background:#111827; color:#fff; padding:12px 16px; display:flex; justify-content:space-between; align-items:center; }
        .navbar a { color:#fff; text-decoration:none; margin-left:12px; }
        .container { max-width:1100px; margin:16px auto; padding:0 16px; }

        .grid { display:grid; grid-template-columns: repeat(1, 1fr); gap:14px; }
        @media (min-width: 900px) { .grid { grid-template-columns: repeat(2, 1fr); } }

        .card { background:#fff; border-radius:14px; padding:16px; box-shadow:0 8px 20px rgba(17,24,39,0.06); }
        .card h2 { margin:0 0 10px; font-size:16px; }
        .big { font-size:34px; font-weight:800; margin:6px 0 0; }
        .muted { color:#6b7280; font-size:13px; }

        table { width:100%; border-collapse:separate; border-spacing:0; border-radius:12px; overflow:hidden; background:#fff; margin-top:10px; }
        th, td { padding:10px; border-bottom:1px solid #f1f5f9; text-align:left; }
        th { background:#f8fafc; font-size:12px; color:#6b7280; text-transform:uppercase; letter-spacing:.04em; }

        .badge { display:inline-block; padding:4px 10px; border-radius:999px; font-size:12px; background:#f3f4f6; }
        .msg { padding:10px 12px; border-radius:12px; margin-bottom:12px; font-size:14px; }
        .ok { background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; }
        .err { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }

        .span2 { grid-column: 1 / -1; }
        .kpi-row { display:flex; gap:12px; flex-wrap:wrap; margin-top:10px; }
        .kpi { flex:1; min-width:180px; background:#f8fafc; border:1px solid #eef2f7; border-radius:12px; padding:12px; }
        .kpi .label { color:#6b7280; font-size:12px; }
        .kpi .value { font-size:22px; font-weight:800; margin-top:6px; }
    </style>
</head>
<body>

<div class="navbar">
    <div><b>Admin</b> — System Analysis</div>
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

    <div class="grid">

        <!-- Users -->
        <div class="card">
            <h2>Total Users</h2>
            <div class="big"><?= (int)$totalUsers ?></div>
            <div class="muted">All accounts in the system</div>

            <table>
                <tr><th>Role</th><th>Count</th></tr>
                <?php foreach ($usersByRole as $role => $count): ?>
                    <tr>
                        <td><span class="badge"><?= htmlspecialchars($role) ?></span></td>
                        <td><?= (int)$count ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <!-- Student statuses -->
        <div class="card">
            <h2>Student Internship Status</h2>
            <div class="muted">Counts are based on student_internships</div>

            <table>
                <tr><th>Status</th><th>Students</th></tr>
                <tr>
                    <td><span class="badge">in_progress</span></td>
                    <td><?= (int)$studentCounts['in_progress'] ?></td>
                </tr>
                <tr>
                    <td><span class="badge">completed</span></td>
                    <td><?= (int)$studentCounts['completed'] ?></td>
                </tr>
                <tr>
                    <td><span class="badge">no_internship</span></td>
                    <td><?= (int)$studentCounts['no_internship'] ?></td>
                </tr>
            </table>

            <div class="muted" style="margin-top:10px;">
                Notes:
                <ul style="margin:8px 0 0 18px;">
                    <li>If a student has both completed and in_progress records, they are counted as <b>in_progress</b>.</li>
                    <li>No internship means the student has no row in <b>student_internships</b>.</li>
                </ul>
            </div>
        </div>

        <!-- Internships posted -->
        <div class="card">
            <h2>Internships Posted</h2>
            <div class="big"><?= (int)$totalInternships ?></div>
            <div class="muted">Total internship applications created by company supervisors</div>
        </div>

        <!-- Attendance totals + rates -->
        <div class="card">
            <h2>Attendance Totals</h2>
            <div class="muted">Overall attendance records (all internships)</div>

            <div class="kpi-row">
                <div class="kpi">
                    <div class="label">Total Records</div>
                    <div class="value"><?= (int)$attendanceTotals['total'] ?></div>
                </div>
                <div class="kpi">
                    <div class="label">Present</div>
                    <div class="value"><?= (int)$attendanceTotals['present'] ?></div>
                </div>
                <div class="kpi">
                    <div class="label">Absent</div>
                    <div class="value"><?= (int)$attendanceTotals['absent'] ?></div>
                </div>
            </div>

            <table>
                <tr><th>Metric</th><th>Value</th></tr>
                <tr>
                    <td><span class="badge">Present Rate</span></td>
                    <td><?= number_format($attendanceTotals['present_rate'], 2) ?>%</td>
                </tr>
                <tr>
                    <td><span class="badge">Absent Rate</span></td>
                    <td><?= number_format($attendanceTotals['absent_rate'], 2) ?>%</td>
                </tr>
            </table>

            <div class="muted" style="margin-top:10px;">
                If you use other values besides <b>present/absent</b> in attendance.status, tell me and I’ll update the query.
            </div>
        </div>

    </div>

</div>
</body>
</html>
