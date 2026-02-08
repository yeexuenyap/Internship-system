<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'academic') {
    header("Location: ../auth/login.php");
    exit();
}

$academic_id = $_SESSION['user_id'];
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
    <title>Academic Supervisor Dashboard</title>
    <link rel="stylesheet" href="../assets/dashboard.css">
</head>
<body>

<div class="navbar">
    <div>Academic Supervisor Dashboard</div>
    <a href="academic_logbook.php">Student Logbook</a>
    <a href="academic_attendance.php">Student Attendance</a>
    <a href="profile.php">Profile</a>
    <a href="notifications.php">Notifications</a>
    <a href="../auth/logout.php">Logout</a>
</div>

<div class="container">

<!-- ================= ASSIGNMENT REQUESTS ================= -->

<h2>Student Assignment Requests</h2>

<?php
$requests = $conn->prepare(
    "SELECT academic_requests.id AS request_id,
            users.id AS student_id,
            users.name AS student_name,
            users.email
     FROM academic_requests
     JOIN users ON academic_requests.student_id = users.id
     WHERE academic_requests.academic_id = ?
       AND academic_requests.status = 'pending'"
);
$requests->bind_param("i", $academic_id);
$requests->execute();
$reqResult = $requests->get_result();

if ($reqResult->num_rows > 0):
    while ($req = $reqResult->fetch_assoc()):
?>
<div class="card">
    <h3><?= htmlspecialchars($req['student_name']) ?></h3>
    <p><?= htmlspecialchars($req['email']) ?></p>

    <form method="POST" action="academic_request_action.php" style="display:inline;">
        <input type="hidden" name="request_id" value="<?= $req['request_id'] ?>">
        <input type="hidden" name="student_id" value="<?= $req['student_id'] ?>">
        <input type="hidden" name="action" value="approve">
        <button class="button">Approve</button>
    </form>

    <form method="POST" action="academic_request_action.php" style="display:inline;">
        <input type="hidden" name="request_id" value="<?= $req['request_id'] ?>">
        <input type="hidden" name="student_id" value="<?= $req['student_id'] ?>">
        <input type="hidden" name="action" value="reject">
        <button class="button" style="background:#e74a3b;">Reject</button>
    </form>
</div>
<?php
    endwhile;
else:
    echo "<p><i>No pending requests.</i></p>";
endif;
?>

<!-- ================= ASSIGNED STUDENTS ================= -->

<h2>My Supervised Students</h2>

<?php
$stmt = $conn->prepare(
    "SELECT 
        users.id AS student_id,
        users.name AS student_name,
        users.email,
        internships.title,
        student_internships.internship_id,
        student_internships.status,
        student_internships.evaluation_score,
        student_internships.evaluation_comment,
        student_internships.academic_score,
        student_internships.academic_comment
     FROM users
     LEFT JOIN student_internships 
        ON users.id = student_internships.student_id
     LEFT JOIN internships 
        ON student_internships.internship_id = internships.id
     WHERE users.academic_supervisor_id = ?"
);
$stmt->bind_param("i", $academic_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0):
    while ($row = $result->fetch_assoc()):
?>
<div class="card">
    <h3><?= htmlspecialchars($row['student_name']) ?></h3>
    <p><b>Email:</b> <?= htmlspecialchars($row['email']) ?></p>

    <?php if ($row['title']): ?>
        <p><b>Internship:</b> <?= htmlspecialchars($row['title']) ?></p>
        <p><b>Status:</b> <?= ucfirst($row['status']) ?></p>

        <?php if ($row['evaluation_score'] !== null): ?>
            <p><b>Company Score:</b> <?= $row['evaluation_score'] ?></p>
            <p><b>Company Comment:</b> <?= htmlspecialchars($row['evaluation_comment']) ?></p>
        <?php endif; ?>

        <?php if ($row['status'] == 'completed'): ?>
            <h4>Academic Evaluation</h4>

            <form method="POST" action="academic_evaluate.php">
                <input type="hidden" name="student_id" value="<?= $row['student_id'] ?>">
                <input type="hidden" name="internship_id" value="<?= $row['internship_id'] ?>">

                <input type="number" name="score" min="1" max="100"
                       value="<?= $row['academic_score'] ?>" required>

                <textarea name="comment" placeholder="Academic evaluation comment"><?= htmlspecialchars($row['academic_comment']) ?></textarea>

                <button class="button">Submit Academic Evaluation</button>
            </form>
        <?php endif; ?>

    <?php else: ?>
        <p><i>No internship assigned yet.</i></p>
    <?php endif; ?>
</div>
<?php
    endwhile;
else:
    echo "<p><i>No students assigned to you.</i></p>";
endif;
?>

</div>

</body>
</html>
