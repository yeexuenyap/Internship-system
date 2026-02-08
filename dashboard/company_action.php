<?php
session_start();
include("../config/db.php");

if ($_SESSION['role'] != 'company') {
    header("Location: ../auth/login.php");
    exit();
}

$internship_id = $_POST['internship_id'];
$student_id = $_POST['student_id'];
$action = $_POST['action'];

if ($action == 'approve') {
    $status = 'in_progress';
} elseif ($action == 'reject') {
    $status = 'rejected';
}

$stmt = $conn->prepare(
    "UPDATE student_internships
     SET status = ?
     WHERE internship_id = ? AND student_id = ?"
);
$stmt->bind_param("sii", $status, $internship_id, $student_id);
$stmt->execute();

header("Location:company.php");
exit();
