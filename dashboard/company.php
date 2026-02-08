<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'company') {
    header("Location: ../auth/login.php");
    exit();
}

$company_id = $_SESSION['user_id'];

// Post internship
if (isset($_POST['post'])) {
    $stmt = $conn->prepare(
        "INSERT INTO internships (company_id, title, description, location, duration, faculty)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param(
        "isssss",
        $company_id,
        $_POST['title'],
        $_POST['description'],
        $_POST['location'],
        $_POST['duration'],
        $_POST['faculty']
    );
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Company Supervisor Dashboard</title>
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
    <a href="notifications.php">Notifications</a>
    <a href="../auth/logout.php">Logout</a>
</div>

<div class="container">

    <button class="button" onclick="toggleForm()">➕ Post Internship</button>

    <div class="card hidden" id="postForm">
        <h3>New Internship</h3>
        <form method="POST">
            <input type="text" name="title" placeholder="Title" required>
            <textarea name="description" placeholder="Description" required></textarea>
            <input type="text" name="location" placeholder="Location">
            <input type="text" name="duration" placeholder="Duration">

            <!-- ✅ NEW: Faculty -->
            <select name="faculty" required>
                <option value="">-- Select Faculty --</option>
                <option value="FCI">FCI</option>
                <option value="FCM">FCM</option>
                <option value="FOM">FOM</option>
                <option value="FIST">FIST</option>
            </select>

            <button class="button" name="post">Post</button>
        </form>
    </div>

    <h2>Your Internships</h2>

    <?php
    $internships = $conn->prepare(
        "SELECT * FROM internships WHERE company_id = ?"
    );
    $internships->bind_param("i", $company_id);
    $internships->execute();
    $internshipResult = $internships->get_result();

    while ($internship = $internshipResult->fetch_assoc()):
    ?>
        <div class="card">
            <h3><?= htmlspecialchars($internship['title']) ?></h3>
            <p><?= htmlspecialchars($internship['description']) ?></p>
            <p><b>Faculty:</b> <?= htmlspecialchars($internship['faculty']) ?></p>

            <h4>Applicants</h4>

            <?php
            $students = $conn->prepare(
                "SELECT users.id AS student_id, users.name, users.email, student_internships.status
                 FROM student_internships
                 JOIN users ON student_internships.student_id = users.id
                 WHERE student_internships.internship_id = ?"
            );
            $students->bind_param("i", $internship['id']);
            $students->execute();
            $studentResult = $students->get_result();

            if ($studentResult->num_rows > 0):
            ?>
            <table width="100%" border="1" cellpadding="8">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

                <?php while ($student = $studentResult->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($student['name']) ?></td>
                    <td><?= htmlspecialchars($student['email']) ?></td>
                    <td><?= ucfirst($student['status']) ?></td>
                    <td>
                        <?php if ($student['status'] == 'applied'): ?>
                            <form method="POST" action="company_action.php" style="display:inline;">
                                <input type="hidden" name="internship_id" value="<?= $internship['id'] ?>">
                                <input type="hidden" name="student_id" value="<?= $student['student_id'] ?>">
                                <input type="hidden" name="action" value="approve">
                                <button class="button">Approve</button>
                            </form>

                            <form method="POST" action="company_action.php" style="display:inline;">
                                <input type="hidden" name="internship_id" value="<?= $internship['id'] ?>">
                                <input type="hidden" name="student_id" value="<?= $student['student_id'] ?>">
                                <input type="hidden" name="action" value="reject">
                                <button class="button" style="background:#e74a3b;">Reject</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
            <?php else: ?>
                <p><i>No applicants yet.</i></p>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>

</div>

<script>
function toggleForm() {
    document.getElementById("postForm").classList.toggle("hidden");
}
</script>

</body>
</html>
