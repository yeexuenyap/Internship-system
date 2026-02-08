<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$company_id = (int)($_GET['company_id'] ?? 0);
$tab = $_GET['tab'] ?? 'evaluations';
if (!in_array($tab, ['evaluations','feedback'], true)) $tab = 'evaluations';

if ($company_id <= 0) {
    header("Location: admin_companies.php");
    exit();
}

// company info
$cstmt = $conn->prepare("SELECT id, name, email FROM users WHERE id=? AND role='company' LIMIT 1");
$cstmt->bind_param("i", $company_id);
$cstmt->execute();
$company = $cstmt->get_result()->fetch_assoc();
if (!$company) {
    header("Location: admin_companies.php");
    exit();
}

$success = $_SESSION['success'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Company Records</title>
    <style>
        body { background:#f6f7fb; font-family:Arial; margin:0; }
        .navbar { background:#111827; color:#fff; padding:12px 16px; display:flex; justify-content:space-between; align-items:center; }
        .navbar a { color:#fff; text-decoration:none; margin-left:12px; }
        .container { max-width:1200px; margin:16px auto; padding:0 16px; }

        .card { background:#fff; border-radius:12px; padding:14px; box-shadow:0 8px 20px rgba(17,24,39,0.06); margin-bottom:14px; }
        .tabs { display:flex; gap:10px; margin-top:10px; }
        .tablink { padding:10px 14px; border-radius:10px; text-decoration:none; border:1px solid #e5e7eb; background:#fff; color:#111827; font-weight:600; }
        .tablink.active { background:#2563eb; border-color:#2563eb; color:#fff; }

        table { width:100%; border-collapse:separate; border-spacing:0; border-radius:12px; overflow:hidden; background:#fff; }
        th, td { padding:10px; border-bottom:1px solid #f1f5f9; text-align:left; vertical-align:top; }
        th { background:#f8fafc; font-size:12px; color:#6b7280; text-transform:uppercase; letter-spacing:.04em; }
        tr:hover td { background:#fafafa; }

        .btn { border:none; border-radius:10px; color:#fff; cursor:pointer; font-size:13px; padding:8px 10px; }
        .btn-red { background:#ef4444; }

        .msg { padding:10px 12px; border-radius:12px; margin-bottom:12px; font-size:14px; }
        .ok { background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; }
        .err { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }

        .tag { display:inline-block; padding:4px 8px; border-radius:999px; font-size:12px; background:#f3f4f6; }
    </style>
</head>
<body>

<div class="navbar">
    <div><b>Admin</b> — Company Records</div>
    <div>
        <a href="admin_companies.php">Back</a>
        <a href="../auth/logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <?php if ($success): ?><div class="msg ok"><?= htmlspecialchars($success) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="msg err"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="card">
        <h2 style="margin:0;"><?= htmlspecialchars($company['name']) ?></h2>
        <div style="color:#6b7280;margin-top:6px;"><?= htmlspecialchars($company['email']) ?> • ID: <?= (int)$company['id'] ?></div>

        <div class="tabs">
            <a class="tablink <?= $tab==='evaluations'?'active':'' ?>"
               href="admin_company_records.php?company_id=<?= (int)$company_id ?>&tab=evaluations">
                Evaluations
            </a>
            <a class="tablink <?= $tab==='feedback'?'active':'' ?>"
               href="admin_company_records.php?company_id=<?= (int)$company_id ?>&tab=feedback">
                Daily Feedback
            </a>
        </div>
    </div>

    <?php if ($tab === 'evaluations'): ?>

        <div class="card">
            <h3 style="margin:0 0 10px;">Company Evaluations</h3>

            <?php
            $stmt = $conn->prepare(
                "SELECT
                    si.student_id,
                    u.name AS student_name,
                    u.email AS student_email,
                    i.id AS internship_id,
                    i.title AS internship_title,
                    si.status,
                    si.evaluation_score,
                    si.evaluation_comment,
                    si.accepted_at
                 FROM student_internships si
                 JOIN internships i ON si.internship_id = i.id
                 JOIN users u ON si.student_id = u.id
                 WHERE i.company_id = ?
                   AND (si.evaluation_score IS NOT NULL OR si.evaluation_comment IS NOT NULL)
                 ORDER BY si.accepted_at DESC"
            );
            $stmt->bind_param("i", $company_id);
            $stmt->execute();
            $rows = $stmt->get_result();
            ?>

            <table>
                <tr>
                    <th>Internship</th>
                    <th>Student</th>
                    <th>Status</th>
                    <th>Score</th>
                    <th>Comment</th>
                    <th>Action</th>
                </tr>

                <?php if ($rows->num_rows === 0): ?>
                    <tr><td colspan="6"><i>No evaluations found.</i></td></tr>
                <?php endif; ?>

                <?php while ($r = $rows->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <b><?= htmlspecialchars($r['internship_title']) ?></b><br>
                            <small>ID: <?= (int)$r['internship_id'] ?></small>
                        </td>
                        <td>
                            <b><?= htmlspecialchars($r['student_name']) ?></b><br>
                            <small><?= htmlspecialchars($r['student_email']) ?></small><br>
                            <small>ID: <?= (int)$r['student_id'] ?></small>
                        </td>
                        <td><span class="tag"><?= htmlspecialchars($r['status']) ?></span></td>
                        <td><?= htmlspecialchars($r['evaluation_score'] ?? '-') ?></td>
                        <td><?= nl2br(htmlspecialchars($r['evaluation_comment'] ?? '')) ?></td>
                        <td>
                            <form method="POST" action="admin_delete_evaluation.php" style="display:inline;">
                                <input type="hidden" name="company_id" value="<?= (int)$company_id ?>">
                                <input type="hidden" name="internship_id" value="<?= (int)$r['internship_id'] ?>">
                                <input type="hidden" name="student_id" value="<?= (int)$r['student_id'] ?>">
                                <button class="btn btn-red" onclick="return confirm('Delete this evaluation?');">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

    <?php else: ?>

        <div class="card">
            <h3 style="margin:0 0 10px;">Daily Feedback</h3>

            <?php
            $stmt = $conn->prepare(
                "SELECT
                    f.id,
                    f.student_id,
                    u.name AS student_name,
                    u.email AS student_email,
                    f.internship_id,
                    i.title AS internship_title,
                    f.feedback_date,
                    f.content,
                    f.created_at
                 FROM feedback f
                 JOIN internships i ON f.internship_id = i.id
                 JOIN users u ON f.student_id = u.id
                 WHERE i.company_id = ?
                 ORDER BY f.feedback_date DESC, f.id DESC"
            );
            $stmt->bind_param("i", $company_id);
            $stmt->execute();
            $rows = $stmt->get_result();
            ?>

            <table>
                <tr>
                    <th>ID</th>
                    <th>Internship</th>
                    <th>Student</th>
                    <th>Feedback</th>
                    <th>Date</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>

                <?php if ($rows->num_rows === 0): ?>
                    <tr><td colspan="7"><i>No feedback found.</i></td></tr>
                <?php endif; ?>

                <?php while ($r = $rows->fetch_assoc()): ?>
                    <tr>
                        <td><?= (int)$r['id'] ?></td>
                        <td>
                            <b><?= htmlspecialchars($r['internship_title']) ?></b><br>
                            <small>ID: <?= (int)$r['internship_id'] ?></small>
                        </td>
                        <td>
                            <b><?= htmlspecialchars($r['student_name']) ?></b><br>
                            <small><?= htmlspecialchars($r['student_email']) ?></small>
                        </td>
                        <td><?= nl2br(htmlspecialchars($r['content'] ?? '')) ?></td>
                        <td><?= htmlspecialchars($r['feedback_date'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($r['created_at'] ?? '-') ?></td>
                        <td>
                            <form method="POST" action="admin_delete_feedback.php" style="display:inline;">
                                <input type="hidden" name="company_id" value="<?= (int)$company_id ?>">
                                <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                <button class="btn btn-red" onclick="return confirm('Delete this feedback?');">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

    <?php endif; ?>

</div>
</body>
</html>
