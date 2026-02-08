<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

$action = $_POST['action'] ?? 'add';

if ($action == 'add') {
    $internship_id = $_POST['internship_id'];
    $log_date = $_POST['log_date'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $conn->prepare(
        "INSERT INTO logbook (student_id, internship_id, log_date, title, content)
         VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("iisss", $student_id, $internship_id, $log_date, $title, $content);
    $stmt->execute();
    $_SESSION['msg'] = "Log added successfully!";

} elseif ($action == 'delete') {
    $log_id = $_POST['log_id'];
    $stmt = $conn->prepare(
        "DELETE FROM logbook WHERE id = ? AND student_id = ?"
    );
    $stmt->bind_param("ii", $log_id, $student_id);
    $stmt->execute();
    $_SESSION['msg'] = "Log deleted successfully!";
}

header("Location: student_logbook.php");
exit();
