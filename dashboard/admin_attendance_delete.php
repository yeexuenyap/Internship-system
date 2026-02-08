<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin_students.php");
    exit();
}

$student_id = (int)($_POST['student_id'] ?? 0);
$internship_id = (int)($_POST['internship_id'] ?? 0);
$attendance_id = (int)($_POST['attendance_id'] ?? 0);

if ($student_id <= 0 || $internship_id <= 0 || $attendance_id <= 0) {
    $_SESSION['error'] = "Invalid request.";
    header("Location: admin_student_attendance.php?student_id=".$student_id);
    exit();
}

// Delete only if it belongs to that student & internship
$stmt = $conn->prepare("DELETE FROM attendance WHERE id=? AND student_id=? AND internship_id=?");
$stmt->bind_param("iii", $attendance_id, $student_id, $internship_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Attendance deleted.";
} else {
    $_SESSION['error'] = "Failed to delete attendance.";
}

header("Location: admin_student_attendance.php?student_id=".$student_id);
exit();
