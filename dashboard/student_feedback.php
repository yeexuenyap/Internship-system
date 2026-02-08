<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

// Get current internship
$stmt = $conn->prepare(
    "SELECT internship_id FROM student_internships 
     WHERE student_id = ? AND status IN ('in_progress','completed') LIMIT 1"
);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$internship = $stmt->get_result()->fetch_assoc();
$internship_id = $internship['internship_id'] ?? null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Daily Feedback</title>
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
<h2>Daily Feedback</h2>

<?php if (!$internship_id): ?>
<p><i>No internship assigned yet.</i></p>
<?php else: ?>
<?php
$feedbacks = $conn->prepare(
    "SELECT * FROM feedback WHERE student_id = ? AND internship_id = ? ORDER BY feedback_date DESC"
);
$feedbacks->bind_param("ii", $student_id, $internship_id);
$feedbacks->execute();
$fbResult = $feedbacks->get_result();

if ($fbResult->num_rows > 0):
    while ($fb = $fbResult->fetch_assoc()):
?>
<div class="card">
    <h4><?= htmlspecialchars($fb['feedback_date']) ?></h4>
    <p><?= nl2br(htmlspecialchars($fb['content'])) ?></p>
</div>
<?php
    endwhile;
else:
    echo "<p><i>No feedback yet.</i></p>";
endif;
?>
<?php endif; ?>

</div>
</body>
</html>
