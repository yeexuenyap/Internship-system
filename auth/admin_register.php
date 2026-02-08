<?php
session_start();
include("../config/db.php");

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $setup_password = $_POST['setup_password'] ?? '';

    if ($name === '' || $email === '' || $password === '' || $setup_password === '') {
        $error = "All fields are required.";
    } else {
        // Get setup hash from DB
        $s = $conn->prepare("SELECT admin_setup_password_hash FROM settings WHERE id = 1");
        $s->execute();
        $row = $s->get_result()->fetch_assoc();

        if (!$row) {
            $error = "Admin setup password not configured in database.";
        } elseif (!password_verify($setup_password, $row['admin_setup_password_hash'])) {
            $error = "Invalid admin setup password.";
        } else {
            // Check if email already exists
            $check = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
            $check->bind_param("s", $email);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                $error = "Email already registered.";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);

                $ins = $conn->prepare(
                    "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')"
                );
                $ins->bind_param("sss", $name, $email, $hash);

                if ($ins->execute()) {
                    header("Location: login.php");
                    exit();
                } else {
                    $error = "Failed to create admin account.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Register</title>
    <link rel="stylesheet" href="../assets/dashboard.css">
</head>
<body>

<div class="container">
    <h2>Create Admin Account</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <div class="card">
        <form method="POST">
            <input type="text" name="name" placeholder="Admin Name" required>
            <input type="email" name="email" placeholder="Admin Email" required>
            <input type="password" name="password" placeholder="Admin Password" required>

            <input type="password" name="setup_password" placeholder="Admin Setup Password" required>

            <button class="button">Create Admin</button>
        </form>
    </div>

    <p style="margin-top:10px;">
        <a href="login.php">Back to Login</a>
    </p>
</div>

</body>
</html>
