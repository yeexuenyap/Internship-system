<?php
session_start();
include("../config/db.php");

// Academic supervisor only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'academic') {
    header("Location: ../auth/login.php");
    exit();
}

$academic_id = $_SESSION['user_id'];

/*
|--------------------------------------------------------------------------
| Get students assigned to this academic supervisor
| Using your table name 'academic_requests'
|--------------------------------------------------------------------------
*/
$stmt = $conn->prepare(
    "SELECT u.id AS student_id, u.name
     FROM academic_requests ar
     JOIN users u ON ar.student_id = u.id
     WHERE ar.academic_id = ?"
);
$stmt->bind_param("i", $academic_id);
$stmt->execute();
$students = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Attendance</title>
    <link rel="stylesheet" href="../assets/dashboard.css">
    <style>
        body { font-family: Arial; background:#f4f6f9; }
        .card { background:#fff; padding:15px; margin:15px; border-radius:5px; }
        table { width:100%; border-collapse: collapse; margin-top:10px; }
        th, td { border:1px solid #ccc; padding:8px; text-align:left; }
        th { background:#eee; }
    </style>
</head>
<body>

<div class="navbar">
    <div>Academic Supervisor Dashboard</div>
    <a href="academic_logbook.php">Student Logbook</a>
    <a href="academic_attendance.php">Student Attendance</a>
    <a href="profile.php">Profile</a>
    <a href="../auth/logout.php">Logout</a>
</div>

<h2>Assigned Students Attendance</h2>

<?php while ($student = $students->fetch_assoc()): ?>

    <div class="card">
        <h3><?= htmlspecialchars($student['name']) ?></h3>

        <?php
        $attendance = $conn->prepare(
            "SELECT date, status
             FROM attendance
             WHERE student_id = ?
             ORDER BY date DESC"
        );
        $attendance->bind_param("i", $student['student_id']);
        $attendance->execute();
        $records = $attendance->get_result();
        ?>

        <?php if ($records->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Status</th>
                </tr>

                <?php while ($row = $records->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['date'] ?></td>
                        <td><?= $row['status'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No attendance records.</p>
        <?php endif; ?>

    </div>

<?php endwhile; ?>

</body>
</html>
