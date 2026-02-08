<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: admin_internships.php");
    exit();
}

$valid_faculties = ['FCI','FCM','FOM','FIST'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $duration = trim($_POST['duration'] ?? '');
    $faculty = $_POST['faculty'] ?? '';

    if ($title === '' || $description === '' || !in_array($faculty, $valid_faculties, true)) {
        $_SESSION['error'] = "Invalid input. Title/Description/Faculty required.";
        header("Location: admin_internship_edit.php?id=".$id);
        exit();
    }

    $stmt = $conn->prepare(
        "UPDATE internships
         SET title = ?, description = ?, location = ?, duration = ?, faculty = ?
         WHERE id = ?"
    );
    $stmt->bind_param("sssssi", $title, $description, $location, $duration, $faculty, $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Internship updated.";
        header("Location: admin_internships.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to update internship.";
        header("Location: admin_internship_edit.php?id=".$id);
        exit();
    }
}

$stmt = $conn->prepare("SELECT * FROM internships WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$intern = $stmt->get_result()->fetch_assoc();

if (!$intern) {
    header("Location: admin_internships.php");
    exit();
}

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Internship</title>
    <style>
        body { background:#f6f7fb; font-family: Arial, sans-serif; margin:0; }
        .navbar { background:#111827; color:#fff; padding:12px 16px; display:flex; justify-content:space-between; }
        .navbar a { color:#fff; text-decoration:none; margin-left:12px; }
        .container { max-width:800px; margin:16px auto; padding:0 16px; }
        .card { background:#fff; border-radius:12px; padding:14px; box-shadow:0 8px 20px rgba(17,24,39,0.06); }
        label { font-size:12px; color:#6b7280; display:block; margin:10px 0 6px; }
        input, select, textarea { width:100%; padding:10px; border:1px solid #e5e7eb; border-radius:10px; }
        textarea { min-height:120px; resize:vertical; }
        .btn { padding:10px 12px; border:none; border-radius:10px; color:#fff; cursor:pointer; }
        .btn-blue { background:#2563eb; }
        .btn-gray { background:#6b7280; }
        .msg { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; padding:10px; border-radius:12px; margin-bottom:12px; }
    </style>
</head>
<body>

<div class="navbar">
    <div><b>Admin</b> — Edit Internship #<?= (int)$intern['id'] ?></div>
    <div>
        <a href="admin_internships.php">Back</a>
        <a href="../auth/logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <?php if ($error): ?><div class="msg"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="card">
        <form method="POST">
            <label>Title</label>
            <input type="text" name="title" value="<?= htmlspecialchars($intern['title']) ?>" required>

            <label>Description</label>
            <textarea name="description" required><?= htmlspecialchars($intern['description']) ?></textarea>

            <label>Location</label>
            <input type="text" name="location" value="<?= htmlspecialchars($intern['location'] ?? '') ?>">

            <label>Duration</label>
            <input type="text" name="duration" value="<?= htmlspecialchars($intern['duration'] ?? '') ?>">

            <label>Faculty</label>
            <select name="faculty" required>
                <?php foreach ($valid_faculties as $f): ?>
                    <option value="<?= $f ?>" <?= (($intern['faculty'] ?? '') === $f ? 'selected' : '') ?>><?= $f ?></option>
                <?php endforeach; ?>
            </select>

            <div style="display:flex; gap:10px; margin-top:12px;">
                <button class="btn btn-blue" type="submit">Save</button>
                <a class="btn btn-gray" style="text-decoration:none;" href="admin_internships.php">Cancel</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
