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

if ($id <= 0) {
    $_SESSION['error'] = "Invalid user.";
    header("Location: admin.php");
    exit();
}

if ($id === (int)$_SESSION['user_id']) {
    $_SESSION['error'] = "You cannot delete your own account.";
    header("Location: admin.php");
    exit();
}

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['success'] = "User deleted.";
} else {
    $_SESSION['error'] = "Failed to delete user.";
}

header("Location: admin.php");
exit();
