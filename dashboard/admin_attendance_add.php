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
$date = $_POST['date'] ?? '';
$status = $_POST['status'] ?? '';

if ($student_id <= 0 || $internship_id <= 0 || $date === '' || !in_array($status, ['present','absent'], true)) {
    $_SESSION['error'] = "Invalid input.";
    header("Location: admin_student_attendance.php?student_id=".$student_id);
    exit();
}

// Ensure student has internship in progress
$verify = $conn->prepare(
    "SELECT 1 FROM student_internships
     WHERE student_id=? AND internship_id=? AND status='in_progress' LIMIT 1"
);
$verify->bind_param("ii", $student_id, $internship_id);
$verify->execute();
$verify->store_result();
if ($verify->num_rows === 0) {
    $_SESSION['error'] = "Student has no internship in progress.";
    header("Location: admin_student_attendance.php?student_id=".$student_id);
    exit();
}

// Check if attendance already exists for that date
$check = $conn->prepare(
    "SELECT id FROM attendance
     WHERE student_id=? AND internship_id=? AND date=? LIMIT 1"
);
$check->bind_param("iis", $student_id, $internship_id, $date);
$check->execute();
$existing = $check->get_result()->fetch_assoc();

if ($existing) {
    $upd = $conn->prepare("UPDATE attendance SET status=? WHERE id=?");
    $upd->bind_param("si", $status, $existing['id']);
    $ok = $upd->execute();
    $_SESSION['success'] = $ok ? "Attendance updated." : "Failed to update attendance.";
} else {
    $ins = $conn->prepare("INSERT INTO attendance (student_id, internship_id, date, status) VALUES (?,?,?,?)");
    $ins->bind_param("iiss", $student_id, $internship_id, $date, $status);
    $ok = $ins->execute();
    $_SESSION['success'] = $ok ? "Attendance added." : "Failed to add attendance.";
}

header("Location: admin_student_attendance.php?student_id=".$student_id);
exit();
