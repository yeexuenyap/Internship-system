<?php
session_start();

if (isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'student') {
        header("Location: dashboard/student.php");
    } elseif ($_SESSION['role'] == 'company') {
        header("Location: dashboard/company.php");
    } elseif ($_SESSION['role'] == 'academic') {
        header("Location: dashboard/academic.php");
    }

    exit();
}

// Not logged in
header("Location: auth/login.php");
exit();
