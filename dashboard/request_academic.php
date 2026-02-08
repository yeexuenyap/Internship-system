<?php
session_start();
include("../config/db.php");

if ($_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$student_id = $_SESSION['user_id'];
$academic_id = $_POST['academic_id'];

// Prevent duplicate pending request
$check = $conn->prepare(
    "SELECT id FROM academic_requests
     WHERE student_id = ? AND status = 'pending'"
);
$check->bind_param("i", $student_id);
$check->execute();
$check->store_result();

if ($check->num_rows == 0) {
    $stmt = $conn->prepare(
        "INSERT INTO academic_requests (student_id, academic_id)
         VALUES (?, ?)"
    );
    $stmt->bind_param("ii", $student_id, $academic_id);
    $stmt->execute();
}

header("Location:student.php");
exit();
