<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin.php");
    exit();
}

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) { header("Location: admin.php"); exit(); }

$tempPassword = "Temp@1234";
$hash = password_hash($tempPassword, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
$stmt->bind_param("si", $hash, $id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Password reset. New temp password: ".$tempPassword;
} else {
    $_SESSION['error'] = "Failed to reset password.";
}

header("Location: admin.php");
exit();
