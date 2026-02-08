<?php
session_start();
include("../config/db.php");

if ($_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$student_id = $_SESSION['user_id'];
$internship_id = $_POST['internship_id'];

$stmt = $conn->prepare(
    "DELETE FROM student_internships
     WHERE student_id = ? AND internship_id = ?"
);
$stmt->bind_param("ii", $student_id, $internship_id);
$stmt->execute();

header("Location: student.php");
exit();
