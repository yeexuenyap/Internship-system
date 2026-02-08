<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'academic') {
    header("Location: ../auth/login.php");
    exit();
}

$academic_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Academic Supervisor - Student Logbooks</title>
    <link rel="stylesheet" href="../assets/dashboard.css">
</head>
<body>

<div class="navbar">
    <div>Academic Supervisor Dashboard</div>
    <a href="academic_logbook.php">Student Logbook</a>
    <a href="academic_attendance.php">Student Attendance</a>
    <a href="profile.php">Profile</a>
    <a href="../auth/logout.php">Logout</a>
</div>

<div class="container">
<h2>Student Logbooks</h2>

<?php
// Fetch all students assigned to this academic supervisor
$stmt = $conn->prepare(
    "SELECT users.id AS student_id, users.name AS student_name, internships.id AS internship_id, internships.title AS internship_title
     FROM users
     LEFT JOIN student_internships ON users.id = student_internships.student_id
     LEFT JOIN internships ON student_internships.internship_id = internships.id
     WHERE users.academic_supervisor_id = ? AND student_internships.status IN ('in_progress','completed')
     ORDER BY users.name"
);
$stmt->bind_param("i", $academic_id);
$stmt->execute();
$students = $stmt->get_result();

if ($students->num_rows > 0):
    while ($s = $students->fetch_assoc()):
?>
<div class="card">
    <h3><?= htmlspecialchars($s['student_name']) ?> (<?= htmlspecialchars($s['internship_title']) ?>)</h3>

    <?php
    // Fetch logbook entries
    $logs = $conn->prepare(
        "SELECT * FROM logbook 
         WHERE student_id = ? AND internship_id = ? 
         ORDER BY log_date DESC"
    );
    $logs->bind_param("ii", $s['student_id'], $s['internship_id']);
    $logs->execute();
    $logResult = $logs->get_result();

    if ($logResult->num_rows > 0):
        while ($log = $logResult->fetch_assoc()):
    ?>
        <div class="card" style="background:#f9f9f9; margin:5px 0;">
            <h4><?= htmlspecialchars($log['log_date']) ?> - <?= htmlspecialchars($log['title']) ?></h4>
            <p><?= nl2br(htmlspecialchars($log['content'])) ?></p>
        </div>
    <?php
        endwhile;
    else:
        echo "<p><i>No logs yet.</i></p>";
    endif;
    ?>

</div>
<?php
    endwhile;
else:
    echo "<p><i>No students assigned.</i></p>";
endif;
?>

</div>
</body>
</html>
