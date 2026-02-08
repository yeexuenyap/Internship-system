<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin_notifications.php");
    exit();
}

$title = trim($_POST['title'] ?? '');
$message = trim($_POST['message'] ?? '');
$target_role = $_POST['target_role'] ?? 'all';

$valid = ['all','student','company','academic'];
if ($title === '' || $message === '' || !in_array($target_role, $valid, true)) {
    $_SESSION['error'] = "Invalid input.";
    header("Location: admin_notifications.php");
    exit();
}

$stmt = $conn->prepare("INSERT INTO notifications (title, message, target_role) VALUES (?,?,?)");
$stmt->bind_param("sss", $title, $message, $target_role);

if ($stmt->execute()) {
    $_SESSION['success'] = "Notification sent.";
} else {
    $_SESSION['error'] = "Failed to send notification.";
}

header("Location: admin_notifications.php");
exit();
