<?php
include 'includes/header.php';
include_once 'includes/functions.php';

$program_id = $_GET['id'] ?? '';
$programs = getPrograms();
$selected_program = null;
foreach ($programs as $p) {
    if ($p['id'] === $program_id) {
        $selected_program = $p;
        break;
    }
}
$message = '';
$error = '';

if (!$selected_program) {
    $error = 'Program not found.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        deleteProgram($program_id);
        $message = 'Program deleted successfully.';
    } else {
        header('Location: index.php');
        exit;
    }
}
?>
<h1>Delete Program</h1>
<?php if ($message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <a href="index.php">&larr; Back to Program List</a>
<?php elseif ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <a href="index.php">&larr; Back to Program List</a>
<?php else: ?>
    <div class="alert alert-error">
        Are you sure you want to delete the program <strong><?= htmlspecialchars($selected_program['name']) ?></strong>?
    </div>
    <form method="post">
        <button type="submit" name="confirm" value="yes">Yes, Delete</button>
        <button type="submit" name="confirm" value="no">Cancel</button>
    </form>
<?php endif; ?>
<?php include 'includes/footer.php'; ?>