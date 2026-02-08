<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin_internships.php");
    exit();
}

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    $_SESSION['error'] = "Invalid internship ID.";
    header("Location: admin_internships.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| If you have foreign keys with ON DELETE CASCADE, you can delete internships directly.
| If not, delete from student_internships first to avoid orphan records.
|--------------------------------------------------------------------------
*/
$conn->begin_transaction();
try {
    $delApps = $conn->prepare("DELETE FROM student_internships WHERE internship_id = ?");
    $delApps->bind_param("i", $id);
    $delApps->execute();

    $delIntern = $conn->prepare("DELETE FROM internships WHERE id = ?");
    $delIntern->bind_param("i", $id);
    $delIntern->execute();

    $conn->commit();
    $_SESSION['success'] = "Internship deleted successfully.";
} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error'] = "Failed to delete internship.";
}

header("Location: admin_internships.php");
exit();
