<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

// Tabs
$tab = $_GET['tab'] ?? 'available';
if (!in_array($tab, ['available', 'current'], true)) $tab = 'available';

// Faculty filter (only used in available tab)
$filter_faculty = $_GET['faculty'] ?? '';
$valid_faculties = ['FCI','FCM','FOM','FIST'];
if ($filter_faculty !== '' && !in_array($filter_faculty, $valid_faculties, true)) {
    $filter_faculty = '';
}

// Flash messages (optional)
$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../assets/dashboard.css">
    <style>
        /* Simple tabs */
        .tabs { display:flex; gap:10px; margin: 10px 0 18px; }
        .tablink {
            padding:10px 14px;
            border-radius:10px;
            text-decoration:none;
            border:1px solid #ddd;
            background:#fff;
            color:#333;
            font-weight:600;
        }
        .tablink.active {
            background:#4e73df;
            border-color:#4e73df;
            color:#fff;
        }
        .msg-ok { background:#e7f9ef; color:#065f46; padding:10px; border-radius:8px; margin-bottom:10px; }
        .msg-err { background:#ffe7e7; color:#991b1b; padding:10px; border-radius:8px; margin-bottom:10px; }

        .filter-bar { display:flex; gap:10px; align-items:end; flex-wrap:wrap; margin-bottom:15px; }
        .filter-bar label { display:block; font-size:12px; margin-bottom:6px; }
        .filter-bar select { padding:10px; border-radius:8px; border:1px solid #ddd; min-width:200px; }
        .filter-bar .button { height:40px; }

        .badge { display:inline-block; padding:4px 10px; border-radius:999px; font-size:12px; background:#f3f4f6; }
        .badge.green { background:#e7f9ef; color:#065f46; }
        .badge.yellow { background:#fff4d6; color:#8a5a00; }
        .badge.red { background:#ffe7e7; color:#991b1b; }
        .badge.gray { background:#f1f5f9; color:#334155; }

        .actions { display:flex; gap:8px; flex-wrap:wrap; margin-top:10px;chk|Bvdrn; }
        .btn-red { background:#e74a3b; }
        .btn-green { background:#1cc88a; }
        .btn-blue { background:#4e73df; }
        .btn-gray { background:#858796; }
        .button { border:none; color:#fff; padding:10px 12px; border-radius:8px; cursor:pointer; }
        .button:disabled { opacity:0.6; cursor:not-allowed; }
        .card { background:#fff; padding:15px; border-radius:10px; margin:12px 0; }
        input[type="date"] { padding:10px; border-radius:8px; border:1px solid #ddd; }
    </style>
</head>
<body>

<div class="navbar">
        <div>Student Dashboard</div>
        <a href="student.php">Dashboard</a>
        <a href="student_logbook.php">Logbook</a>
        <a href="student_feedback.php">Daily Feedback</a>
        <a href="profile.php">Profile</a>
        <a href="student_attendance.php">Attendance</a>
        <a href="student_academic_apply.php">Academic Supervisor</a>
        <a href="notifications.php">Notifications</a>
        <a href="../auth/logout.php">Logout</a>
    </div>

<div class="container">

    <?php if ($success): ?><div class="msg-ok"><?= htmlspecialchars($success) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="msg-err"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="tabs">
        <a class="tablink <?= $tab === 'available' ? 'active' : '' ?>" href="student.php?tab=available<?= $filter_faculty ? '&faculty='.urlencode($filter_faculty) : '' ?>">
            Available Internships
        </a>
        <a class="tablink <?= $tab === 'current' ? 'active' : '' ?>" href="student.php?tab=current">
            Current Internship
        </a>
    </div>

    <?php if ($tab === 'available'): ?>

        <div class="card">
            <h2>Available Internships</h2>

            <!-- Faculty Search / Filter -->
            <form method="GET" class="filter-bar">
                <input type="hidden" name="tab" value="available">
                <div>
                    <label>Search by Faculty</label>
                    <select name="faculty">
                        <option value="">All Faculties</option>
                        <?php foreach ($valid_faculties as $f): ?>
                            <option value="<?= $f ?>" <?= ($filter_faculty === $f ? 'selected' : '') ?>><?= $f ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <button class="button btn-blue" type="submit">Search</button>
                </div>
                <?php if ($filter_faculty): ?>
                    <div>
                        <a class="button btn-gray" style="text-decoration:none; display:inline-block;" href="student.php?tab=available">Clear</a>
                    </div>
                <?php endif; ?>
            </form>

            <?php
            // Internship list (with student's status)
            $sql = "SELECT internships.*,
                           users.name AS company_name,
                           si.status
                    FROM internships
                    JOIN users ON internships.company_id = users.id
                    LEFT JOIN student_internships si
                      ON internships.id = si.internship_id
                      AND si.student_id = ?";

            if ($filter_faculty !== '') {
                $sql .= " WHERE internships.faculty = ?";
            }

            $stmt = $conn->prepare($sql);

            if ($filter_faculty !== '') {
                $stmt->bind_param("is", $student_id, $filter_faculty);
            } else {
                $stmt->bind_param("i", $student_id);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                echo "<p><i>No internship applications found.</i></p>";
            }

            while ($row = $result->fetch_assoc()):
                $status = $row['status']; // can be null
            ?>
                <div class="card">
                    <h3><?= htmlspecialchars($row['title']) ?></h3>
                    <p><b>Company:</b> <?= htmlspecialchars($row['company_name']) ?></p>
                    <p><?= htmlspecialchars($row['description']) ?></p>
                    <p><b>Faculty:</b> <?= htmlspecialchars($row['faculty']) ?></p>
                    <?php if (!empty($row['location']) || !empty($row['duration'])): ?>
                        <p><?= htmlspecialchars($row['location'] ?? '') ?> <?= (!empty($row['location']) && !empty($row['duration'])) ? ' | ' : '' ?><?= htmlspecialchars($row['duration'] ?? '') ?></p>
                    <?php endif; ?>

                    <div class="actions">
                        <?php if ($status === null): ?>
                            <form method="POST" action="apply_internship.php">
                                <input type="hidden" name="internship_id" value="<?= (int)$row['id'] ?>">
                                <button class="button btn-green">Apply</button>
                            </form>

                        <?php elseif ($status === 'applied'): ?>
                            <span class="badge yellow">Pending Approval</span>
                            <button class="button btn-gray" disabled>Applied</button>

                        <?php elseif ($status === 'in_progress'): ?>
                            <span class="badge green">In Progress</span>
                            <button class="button btn-gray" disabled>In Progress</button>

                            <form method="POST" action="drop_internship.php">
                                <input type="hidden" name="internship_id" value="<?= (int)$row['id'] ?>">
                                <button class="button btn-red" onclick="return confirm('Drop this internship?');">
                                    Drop Internship
                                </button>
                            </form>

                        <?php elseif ($status === 'rejected'): ?>
                            <span class="badge red">Rejected</span>
                            <button class="button btn-gray" disabled>Rejected</button>

                        <?php elseif ($status === 'completed'): ?>
                            <span class="badge gray">Completed</span>
                            <button class="button btn-gray" disabled>Completed</button>

                        <?php elseif ($status === 'dropped'): ?>
                            <span class="badge red">Dropped</span>
                            <button class="button btn-gray" disabled>Dropped</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>

        </div>

    <?php else: ?>

        <div class="card">
            <h2>Current Internship</h2>

            <?php
            // Current internship for student (in_progress or completed)
            $cur = $conn->prepare(
                "SELECT i.id AS internship_id, i.title, i.description, i.location, i.duration, i.faculty,
                        c.name AS company_name,
                        si.status
                 FROM student_internships si
                 JOIN internships i ON si.internship_id = i.id
                 JOIN users c ON i.company_id = c.id
                 WHERE si.student_id = ?
                   AND si.status IN ('in_progress','completed')
                 ORDER BY si.internship_id DESC
                 LIMIT 1"
            );
            $cur->bind_param("i", $student_id);
            $cur->execute();
            $current = $cur->get_result()->fetch_assoc();

            if (!$current):
            ?>
                <p><i>You don’t have an internship in progress or completed yet.</i></p>
            <?php else: ?>
                <div class="card" style="margin-top:10px;">
                    <h3><?= htmlspecialchars($current['title']) ?></h3>
                    <p><b>Company:</b> <?= htmlspecialchars($current['company_name']) ?></p>
                    <p><?= htmlspecialchars($current['description']) ?></p>
                    <p><b>Faculty:</b> <?= htmlspecialchars($current['faculty']) ?></p>
                    <?php if (!empty($current['location']) || !empty($current['duration'])): ?>
                        <p><?= htmlspecialchars($current['location'] ?? '') ?> <?= (!empty($current['location']) && !empty($current['duration'])) ? ' | ' : '' ?><?= htmlspecialchars($current['duration'] ?? '') ?></p>
                    <?php endif; ?>

                    <?php if ($current['status'] === 'in_progress'): ?>
                        <p><span class="badge green">IN PROGRESS</span></p>

                        <div class="actions">
                            <a class="button btn-blue" style="text-decoration:none;" href="student_attendance.php">
                                View Attendance
                            </a>
                            <a class="button btn-blue" style="text-decoration:none;" href="student_logbook.php">
                                Go to Logbook
                            </a>
                            <a class="button btn-blue" style="text-decoration:none;" href="student_feedback.php">
                                View Feedback
                            </a>

                            <form method="POST" action="drop_internship.php" style="display:inline;">
                                <input type="hidden" name="internship_id" value="<?= (int)$current['internship_id'] ?>">
                                <button class="button btn-red" onclick="return confirm('Drop this internship?');">
                                    Drop Internship
                                </button>
                            </form>
                        </div>

                    <?php else: ?>
                        <p><span class="badge gray">COMPLETED</span></p>
                        <div class="actions">
                            <a class="button btn-blue" style="text-decoration:none;" href="student_attendance.php">
                                View Attendance
                            </a>
                            <a class="button btn-blue" style="text-decoration:none;" href="student_logbook.php">
                                View Logbook
                            </a>
                            <a class="button btn-blue" style="text-decoration:none;" href="student_feedback.php">
                                View Feedback
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

    <?php endif; ?>

</div>
</body>
</html>
