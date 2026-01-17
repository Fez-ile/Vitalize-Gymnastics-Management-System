<?php
include 'includes/header.php';
include_once 'includes/functions.php';

$programs = getPrograms();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $program_id = $_POST['program_id'] ?? '';
    $name = trim($_POST['name'] ?? '');
    $age = trim($_POST['age'] ?? '');
    $experience = trim($_POST['experience'] ?? '');

    // Validation
    if (!$program_id || !$name || !$age || !$experience) {
        $error = 'All fields are required.';
    } elseif (!is_numeric($age) || $age < 3 || $age > 100) {
        $error = 'Please enter a valid age (3-100).';
    } else {
        $enrolData = [
            'program_id' => $program_id,
            'name' => $name,
            'age' => (int) $age,
            'experience' => $experience
        ];
        enrolGymnast($enrolData);
        $message = 'Enrolment successful! The coach will be notified.';
    }
}
?>
<h1>Enrol Gymnast</h1>
<?php if ($message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
<?php elseif ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<form method="post">
    <label for="program_id">Select Program:</label>
    <select name="program_id" id="program_id" required>
        <option value="">-- Select --</option>
        <?php foreach ($programs as $program): ?>
            <option value="<?= htmlspecialchars($program['id']) ?>" <?= (isset($_POST['program_id']) && $_POST['program_id'] === $program['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($program['name']) ?> (<?= htmlspecialchars($program['skill_level']) ?>)
            </option>
        <?php endforeach; ?>
    </select>
    <label for="name">Gymnast Name:</label>
    <input type="text" name="name" id="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
    <label for="age">Age:</label>
    <input type="number" name="age" id="age" min="3" max="100" value="<?= htmlspecialchars($_POST['age'] ?? '') ?>"
        required>
    <label for="experience">Experience Level:</label>
    <select name="experience" id="experience" required>
        <option value="">-- Select --</option>
        <option value="Beginner" <?= (($_POST['experience'] ?? '') === 'Beginner') ? 'selected' : '' ?>>Beginner</option>
        <option value="Intermediate" <?= (($_POST['experience'] ?? '') === 'Intermediate') ? 'selected' : '' ?>>
            Intermediate
        </option>
        <option value="Advanced" <?= (($_POST['experience'] ?? '') === 'Advanced') ? 'selected' : '' ?>>Advanced</option>
    </select>
    <button type="submit">Enrol</button>
</form>
<?php include 'includes/footer.php'; ?>