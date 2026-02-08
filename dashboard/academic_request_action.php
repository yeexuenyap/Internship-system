<?php
session_start();
include("../config/db.php");

if ($_SESSION['role'] != 'academic') {
    header("Location: ../auth/login.php");
    exit();
}

$request_id = $_POST['request_id'];
$student_id = $_POST['student_id'];
$action = $_POST['action'];

if ($action == 'approve') {
    // Get academic id
    $stmt = $conn->prepare(
        "SELECT academic_id FROM academic_requests WHERE id = ?"
    );
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $stmt->bind_result($academic_id);
    $stmt->fetch();
    $stmt->close();

    // Assign supervisor
    $assign = $conn->prepare(
        "UPDATE users SET academic_supervisor_id = ? WHERE id = ?"
    );
    $assign->bind_param("ii", $academic_id, $student_id);
    $assign->execute();

    // Mark request approved
    $conn->query(
        "UPDATE academic_requests SET status='approved' WHERE id=$request_id"
    );

} else {
    // Reject request
    $conn->query(
        "UPDATE academic_requests SET status='rejected' WHERE id=$request_id"
    );
}

header("Location:academic.php");
exit();
