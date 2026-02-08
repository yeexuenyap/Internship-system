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
    <title>Company Supervisor - Logbook Feedback</title>
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
</div>

<div class="container">
<h2>Provide Feedback on Logs</h2>

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
    // Fetch logs for this student
    $logs = $conn->prepare(
        "SELECT * FROM logbook WHERE student_id = ? AND internship_id = ? ORDER BY log_date DESC"
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

            <!-- Existing feedback -->
            <div style="background:#e0e0e0; padding:5px;">
                <strong>Previous Feedback:</strong>
                <?php
                $fb_stmt = $conn->prepare("SELECT * FROM logbook_feedback WHERE log_id = ? ORDER BY created_at DESC");
                $fb_stmt->bind_param("i", $log['id']);
                $fb_stmt->execute();
                $fb_result = $fb_stmt->get_result();
                if ($fb_result->num_rows > 0):
                    while ($fb = $fb_result->fetch_assoc()):
                        echo "<p><em>".nl2br(htmlspecialchars($fb['content']))."</em></p>";
                    endwhile;
                else:
                    echo "<p><i>No feedback yet.</i></p>";
                endif;
                ?>
            </div>

            <!-- Add new feedback -->
            <form method="POST" action="submit_log_feedback.php">
                <input type="hidden" name="log_id" value="<?= $log['id'] ?>">
                <textarea name="content" placeholder="Enter feedback" required></textarea>
                <button class="button">Submit Feedback</button>
            </form>
        </div>
    <?php
        endwhile;
    else:
        echo "<p><i>No logs available yet.</i></p>";
    endif;
    ?>
</div>
<?php
    endwhile;
else:
    echo "<p><i>No interns assigned.</i></p>";
endif;
?>

</div>
</body>
</html>
