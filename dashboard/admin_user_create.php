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

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$role = $_POST['role'] ?? '';
$faculty = $_POST['faculty'] ?? '';
$password = $_POST['password'] ?? '';

if ($name === '' || $email === '' || $password === '' || !in_array($role, ['student','company','academic','admin'])) {
    $_SESSION['error'] = "Invalid input.";
    header("Location: admin.php");
    exit();
}

$faculty = ($faculty === '') ? NULL : $faculty;

$check = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    $_SESSION['error'] = "Email already exists.";
    header("Location: admin.php");
    exit();
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (name, email, password, role, faculty) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $email, $hash, $role, $faculty);

if ($stmt->execute()) {
    $_SESSION['success'] = "User created successfully.";
} else {
    $_SESSION['error'] = "Failed to create user.";
}

header("Location: admin.php");
exit();
