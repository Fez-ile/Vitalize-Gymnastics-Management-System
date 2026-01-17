<?php
include 'includes/header.php';
include_once 'includes/functions.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $coach = trim($_POST['coach'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $duration = trim($_POST['duration'] ?? '');
    $skill_level = $_POST['skill_level'] ?? '';

    // Validation
    if (!$name || !$description || !$coach || !$contact || !$duration || !$skill_level) {
        $error = 'All fields are required.';
    } elseif (!is_numeric($duration) || $duration < 1 || $duration > 52) {
        $error = 'Please enter a valid duration (1-52 weeks).';
    } elseif (!preg_match('/^[\w\s\-\.]+@[\w\-]+\.[A-Za-z]{2,6}$|^\+?[0-9\-\s]{7,15}$/', $contact)) {
        $error = 'Please enter a valid email or phone number for contact.';
    } else {
        $programData = [
            'name' => $name,
            'description' => $description,
            'coach' => $coach,
            'contact' => $contact,
            'duration' => (int) $duration,
            'skill_level' => $skill_level
        ];
        addProgram($programData);
        $message = 'Program added successfully!';
    }
}
?>
<h1>Add Gymnastics Program</h1>
<?php if ($message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
<?php elseif ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<form method="post">
    <label for="name">Program Name:</label>
    <input type="text" name="name" id="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
    <label for="description">Description:</label>
    <textarea name="description" id="description" rows="3"
        required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
    <label for="coach">Coach/Instructor Name:</label>
    <input type="text" name="coach" id="coach" value="<?= htmlspecialchars($_POST['coach'] ?? '') ?>" required>
    <label for="contact">Coach Contact (Email or Phone):</label>
    <input type="text" name="contact" id="contact" value="<?= htmlspecialchars($_POST['contact'] ?? '') ?>" required>
    <label for="duration">Duration (weeks):</label>
    <input type="number" name="duration" id="duration" min="1" max="52"
        value="<?= htmlspecialchars($_POST['duration'] ?? '') ?>" required>
    <label for="skill_level">Skill Level:</label>
    <select name="skill_level" id="skill_level" required>
        <option value="">-- Select --</option>
        <option value="Beginner" <?= (($_POST['skill_level'] ?? '') === 'Beginner') ? 'selected' : '' ?>>Beginner</option>
        <option value="Intermediate" <?= (($_POST['skill_level'] ?? '') === 'Intermediate') ? 'selected' : '' ?>>
            Intermediate
        </option>
        <option value="Advanced" <?= (($_POST['skill_level'] ?? '') === 'Advanced') ? 'selected' : '' ?>>Advanced</option>
    </select>
    <button type="submit">Add Program</button>
</form>
<?php include 'includes/footer.php'; ?>