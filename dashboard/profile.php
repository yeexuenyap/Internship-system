<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $faculty = $_POST['faculty'];

    $stmt = $conn->prepare("UPDATE users SET faculty = ? WHERE id = ?");
    $stmt->bind_param("si", $faculty, $user_id);
    $stmt->execute();

    $_SESSION['msg'] = "Profile updated successfully!";
    header("Location: profile.php");
    exit();
}

$stmt = $conn->prepare("
    SELECT name, email, faculty, role
    FROM users
    WHERE id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$role = $user['role'];
$msg = $_SESSION['msg'] ?? '';
unset($_SESSION['msg']);
?>


<!DOCTYPE html>
<html>

<head>
    <title>User Profile</title>
    <link rel="stylesheet" href="../assets/dashboard.css">
</head>

<body>

    <div class="navbar">
        <div>Profile</div>
        <div>
            <?php if ($user['role'] == 'student'): ?>
                <a href="student.php">Dashboard</a>
                <a href="student_logbook.php">Logbook</a>
                <a href="student_feedback.php">Daily Feedback</a>
                <a href="student_attendance.php">Attendance</a>
                <a href="student_academic_apply.php">Academic Supervisor</a>
                <a href="notifications.php">Notifications</a>

            <?php elseif ($role === 'company'): ?>
                <a href="company.php">Dashboard</a>
                <a href="company_complete.php">Completion & Evaluation</a>
                <a href="company_logbook.php">Student Logbook</a>
                <a href="company_feedback.php">Daily Feedback</a>
                <a href="company_logbook_feedback.php">Logbook Feedback</a>
                <a href="company_attendance.php">Attendance</a>
                <a href="notifications.php">Notifications</a>

            <?php elseif ($role === 'academic'): ?>
                <a href="academic_logbook.php">Student Logbook</a>
                <a href="academic_attendance.php">Student Attendance</a>
                <a href="notifications.php">Notifications</a>
            <?php endif; ?>

            <a href="profile.php"><b>Profile</b></a>
            <a href="../auth/logout.php">Logout</a>
        </div>
    </div>


    <div class="container">
        <h2>User Profile</h2>

        <?php if ($msg): ?>
            <p style="color:green"><?= $msg ?></p><?php endif; ?>

        <div class="card">
            <form method="POST">
                <label>Name</label>
                <input type="text" value="<?= htmlspecialchars($user['name']) ?>" disabled>

                <label>Email</label>
                <input type="text" value="<?= htmlspecialchars($user['email']) ?>" disabled>

                <label>Faculty</label>
                <select name="faculty" required>
                    <option value="">-- Select Faculty --</option>
                    <?php
                    $faculties = ['FCI', 'FCM', 'FIST', 'FOM'];
                    foreach ($faculties as $f) {
                        $selected = ($user['faculty'] == $f) ? 'selected' : '';
                        echo "<option value='$f' $selected>$f</option>";
                    }
                    ?>
                </select>

                <button class="button">Save Profile</button>
            </form>
        </div>
    </div>

</body>

</html>