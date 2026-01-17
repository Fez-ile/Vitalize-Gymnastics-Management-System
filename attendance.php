<?php
include 'includes/header.php';
include_once 'includes/functions.php';

$program_id = $_GET['program_id'] ?? '';
$programs = getPrograms();
$selected_program = null;
foreach ($programs as $p) {
    if ($p['id'] === $program_id) {
        $selected_program = $p;
        break;
    }
}
$gymnasts = $program_id ? getEnrolledGymnasts($program_id) : [];
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $program_id) {
    foreach ($gymnasts as $gymnast) {
        $gid = $gymnast['id'] ?? '';
        $present = isset($_POST['present'][$gid]) ? 1 : 0;
        $note = trim($_POST['progress_note'][$gid] ?? '');
        $date = date('Y-m-d');
        markAttendance($program_id, $gid, $date, $present, $note);
    }
    $message = 'Attendance and progress updated for this session.';
}

// For progress bar: assume duration = total sessions
$total_sessions = $selected_program['duration'] ?? 1;
?>
<h1>Attendance & Progress<?= $selected_program ? ' - ' . htmlspecialchars($selected_program['name']) : '' ?></h1>
<?php if ($message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
<?php elseif ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if (!$selected_program): ?>
    <div class="alert alert-error">Invalid or missing program selected.</div>
<?php else: ?>
    <?php if (empty($gymnasts)): ?>
        <div class="alert">No gymnasts enrolled in this program.</div>
    <?php else: ?>
        <form method="post">
            <table>
                <tr>
                    <th>Gymnast Name</th>
                    <th>Age</th>
                    <th>Experience</th>
                    <th>Present</th>
                    <th>Progress Note</th>
                    <th>Progress</th>
                </tr>
                <?php foreach ($gymnasts as $gymnast):
                    $gid = $gymnast['id'] ?? '';
                    $progress = calculateProgress($program_id, $gid, $total_sessions);
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($gymnast['name']) ?></td>
                        <td><?= htmlspecialchars($gymnast['age']) ?></td>
                        <td><?= htmlspecialchars($gymnast['experience']) ?></td>
                        <td style="text-align:center;">
                            <input type="checkbox" name="present[<?= $gid ?>]" value="1">
                        </td>
                        <td>
                            <input type="text" name="progress_note[<?= $gid ?>]" style="width:95%;">
                        </td>
                        <td>
                            <div class="progress-bar">
                                <div class="progress-bar-inner" style="width: <?= $progress ?>%;">
                                    <?= $progress ?>%
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <button type="submit">Save Attendance & Progress</button>
        </form>
    <?php endif; ?>
<?php endif; ?>
<?php include 'includes/footer.php'; ?>