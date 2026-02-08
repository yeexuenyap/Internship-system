<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'company') {
    header("Location: ../auth/login.php");
    exit();
}

$company_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: company.php");
    exit();
}

// ================= INPUT =================
$title = trim($_POST['title']);
$description = trim($_POST['description']);
$faculty = $_POST['faculty'];

// ================= VALIDATION =================
if (empty($title) || empty($description) || empty($faculty)) {
    $_SESSION['msg'] = "All fields are required.";
    header("Location: company.php");
    exit();
}

$allowed_faculties = ['FCI','FCM','FIST','FOM'];
if (!in_array($faculty, $allowed_faculties)) {
    $_SESSION['msg'] = "Invalid faculty selected.";
    header("Location: company.php");
    exit();
}

// ================= INSERT =================
$stmt = $conn->prepare(
    "INSERT INTO internships (company_id, title, description, faculty)
     VALUES (?, ?, ?, ?)"
);

$stmt->bind_param("isss", $company_id, $title, $description, $faculty);

if ($stmt->execute()) {
    $_SESSION['msg'] = "Internship posted successfully.";
} else {
    $_SESSION['msg'] = "Failed to post internship.";
}

// ================= REDIRECT =================
header("Location: company.php");
exit();
