<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin_companies.php");
    exit();
}

$company_id = (int)($_POST['company_id'] ?? 0);
$id = (int)($_POST['id'] ?? 0);

if ($company_id <= 0 || $id <= 0) {
    $_SESSION['error'] = "Invalid request.";
    header("Location: admin_companies.php");
    exit();
}

// Ensure this feedback belongs to an internship posted by the company
$verify = $conn->prepare("
    SELECT 1
    FROM feedback f
    JOIN internships i ON f.internship_id = i.id
    WHERE f.id = ? AND i.company_id = ?
");
$verify->bind_param("ii", $id, $company_id);
$verify->execute();
$verify->store_result();

if ($verify->num_rows === 0) {
    $_SESSION['error'] = "Unauthorized action.";
    header("Location: admin_company_records.php?company_id=".$company_id."&tab=feedback");
    exit();
}

$stmt = $conn->prepare("DELETE FROM feedback WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Feedback deleted.";
} else {
    $_SESSION['error'] = "Failed to delete feedback.";
}

header("Location: admin_company_records.php?company_id=".$company_id."&tab=feedback");
exit();
