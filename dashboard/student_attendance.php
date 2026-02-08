<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

/*
|--------------------------------------------------------------------------
| Find the student's current internship (in_progress or completed)
|--------------------------------------------------------------------------
*/
$intern = $conn->prepare(
    "SELECT internship_id
     FROM student_internships
     WHERE student_id = ?
       AND status IN ('in_progress','completed')
     ORDER BY internship_id DESC
     LIMIT 1"
);
$intern->bind_param("i", $student_id);
$intern->execute();
$internshipRow = $intern->get_result()->fetch_assoc();
$internship_id = $internshipRow['internship_id'] ?? null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Attendance</title>
    <link rel="stylesheet" href="../assets/dashboard.css">
</head>
<body>

<div class="navbar">
        <div>Student Dashboard</div>
        <a href="student.php">Dashboard</a>
        <a href="student_logbook.php">Logbook</a>
        <a href="student_feedback.php">Daily Feedback</a>
        <a href="profile.php">Profile</a>
        <a href="student_attendance.php">Attendance</a>
        <a href="student_academic_apply.php">Academic Supervisor</a>
        <a href="notifications.php">Notifications</a>
        <a href="../auth/logout.php">Logout</a>
    </div>

<div class="container">
    <h2>My Attendance</h2>

    <?php if (!$internship_id): ?>
        <p><i>You don’t have an internship in progress or completed yet.</i></p>
    <?php else: ?>

        <?php
        // Attendance list
        $att = $conn->prepare(
            "SELECT date, status
             FROM attendance
             WHERE student_id = ? AND internship_id = ?
             ORDER BY date DESC"
        );
        $att->bind_param("ii", $student_id, $internship_id);
        $att->execute();
        $attResult = $att->get_result();

        // Attendance summary
        $sum = $conn->prepare(
            "SELECT
                SUM(status='present') AS present_days,
                SUM(status='absent') AS absent_days,
                COUNT(*) AS total_days
             FROM attendance
             WHERE student_id = ? AND internship_id = ?"
        );
        $sum->bind_param("ii", $student_id, $internship_id);
        $sum->execute();
        $summary = $sum->get_result()->fetch_assoc();

        $total = (int)($summary['total_days'] ?? 0);
        $present = (int)($summary['present_days'] ?? 0);
        $absent = (int)($summary['absent_days'] ?? 0);
        $percent = $total > 0 ? round(($present / $total) * 100, 2) : 0;
        ?>

        <div class="card">
            <p><b>Total Days:</b> <?= $total ?></p>
            <p><b>Present:</b> <?= $present ?></p>
            <p><b>Absent:</b> <?= $absent ?></p>
            <p><b>Attendance Rate:</b> <?= $percent ?>%</p>
        </div>

        <?php if ($attResult->num_rows > 0): ?>
            <table width="100%" border="1" cellpadding="8" style="background:#fff;">
                <tr style="background:#f4f6f9;">
                    <th>Date</th>
                    <th>Status</th>
                </tr>

                <?php while ($row = $attResult->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['date']) ?></td>
                        <td>
                            <?php if ($row['status'] === 'present'): ?>
                                <span style="color:green;font-weight:bold;">Present</span>
                            <?php else: ?>
                                <span style="color:red;font-weight:bold;">Absent</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p><i>No attendance records yet.</i></p>
        <?php endif; ?>

    <?php endif; ?>
</div>

</body>
</html>
