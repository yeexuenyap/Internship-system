<?php
session_start();
include("../config/db.php");

if ($_SESSION['role'] != 'company') {
    header("Location: ../auth/login.php");
    exit();
}

$log_id = $_POST['log_id'];
$supervisor_id = $_SESSION['user_id'];
$content = $_POST['content'];

$stmt = $conn->prepare(
    "INSERT INTO logbook_feedback (log_id, supervisor_id, content) VALUES (?, ?, ?)"
);
$stmt->bind_param("iis", $log_id, $supervisor_id, $content);
$stmt->execute();

header("Location: company_logbook_feedback.php");
exit();
