<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$student_id = (int)($_GET['student_id'] ?? 0);
if ($student_id <= 0) {
    header("Location: admin_students.php");
    exit();
}

$success = $_SESSION['success'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

// Student info
$sstmt = $conn->prepare("SELECT id, name, email FROM users WHERE id=? AND role='student' LIMIT 1");
$sstmt->bind_param("i", $student_id);
$sstmt->execute();
$student = $sstmt->get_result()->fetch_assoc();
if (!$student) {
    header("Location: admin_students.php");
    exit();
}

// Find student internship in progress
$istmt = $conn->prepare(
    "SELECT si.internship_id, si.status, i.title
     FROM student_internships si
     JOIN internships i ON si.internship_id = i.id
     WHERE si.student_id = ?
       AND si.status = 'in_progress'
     ORDER BY si.accepted_at DESC
     LIMIT 1"
);
$istmt->bind_param("i", $student_id);
$istmt->execute();
$current = $istmt->get_result()->fetch_assoc();

$internship_id = $current['internship_id'] ?? null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Student Attendance</title>
    <style>
        body { background:#f6f7fb; font-family:Arial; margin:0; }
        .navbar { background:#111827; color:#fff; padding:12px 16px; display:flex; justify-content:space-between; align-items:center; }
        .navbar a { color:#fff; text-decoration:none; margin-left:12px; }
        .container { max-width:1100px; margin:16px auto; padding:0 16px; }
        .card { background:#fff; border-radius:12px; padding:14px; box-shadow:0 8px 20px rgba(17,24,39,0.06); margin-bottom:14px; }
        .msg { padding:10px 12px; border-radius:12px; margin-bottom:12px; font-size:14px; }
        .ok { background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; }
        .err { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }
        table { width:100%; border-collapse:separate; border-spacing:0; border-radius:12px; overflow:hidden; background:#fff; }
        th, td { padding:10px; border-bottom:1px solid #f1f5f9; text-align:left; vertical-align:top; }
        th { background:#f8fafc; font-size:12px; color:#6b7280; text-transform:uppercase; letter-spacing:.04em; }
        tr:hover td { background:#fafafa; }
        .row { display:flex; gap:10px; flex-wrap:wrap; align-items:end; }
        .row > div { flex:1; min-width:220px; }
        input, select { width:100%; padding:10px; border:1px solid #e5e7eb; border-radius:10px; }
        .btn { border:none; border-radius:10px; color:#fff; cursor:pointer; font-size:13px; padding:8px 10px; }
        .btn-blue { background:#2563eb; }
        .btn-red { background:#ef4444; }
        .tag { display:inline-block; padding:4px 8px; border-radius:999px; font-size:12px; background:#f3f4f6; }
    </style>
</head>
<body>

<div class="navbar">
    <div><b>Admin</b> — Student Attendance</div>
    <div>
        <a href="admin_students.php">Back to Students</a>
        <a href="../auth/logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <?php if ($success): ?><div class="msg ok"><?= htmlspecialchars($success) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="msg err"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="card">
        <h2 style="margin:0;"><?= htmlspecialchars($student['name']) ?></h2>
        <div style="color:#6b7280;margin-top:6px;">
            <?= htmlspecialchars($student['email']) ?> • ID: <?= (int)$student['id'] ?>
        </div>

        <?php if (!$internship_id): ?>
            <p style="margin-top:12px;"><span class="tag">No internship in progress</span></p>
        <?php else: ?>
            <p style="margin-top:12px;">
                <b>Internship:</b> <?= htmlspecialchars($current['title']) ?>
                <span class="tag">in_progress</span>
            </p>
        <?php endif; ?>
    </div>

    <?php if ($internship_id): ?>

        <!-- Add attendance -->
        <div class="card">
            <h3 style="margin:0 0 10px;">Add Attendance</h3>
            <form method="POST" action="admin_attendance_add.php" class="row">
                <input type="hidden" name="student_id" value="<?= (int)$student_id ?>">
                <input type="hidden" name="internship_id" value="<?= (int)$internship_id ?>">

                <div>
                    <label style="font-size:12px;color:#6b7280;">Date</label>
                    <input type="date" name="date" value="<?= date('Y-m-d') ?>" required>
                </div>

                <div>
                    <label style="font-size:12px;color:#6b7280;">Status</label>
                    <select name="status" required>
                        <option value="present">present</option>
                        <option value="absent">absent</option>
                    </select>
                </div>

                <div style="flex:0; min-width:160px;">
                    <button class="btn btn-blue" type="submit" style="width:100%;">Add</button>
                </div>
            </form>
            <p style="color:#6b7280;font-size:13px;margin-top:8px;">
                If the same date already exists, the system will update it.
            </p>
        </div>

        <!-- Attendance list -->
        <div class="card">
            <h3 style="margin:0 0 10px;">Attendance Records</h3>

            <?php
            $astmt = $conn->prepare(
                "SELECT id, date, status
                 FROM attendance
                 WHERE student_id = ? AND internship_id = ?
                 ORDER BY date DESC"
            );
            $astmt->bind_param("ii", $student_id, $internship_id);
            $astmt->execute();
            $att = $astmt->get_result();
            ?>

            <table>
                <tr>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

                <?php if ($att->num_rows === 0): ?>
                    <tr><td colspan="3"><i>No attendance records yet.</i></td></tr>
                <?php endif; ?>

                <?php while ($r = $att->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['date']) ?></td>
                        <td><span class="tag"><?= htmlspecialchars($r['status']) ?></span></td>
                        <td>
                            <form method="POST" action="admin_attendance_delete.php" style="display:inline;">
                                <input type="hidden" name="student_id" value="<?= (int)$student_id ?>">
                                <input type="hidden" name="internship_id" value="<?= (int)$internship_id ?>">
                                <input type="hidden" name="attendance_id" value="<?= (int)$r['id'] ?>">
                                <button class="btn btn-red" onclick="return confirm('Delete this attendance record?');">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

    <?php endif; ?>

</div>
</body>
</html>
