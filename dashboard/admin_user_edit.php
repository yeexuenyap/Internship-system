<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header("Location: admin.php"); exit(); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? '';
    $faculty = $_POST['faculty'] ?? '';

    if ($name === '' || $email === '' || !in_array($role, ['student','company','academic','admin'])) {
        $_SESSION['error'] = "Invalid input.";
        header("Location: admin_user_edit.php?id=".$id);
        exit();
    }

    $faculty = ($faculty === '') ? NULL : $faculty;

    // prevent email clash
    $check = $conn->prepare("SELECT id FROM users WHERE email = ? AND id <> ? LIMIT 1");
    $check->bind_param("si", $email, $id);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        $_SESSION['error'] = "Email already used by another user.";
        header("Location: admin_user_edit.php?id=".$id);
        exit();
    }

    $stmt = $conn->prepare("UPDATE users SET name=?, email=?, role=?, faculty=? WHERE id=?");
    $stmt->bind_param("ssssi", $name, $email, $role, $faculty, $id);
    $stmt->execute();

    $_SESSION['success'] = "User updated.";
    header("Location: admin.php");
    exit();
}

$stmt = $conn->prepare("SELECT id, name, email, role, faculty FROM users WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) { header("Location: admin.php"); exit(); }

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="../assets/dashboard.css">
    <style>
        .card { background:#fff; padding:15px; border-radius:8px; margin:15px 0; }
        input, select { width:100%; padding:10px; margin:8px 0; }
        .btn { padding:8px 12px; border:none; border-radius:4px; color:#fff; cursor:pointer; }
        .btn-blue { background:#4e73df; }
        .btn-gray { background:#858796; }
        .msg-err { background:#ffe7e7; padding:10px; border-radius:6px; }
    </style>
</head>
<body>

<div class="navbar">
    <div>Edit User</div>
    <div>
        <a href="admin.php">Back</a>
        <a href="../auth/logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <h2>Edit User #<?= (int)$user['id'] ?></h2>

    <?php if ($error): ?><div class="msg-err"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="card">
        <form method="POST">
            <label>Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <label>Role</label>
            <select name="role" required>
                <?php foreach (['student','company','academic','admin'] as $r): ?>
                    <option value="<?= $r ?>" <?= ($user['role']===$r?'selected':'') ?>><?= ucfirst($r) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Faculty</label>
            <select name="faculty">
                <option value="">None</option>
                <?php foreach (['FCI','FCM','FOM','FIST'] as $f): ?>
                    <option value="<?= $f ?>" <?= ($user['faculty']===$f?'selected':'') ?>><?= $f ?></option>
                <?php endforeach; ?>
            </select>

            <button class="btn btn-blue" type="submit">Save</button>
            <a class="btn btn-gray" href="admin.php" style="text-decoration:none; display:inline-block;">Cancel</a>
        </form>
    </div>
</div>

</body>
</html>
