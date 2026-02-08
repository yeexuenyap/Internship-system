<?php
session_start();
include("../config/db.php");

if ($_SESSION['role'] != 'company') {
    header("Location: ../auth/login.php");
    exit();
}

$student_id = $_POST['student_id'];
$internship_id = $_POST['internship_id'];
$score = $_POST['score'];
$comment = $_POST['comment'];

$stmt = $conn->prepare(
    "UPDATE student_internships
     SET status = 'completed',
         evaluation_score = ?,
         evaluation_comment = ?
     WHERE student_id = ? AND internship_id = ?"
);
$stmt->bind_param("isii", $score, $comment, $student_id, $internship_id);
$stmt->execute();
$_SESSION['success'] = "Final Evaluation has been given successfully.";
header("Location: company_complete.php");
exit();

