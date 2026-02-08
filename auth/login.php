<?php
session_start();
include("../config/db.php");

// LOGIN LOGIC (same as before)
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_id'] = $user['id'];

            if ($user['role'] == 'student') {
                header("Location: ../dashboard/student.php");
            } elseif ($user['role'] == 'company') {
                header("Location: ../dashboard/company.php");
            } elseif($user['role'] == 'academic') {
                header("Location: ../dashboard/academic.php");
            } elseif($user['role'] == 'admin'){
                header("Location: ../dashboard/admin.php");
            }
            exit();
        }
    }
    $error = "Invalid email or password";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="auth-card">
    <h2>Login</h2>

    <?php if (isset($error)) echo "<p style='color:red;text-align:center;'>$error</p>"; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>

    <!-- REGISTER BUTTON -->
    <div class="link">
        <p>Don't have an account?</p>
        <a href="register.php">Register Here</a>
    </div>
</div>

</body>
</html>
