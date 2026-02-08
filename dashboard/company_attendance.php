<?php
session_start();


include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'company') {
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

if (isset($_SESSION['error'])) {
    echo '<div class="alert error">' . htmlspecialchars($_SESSION['error']) . '</div>';
    unset($_SESSION['error']);
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Attendance Management</title>
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
<h2>Mark Attendance</h2>

<?php
$stmt = $conn->prepare(
    "SELECT 
        u.id AS student_id,
        u.name AS student_name,
        i.id AS internship_id,
        i.title AS internship_title
     FROM student_internships si
     JOIN users u ON si.student_id = u.id
     JOIN internships i ON si.internship_id = i.id
     WHERE i.company_id = ?
       AND si.status = 'in_progress'
     ORDER BY u.name"
);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0):
    while ($row = $result->fetch_assoc()):
?>
<div class="card">
    <h3><?= htmlspecialchars($row['student_name']) ?></h3>
    <p><b>Internship:</b> <?= htmlspecialchars($row['internship_title']) ?></p>

    <form method="POST" action="mark_attendance.php">
        <input type="hidden" name="student_id" value="<?= $row['student_id'] ?>">
        <input type="hidden" name="internship_id" value="<?= $row['internship_id'] ?>">

        <label>Date</label>
        <input type="date" name="date" max="<?= date('Y-m-d') ?>" required>

        <label>Status</label>
        <select name="status" required>
            <option value="present">Present</option>
            <option value="absent">Absent</option>
        </select>

        <button class="button">Mark Attendance</button>
    </form>
</div>
<?php
    endwhile;
else:
    echo "<p><i>No in-progress interns.</i></p>";
endif;
?>

</div>
</body>
</html>
