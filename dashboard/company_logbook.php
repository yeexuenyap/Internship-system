<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'company') {
    header("Location: ../auth/login.php");
    exit();
}

$company_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Company Supervisor - Student Logbooks</title>
    <link rel="stylesheet" href="../assets/dashboard.css">
</head>
<body>

<div class="navbar">
    <div>Company Supervisor Dashboard</div>
    <a href="company.php">Dashboard</a>
    <a href="company_complete.php">Completion & Evaluation</a>
    <a href="company_logbook.php">Student Logbook</a>
    <a href="company_feedback.php">Daily Feedback</a>
    <a href="company_logbook_feedback.php">Logbook Feedback</a>
    <a href="company_attendance.php">Attendance</a>
    <a href="profile.php">Profile</a>
    <a href="../auth/logout.php">Logout</a>
</div>

<div class="container">
<h2>Student Logbooks</h2>

<?php
// Fetch all in-progress or completed interns for this company
$stmt = $conn->prepare(
    "SELECT users.id AS student_id, users.name AS student_name, internships.id AS internship_id, internships.title AS internship_title
     FROM student_internships
     JOIN users ON student_internships.student_id = users.id
     JOIN internships ON student_internships.internship_id = internships.id
     WHERE internships.company_id = ? AND student_internships.status IN ('in_progress','completed')
     ORDER BY users.name"
);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$students = $stmt->get_result();

if ($students->num_rows > 0):
    while ($s = $students->fetch_assoc()):
?>
<div class="card">
    <h3><?= htmlspecialchars($s['student_name']) ?> (<?= htmlspecialchars($s['internship_title']) ?>)</h3>

    <?php
    // Fetch logbook entries for this student & internship
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
    echo "<p><i>No interns available.</i></p>";
endif;
?>

</div>
</body>
</html>
