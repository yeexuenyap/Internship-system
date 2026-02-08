<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

$q = trim($_GET['q'] ?? '');
$roleFilter = $_GET['role'] ?? '';

$sql = "SELECT id, name, email, role, faculty FROM users WHERE 1=1";
$params = [];
$types = "";

if ($q !== '') {
    $sql .= " AND (name LIKE ? OR email LIKE ?)";
    $like = "%$q%";
    $params[] = $like;
    $params[] = $like;
    $types .= "ss";
}

if ($roleFilter !== '') {
    $sql .= " AND role = ?";
    $params[] = $roleFilter;
    $types .= "s";
}

$sql .= " ORDER BY role ASC, name ASC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$users = $stmt->get_result();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin - User Management</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f6f7fb;
            color: #1f2937;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 240px;
            background: #111827;
            color: #fff;
            padding: 18px 14px;
        }

        .brand {
            font-size: 18px;
            font-weight: 700;
            padding: 10px 10px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            margin-bottom: 14px;
        }

        .nav a {
            display: flex;
            gap: 10px;
            align-items: center;
            padding: 10px 12px;
            margin: 6px 0;
            color: #e5e7eb;
            text-decoration: none;
            border-radius: 10px;
            font-size: 14px;
        }

        .nav a:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        .nav a.active {
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
        }

        .logout {
            margin-top: 18px;
            display: block;
            padding: 10px 12px;
            border-radius: 10px;
            text-decoration: none;
            color: #fff;
            background: #ef4444;
            text-align: center;
        }

        /* Main */
        .main {
            flex: 1;
            padding: 18px 18px 30px;
        }

        .topbar {
            background: #fff;
            border-radius: 14px;
            padding: 14px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 8px 20px rgba(17, 24, 39, 0.06);
            margin-bottom: 16px;
        }

        .topbar h1 {
            margin: 0;
            font-size: 18px;
        }

        .topbar .meta {
            font-size: 13px;
            color: #6b7280;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        @media (min-width: 1100px) {
            .grid {
                grid-template-columns: 420px 1fr;
                align-items: start;
            }
        }

        .card {
            background: #fff;
            border-radius: 14px;
            padding: 16px;
            box-shadow: 0 8px 20px rgba(17, 24, 39, 0.06);
        }

        .card h2 {
            margin: 0 0 10px;
            font-size: 16px;
        }

        .msg {
            padding: 10px 12px;
            border-radius: 12px;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .msg.ok {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .msg.err {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .row>.field {
            flex: 1;
            min-width: 180px;
        }

        label {
            display: block;
            font-size: 12px;
            color: #6b7280;
            margin: 8px 0 6px;
        }

        input,
        select {
            width: 100%;
            padding: 10px 11px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            outline: none;
            font-size: 14px;
            background: #fff;
        }

        input:focus,
        select:focus {
            border-color: #93c5fd;
            box-shadow: 0 0 0 3px rgba(147, 197, 253, 0.35);
        }

        .btn {
            border: none;
            border-radius: 10px;
            padding: 10px 12px;
            cursor: pointer;
            font-size: 14px;
            color: #fff;
        }

        .btn.blue {
            background: #2563eb;
        }

        .btn.green {
            background: #16a34a;
        }

        .btn.gray {
            background: #6b7280;
        }

        .btn.red {
            background: #ef4444;
        }

        .btn:disabled {
            opacity: 0.55;
            cursor: not-allowed;
        }

        .toolbar {
            display: flex;
            gap: 10px;
            align-items: end;
            flex-wrap: wrap;
        }

        .toolbar .field {
            min-width: 200px;
            flex: 1;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            overflow: hidden;
            border-radius: 12px;
        }

        th,
        td {
            padding: 10px 10px;
            border-bottom: 1px solid #f1f5f9;
            text-align: left;
            font-size: 14px;
        }

        th {
            background: #f8fafc;
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        tr:hover td {
            background: #fafafa;
        }

        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .tag {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 999px;
            font-size: 12px;
            background: #f3f4f6;
        }
    </style>
</head>

<body>

    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="brand">Internship Admin</div>

            <div class="nav">
                <a class="admin-action-btn" href="admin.php">User Management</a>
                <a href="admin_internships.php" class="admin-action-btn">📄 Internship Management</a>
                <a href="admin_companies.php" class="admin-action-btn">🏢 Company Supervisors</a>
                <a href="admin_students.php" class="admin-action-btn">🎓 Student Attendance</a>
                <a href="admin_notifications.php" class="admin-action-btn">Notifications</a>
                <a href="admin_analysis.php" class="admin-action-btn">📊 System Analysis</a>

            </div>
            <a class="logout" href="../auth/logout.php">Logout</a>
        </aside>

        <!-- Main -->
        <main class="main">
            <div class="topbar">
                <div>
                    <h1>User Management</h1>
                    <div class="meta">Create, update, reset passwords, and delete users</div>
                </div>
                <div class="meta">Logged in as: <b><?= htmlspecialchars($_SESSION['name'] ?? 'Admin') ?></b></div>
            </div>

            <?php if ($success): ?>
                <div class="msg ok"><?= htmlspecialchars($success) ?></div><?php endif; ?>
            <?php if ($error): ?>
                <div class="msg err"><?= htmlspecialchars($error) ?></div><?php endif; ?>

            <div class="grid">

                <!-- Left: Create + Search -->
                <section>
                    <div class="card">
                        <h2>Search & Filter</h2>
                        <form method="GET" class="toolbar">
                            <div class="field">
                                <label>Search (name/email)</label>
                                <input type="text" name="q" value="<?= htmlspecialchars($q) ?>"
                                    placeholder="e.g. ali, company@gmail.com">
                            </div>

                            <div class="field">
                                <label>Role</label>
                                <select name="role">
                                    <option value="">All roles</option>
                                    <?php foreach (['student', 'company', 'academic', 'admin'] as $r): ?>
                                        <option value="<?= $r ?>" <?= ($roleFilter === $r ? 'selected' : '') ?>>
                                            <?= ucfirst($r) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="field" style="flex:0; min-width:140px;">
                                <button class="btn blue" type="submit" style="width:100%;">Apply</button>
                            </div>
                        </form>
                    </div>

                    <div class="card" style="margin-top:16px;">
                        <h2>Create User</h2>
                        <form method="POST" action="admin_user_create.php">
                            <label>Name</label>
                            <input type="text" name="name" required>

                            <label>Email</label>
                            <input type="email" name="email" required>

                            <div class="row">
                                <div class="field">
                                    <label>Role</label>
                                    <select name="role" required>
                                        <option value="student">Student</option>
                                        <option value="company">Company</option>
                                        <option value="academic">Academic</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <div class="field">
                                    <label>Faculty (optional)</label>
                                    <select name="faculty">
                                        <option value="">None</option>
                                        <option value="FCI">FCI</option>
                                        <option value="FCM">FCM</option>
                                        <option value="FOM">FOM</option>
                                        <option value="FIST">FIST</option>
                                    </select>
                                </div>
                            </div>

                            <label>Temporary Password</label>
                            <input type="password" name="password" required placeholder="Give a temp password">

                            <button class="btn green" type="submit" style="width:100%; margin-top:10px;">
                                Create User
                            </button>
                        </form>
                    </div>
                </section>

                <!-- Right: Table -->
                <section class="card">
                    <h2>Users</h2>

                    <div style="margin-bottom:10px; color:#6b7280; font-size:13px;">
                        Tip: Use <span class="tag">Reset PW</span> to set a temporary password.
                    </div>

                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Faculty</th>
                            <th>Actions</th>
                        </tr>

                        <?php while ($u = $users->fetch_assoc()): ?>
                            <tr>
                                <td><?= (int) $u['id'] ?></td>
                                <td><?= htmlspecialchars($u['name']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td><span class="tag"><?= htmlspecialchars($u['role']) ?></span></td>
                                <td><?= htmlspecialchars($u['faculty'] ?? '') ?></td>
                                <td>
                                    <div class="actions">
                                        <a class="btn blue" style="text-decoration:none;"
                                            href="admin_user_edit.php?id=<?= (int) $u['id'] ?>">Edit</a>

                                        <form method="POST" action="admin_user_reset_password.php" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= (int) $u['id'] ?>">
                                            <button class="btn gray"
                                                onclick="return confirm('Reset password for this user?')">Reset PW</button>
                                        </form>

                                        <?php if ((int) $u['id'] !== (int) $_SESSION['user_id']): ?>
                                            <form method="POST" action="admin_user_delete.php" style="display:inline;">
                                                <input type="hidden" name="id" value="<?= (int) $u['id'] ?>">
                                                <button class="btn red"
                                                    onclick="return confirm('Delete this user?')">Delete</button>
                                            </form>
                                        <?php else: ?>
                                            <button class="btn red" disabled title="You cannot delete yourself">Delete</button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                </section>

            </div>
        </main>
    </div>

</body>

</html>