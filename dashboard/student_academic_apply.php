<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

// Check existing request
$requestStmt = $conn->prepare(
    "SELECT academic_id, status
     FROM academic_requests
     WHERE student_id = ?
     ORDER BY created_at DESC
     LIMIT 1"
);
$requestStmt->bind_param("i", $student_id);
$requestStmt->execute();
$currentRequest = $requestStmt->get_result()->fetch_assoc();

// Assigned academic supervisor
$assignedStmt = $conn->prepare(
    "SELECT users.name
     FROM users
     WHERE id = (SELECT academic_supervisor_id FROM users WHERE id = ?)"
);
$assignedStmt->bind_param("i", $student_id);
$assignedStmt->execute();
$assigned = $assignedStmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Academic Supervisor</title>
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

<h2>Status</h2>

<?php if ($assigned): ?>
    <p><b>Assigned Academic Supervisor:</b> <?= htmlspecialchars($assigned['name']) ?></p>

<?php elseif ($currentRequest): ?>
    <p>
        <b>Request Status:</b>
        <?= ucfirst($currentRequest['status']) ?>
    </p>

<?php else: ?>
    <p><i>No academic supervisor request made.</i></p>
<?php endif; ?>

<hr>

<h2>Available Academic Supervisors</h2>

<?php
$academics = $conn->query(
    "SELECT id, name, email, faculty
     FROM users
     WHERE role = 'academic'
     ORDER BY name ASC"
);

if ($academics->num_rows === 0):
?>
    <p><i>No academic supervisors available.</i></p>
<?php else: ?>

<table width="100%" border="1" cellpadding="8">
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Faculty</th>
        <th>Action</th>
    </tr>

<?php while ($a = $academics->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($a['name']) ?></td>
    <td><?= htmlspecialchars($a['email']) ?></td>
    <td><?= htmlspecialchars($a['faculty'] ?? '-') ?></td>
    <td>
        <?php if ($assigned): ?>
            <button disabled>Assigned</button>

        <?php elseif ($currentRequest): ?>
            <button disabled><?= ucfirst($currentRequest['status']) ?></button>

        <?php else: ?>
            <form method="POST" action="request_academic.php">
                <input type="hidden" name="academic_id" value="<?= $a['id'] ?>">
                <button class="button">Request</button>
            </form>
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>

</table>

<?php endif; ?>

</div>
</body>
</html>
