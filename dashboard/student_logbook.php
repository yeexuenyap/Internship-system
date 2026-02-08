<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

// Fetch student's internship (in-progress or completed)
$stmt = $conn->prepare(
    "SELECT internship_id FROM student_internships 
     WHERE student_id = ? AND status IN ('in_progress','completed') LIMIT 1"
);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$internship = $stmt->get_result()->fetch_assoc();
$internship_id = $internship['internship_id'] ?? null;

// Flash message handling
$msg = $_SESSION['msg'] ?? '';
unset($_SESSION['msg']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Logbook</title>
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
<h2>Logbook Entries</h2>

<?php if ($msg): ?>
<p style="color:green"><?= $msg ?></p>
<?php endif; ?>

<?php if (!$internship_id): ?>
<p><i>You need to have an internship in progress or completed to add logs.</i></p>
<?php else: ?>

<!-- Add Log Entry -->
<div class="card">
    <h3>Add New Log</h3>
    <form method="POST" action="student_logbook_action.php">
        <input type="hidden" name="internship_id" value="<?= $internship_id ?>">
        <input type="date" name="log_date" required value="<?= date('Y-m-d') ?>">
        <input type="text" name="title" placeholder="Log Title" required>
        <textarea name="content" placeholder="Describe your activities" required></textarea>
        <button class="button">Add Log</button>
    </form>
</div>

<!-- List Logs -->
<?php
$logs = $conn->prepare(
    "SELECT * FROM logbook WHERE student_id = ? AND internship_id = ? ORDER BY log_date DESC"
);
$logs->bind_param("ii", $student_id, $internship_id);
$logs->execute();
$logResult = $logs->get_result();

if ($logResult->num_rows > 0):
    while ($log = $logResult->fetch_assoc()):
?>
<div class="card">
    <h4><?= htmlspecialchars($log['log_date']) ?> - <?= htmlspecialchars($log['title']) ?></h4>
    <p><?= nl2br(htmlspecialchars($log['content'])) ?></p>

    <!-- Supervisor Feedback -->
    <div style="background:#f1f1f1; padding:5px; margin-top:5px;">
        <strong>Supervisor Feedback:</strong>
        <?php
        $fb_stmt = $conn->prepare("SELECT * FROM logbook_feedback WHERE log_id = ? ORDER BY created_at DESC");
        $fb_stmt->bind_param("i", $log['id']);
        $fb_stmt->execute();
        $fb_result = $fb_stmt->get_result();

        if ($fb_result->num_rows > 0):
            while ($fb = $fb_result->fetch_assoc()):
        ?>
            <p><em><?= nl2br(htmlspecialchars($fb['content'])) ?></em></p>
        <?php
            endwhile;
        else:
            echo "<p><i>No feedback yet.</i></p>";
        endif;
        ?>
    </div>

    <!-- Delete Log -->
    <form method="POST" action="student_logbook_action.php" style="margin-top:5px;">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="log_id" value="<?= $log['id'] ?>">
        <button class="button" style="background:#e74a3b;" onclick="return confirm('Delete this log?')">Delete</button>
    </form>
</div>
<?php
    endwhile;
else:
    echo "<p><i>No logs added yet.</i></p>";
endif;
?>

<?php endif; ?>
</div>

</body>
</html>
