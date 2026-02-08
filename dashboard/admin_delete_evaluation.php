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
$internship_id = (int)($_POST['internship_id'] ?? 0);
$student_id = (int)($_POST['student_id'] ?? 0);

if ($company_id <= 0 || $internship_id <= 0 || $student_id <= 0) {
    $_SESSION['error'] = "Invalid request.";
    header("Location: admin_companies.php");
    exit();
}

// Ensure internship belongs to that company (safety)
$verify = $conn->prepare("SELECT 1 FROM internships WHERE id=? AND company_id=?");
$verify->bind_param("ii", $internship_id, $company_id);
$verify->execute();
$verify->store_result();

if ($verify->num_rows === 0) {
    $_SESSION['error'] = "Unauthorized action.";
    header("Location: admin_company_records.php?company_id=".$company_id."&tab=evaluations");
    exit();
}

// "Delete" evaluation by nulling it out
$stmt = $conn->prepare(
    "UPDATE student_internships
     SET evaluation_score = NULL, evaluation_comment = NULL
     WHERE student_id = ? AND internship_id = ?"
);
$stmt->bind_param("ii", $student_id, $internship_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Evaluation deleted.";
} else {
    $_SESSION['error'] = "Failed to delete evaluation.";
}

header("Location: admin_company_records.php?company_id=".$company_id."&tab=evaluations");
exit();
