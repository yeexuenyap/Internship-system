<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'company') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: company.php");
    exit();
}

$company_id   = $_SESSION['user_id'];
$student_id   = $_POST['student_id'] ?? null;
$internship_id= $_POST['internship_id'] ?? null;
$date         = $_POST['date'] ?? null;
$today = date('Y-m-d');
if ($date > $today) {
    $_SESSION['error'] = "Attendance cannot be marked for future dates.";
    header("Location: company_attendance.php");
    exit();
}
$status       = $_POST['status'] ?? null;

if (!$student_id || !$internship_id || !$date || !in_array($status, ['present','absent'])) {
    header("Location: company.php");
    exit();
}

/* Verify internship belongs to this company */
$verify = $conn->prepare("SELECT 1 FROM internships WHERE id = ? AND company_id = ?");
$verify->bind_param("ii", $internship_id, $company_id);
$verify->execute();
$verify->store_result();

if ($verify->num_rows === 0) {
    $_SESSION['error'] = "Unauthorized attendance action.";
    header("Location: company.php");
    exit();
}

/* Optional: only allow attendance for in_progress students */
$check = $conn->prepare(
    "SELECT 1 FROM student_internships
     WHERE student_id = ? AND internship_id = ? AND status = 'in_progress'"
);
$check->bind_param("ii", $student_id, $internship_id);
$check->execute();
$check->store_result();

if ($check->num_rows === 0) {
    $_SESSION['error'] = "Attendance can only be marked for in-progress interns.";
    header("Location: company.php");
    exit();
}

$today = date('Y-m-d');

if ($date > $today) {
    $_SESSION['error'] = "Attendance cannot be marked for future dates.";
    header("Location: company.php");
    exit();
}

$stmt = $conn->prepare(
    "INSERT INTO attendance (student_id, internship_id, date, status)
     VALUES (?, ?, ?, ?)
     ON DUPLICATE KEY UPDATE status = ?"
);
$stmt->bind_param("iisss", $student_id, $internship_id, $date, $status, $status);
$stmt->execute();

$_SESSION['success'] = "Attendance has been given successfully.";
header("Location: company_attendance.php");
exit();
