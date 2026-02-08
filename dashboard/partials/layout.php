<?php
// Call session_start() in each page BEFORE including this file.

function require_role(array $roles) {
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $roles, true)) {
        header("Location: ../auth/login.php");
        exit();
    }
}

function sidebar_links_for_role(string $role): array {
    // key => [label, href]
    if ($role === 'student') {
        return [
            "student_dashboard"   => ["Dashboard", "student.php"],
            "student_logbook"     => ["Logbook", "student_logbook.php"],
            "student_attendance"  => ["Attendance", "student_attendance.php"],
            "student_feedback"    => ["Feedback", "student_feedback.php"],
        ];
    }

    if ($role === 'company') {
        return [
            "company_dashboard"   => ["Dashboard", "company.php"],
            "company_attendance"  => ["Attendance", "company_attendance.php"],
            "company_feedback"    => ["Daily Feedback", "company_feedback.php"],
            "company_log_feedback"=> ["Logbook Feedback", "company_logbook_feedback.php"],
            "company_complete"    => ["Completion & Evaluation", "company_complete.php"],
        ];
    }

    if ($role === 'academic') {
        return [
            "academic_dashboard"  => ["Dashboard", "academic.php"],
            "academic_attendance" => ["Student Attendance", "academic_attendance.php"],
            "academic_logbook"    => ["Student Logbooks", "academic_logbook.php"],
        ];
    }

    if ($role === 'admin') {
        return [
            "admin_users"         => ["User Management", "admin.php"],
        ];
    }

    return [];
}

function render_layout_start(string $title, string $activeKey) {
    $role = $_SESSION['role'] ?? '';
    $name = $_SESSION['name'] ?? 'User';
    $links = sidebar_links_for_role($role);

    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title><?= htmlspecialchars($title) ?></title>
        <link rel="stylesheet" href="../assets/app.css">
    </head>
    <body>
    <div class="layout">

        <aside class="sidebar">
            <div class="brand">Internship System</div>

            <div class="nav">
                <?php foreach ($links as $key => $item): 
                    [$label, $href] = $item;
                    $active = ($key === $activeKey) ? 'active' : '';
                ?>
                    <a class="<?= $active ?>" href="<?= htmlspecialchars($href) ?>">
                        <?= htmlspecialchars($label) ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <a class="logout" href="../auth/logout.php">Logout</a>
        </aside>

        <main class="main">
            <div class="topbar">
                <div>
                    <h1><?= htmlspecialchars($title) ?></h1>
                    <div class="meta">Role: <b><?= htmlspecialchars($role) ?></b></div>
                </div>
                <div class="meta">Logged in as: <b><?= htmlspecialchars($name) ?></b></div>
            </div>

            <?php
            $success = $_SESSION['success'] ?? '';
            $error = $_SESSION['error'] ?? '';
            unset($_SESSION['success'], $_SESSION['error']);

            if ($success) echo '<div class="msg ok">'.htmlspecialchars($success).'</div>';
            if ($error) echo '<div class="msg err">'.htmlspecialchars($error).'</div>';
            ?>

            <div class="container">
    <?php
}

function render_layout_end() {
    ?>
            </div>
        </main>
    </div>
    </body>
    </html>
    <?php
}
