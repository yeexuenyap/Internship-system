<?php
session_start();
include("../config/db.php");

if ($_SESSION['role'] != 'company') {
    header("Location: ../auth/login.php");
    exit();
}

$student_id = $_POST['student_id'];
$internship_id = $_POST['internship_id'];
$date = $_POST['feedback_date'];
$content = $_POST['content'];

// Insert or update feedback for the day
$stmt = $conn->prepare(
    "INSERT INTO feedback (student_id, internship_id, feedback_date, content)
     VALUES (?, ?, ?, ?)
     ON DUPLICATE KEY UPDATE content = ?"
);
$stmt->bind_param("iisss", $student_id, $internship_id, $date, $content, $content);
$stmt->execute();
$_SESSION['success'] = "Daily feedback has been submitted successfully.";
header("Location: company_feedback.php");
exit();

