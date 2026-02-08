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

if (isset($_SESSION['error'])) {
    echo '<div class="alert error">' . htmlspecialchars($_SESSION['error']) . '</div>';
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Internship Completion & Evaluation</title>
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

<h2>In-Progress Interns</h2>

<?php
$stmt = $conn->prepare(
    "SELECT 
        users.id AS student_id,
        users.name AS student_name,
        users.email,
        internships.id AS internship_id,
        internships.title
     FROM student_internships
     JOIN internships ON student_internships.internship_id = internships.id
     JOIN users ON student_internships.student_id = users.id
     WHERE internships.company_id = ?
       AND student_internships.status = 'in_progress'"
);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0):
    while ($row = $result->fetch_assoc()):
?>

<div class="card">
    <h3><?= htmlspecialchars($row['student_name']) ?></h3>
    <p><b>Email:</b> <?= htmlspecialchars($row['email']) ?></p>
    <p><b>Internship:</b> <?= htmlspecialchars($row['title']) ?></p>

    <form method="POST" action="complete_internship.php"
          onsubmit="return confirm('Mark internship as completed?')">

        <input type="hidden" name="student_id" value="<?= $row['student_id'] ?>">
        <input type="hidden" name="internship_id" value="<?= $row['internship_id'] ?>">

        <label>Final Score</label>
        <input type="number" name="score" min="1" max="100" required>

        <label>Evaluation Comment</label>
        <textarea name="comment" placeholder="Final evaluation comment" required></textarea>

        <button class="button">Mark Completed & Evaluate</button>
    </form>
</div>

<?php
    endwhile;
else:
    echo "<p><i>No in-progress interns.</i></p>";
endif;
?>
<hr>

<h2>Completed Interns</h2>

<?php
$completedStmt = $conn->prepare(
    "SELECT 
        users.name AS student_name,
        users.email,
        internships.title,
        student_internships.evaluation_score,
        student_internships.evaluation_comment
     FROM student_internships
     JOIN internships ON student_internships.internship_id = internships.id
     JOIN users ON student_internships.student_id = users.id
     WHERE internships.company_id = ?
       AND student_internships.status = 'completed'"
);
$completedStmt->bind_param("i", $company_id);
$completedStmt->execute();
$completedResult = $completedStmt->get_result();

if ($completedResult->num_rows > 0):
    while ($row = $completedResult->fetch_assoc()):
?>

<div class="card completed">
    <h3><?= htmlspecialchars($row['student_name']) ?></h3>
    <p><b>Email:</b> <?= htmlspecialchars($row['email']) ?></p>
    <p><b>Internship:</b> <?= htmlspecialchars($row['title']) ?></p>

    <p><b>Final Score:</b> <?= htmlspecialchars($row['evaluation_score']) ?></p>
    <p><b>Evaluation Comment:</b><br>
        <?= nl2br(htmlspecialchars($row['evaluation_comment'])) ?>
    </p>
</div>

<?php
    endwhile;
else:
    echo "<p><i>No completed interns yet.</i></p>";
endif;
?>

</div>

</body>
</html>
