<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'company') {
    header("Location: ../auth/login.php");
    exit();
}

$company_id = $_SESSION['user_id'];
?>
<?php
if (isset($_SESSION['success'])) {
    echo '<div class="alert success">' . htmlspecialchars($_SESSION['success']) . '</div>';
    unset($_SESSION['success']);
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Company Supervisor - Daily Feedback</title>
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
<h2>Provide Daily Feedback</h2>

<?php
// Get in-progress interns for this company
$stmt = $conn->prepare(
    "SELECT users.id AS student_id, users.name AS student_name, internships.id AS internship_id, internships.title AS internship_title
     FROM student_internships
     JOIN users ON student_internships.student_id = users.id
     JOIN internships ON student_internships.internship_id = internships.id
     WHERE internships.company_id = ? AND student_internships.status = 'in_progress'
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
    <form method="POST" action="submit_feedback.php">
        <input type="hidden" name="student_id" value="<?= $s['student_id'] ?>">
        <input type="hidden" name="internship_id" value="<?= $s['internship_id'] ?>">
        <input type="date" name="date" max="<?= date('Y-m-d') ?>" required>

        <textarea name="content" placeholder="Enter daily feedback" required></textarea>
        <button class="button">Submit Feedback</button>
    </form>
</div>
<?php
    endwhile;
else:
    echo "<p><i>No interns in progress.</i></p>";
endif;
?>

</div>
</body>
</html>
