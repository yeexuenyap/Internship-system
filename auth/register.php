<?php
include("../config/db.php");

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        $error = "Email already exists!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="auth-card">
    <h2>Register</h2>

    <?php if (isset($error)) echo "<p style='color:red;text-align:center;'>$error</p>"; ?>

    <form method="POST">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>

        <select name="role" required>
            <option value="">Select Role</option>
            <option value="student">Student</option>
            <option value="company">Company Supervisor</option>
            <option value="academic">Academic Supervisor</option>
        </select>

        <button type="submit" name="register">Register</button>
    </form>

    <div class="link">
        <p>Already have an account?</p>
        <a href="login.php">Back to Login</a>
    </div>
</div>

</body>
</html>
