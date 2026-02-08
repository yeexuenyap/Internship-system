<?php
session_start();
include("../config/db.php");

// Ensure student only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: student.php");
    exit();
}

$student_id = $_SESSION['user_id'];
$internship_id = $_POST['internship_id'] ?? null;

if (!$internship_id) {
    header("Location: student.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| Prevent multiple active internships
|--------------------------------------------------------------------------
*/
$checkActive = $conn->prepare(
    "SELECT 1 FROM student_internships
     WHERE student_id = ? AND status IN ('applied','in_progress')"
);
$checkActive->bind_param("i", $student_id);
$checkActive->execute();
$checkActive->store_result();

if ($checkActive->num_rows > 0) {
    $_SESSION['error'] = "You already have an active internship application.";
    header("Location: student.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| Prevent duplicate application
|--------------------------------------------------------------------------
*/
$checkDuplicate = $conn->prepare(
    "SELECT 1 FROM student_internships
     WHERE student_id = ? AND internship_id = ?"
);
$checkDuplicate->bind_param("ii", $student_id, $internship_id);
$checkDuplicate->execute();
$checkDuplicate->store_result();

if ($checkDuplicate->num_rows > 0) {
    $_SESSION['error'] = "You have already applied for this internship.";
    header("Location: student.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| Apply Internship
|--------------------------------------------------------------------------
*/
$stmt = $conn->prepare(
    "INSERT INTO student_internships (student_id, internship_id, status)
     VALUES (?, ?, 'applied')"
);
$stmt->bind_param("ii", $student_id, $internship_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Internship application submitted successfully!";
} else {
    $_SESSION['error'] = "Failed to apply for internship.";
}

header("Location: student.php");
exit();
