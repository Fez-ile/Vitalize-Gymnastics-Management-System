<?php
include_once 'includes/functions.php';

// Handle search and filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$skill_level = isset($_GET['skill_level']) ? $_GET['skill_level'] : '';
$programs = getPrograms();

// Filter programs
if ($search !== '' || $skill_level !== '') {
    $programs = array_filter($programs, function ($program) use ($search, $skill_level) {
        $match = true;
        if ($search !== '') {
            $match = stripos($program['name'], $search) !== false || stripos($program['coach'], $search) !== false;
        }
        if ($match && $skill_level !== '') {
            $match = $program['skill_level'] === $skill_level;
        }
        return $match;
    });
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Vitalize Gymnastics Programs</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background: #f7f7f7;
        }

        h1 {
            color: #2d6a4f;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #95d5b2;
        }

        tr:nth-child(even) {
            background: #f1f1f1;
        }

        .actions a {
            margin-right: 8px;
            text-decoration: none;
            color: #40916c;
        }

        .actions a:hover {
            text-decoration: underline;
        }

        .top-bar {
            margin-bottom: 20px;
        }

        .top-bar form {
            display: inline-block;
            margin-left: 20px;
        }

        .add-btn {
            background: #40916c;
            color: #fff;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
        }

        .add-btn:hover {
            background: #2d6a4f;
        }
    </style>
</head>

<body>
    <div class="top-bar">
        <h1>Vitalize Gymnastics Programs</h1>
        <a class="add-btn" href="add_program.php">+ Add New Program</a>
        <form method="get" style="display:inline;">
            <input type="text" name="search" placeholder="Search by name or coach"
                value="<?= htmlspecialchars($search) ?>">
            <select name="skill_level">
                <option value="">All Levels</option>
                <option value="Beginner" <?= $skill_level === 'Beginner' ? 'selected' : '' ?>>Beginner</option>
                <option value="Intermediate" <?= $skill_level === 'Intermediate' ? 'selected' : '' ?>>Intermediate</option>
                <option value="Advanced" <?= $skill_level === 'Advanced' ? 'selected' : '' ?>>Advanced</option>
            </select>
            <button type="submit">Filter</button>
        </form>
    </div>
    <table>
        <tr>
            <th>Program Name</th>
            <th>Coach</th>
            <th>Duration (weeks)</th>
            <th>Skill Level</th>
            <th>Enrolled Gymnasts</th>
            <th>Actions</th>
        </tr>
        <?php if (empty($programs)): ?>
            <tr>
                <td colspan="6" style="text-align:center;">No programs found.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($programs as $program): ?>
                <tr>
                    <td><?= htmlspecialchars($program['name']) ?></td>
                    <td><?= htmlspecialchars($program['coach']) ?></td>
                    <td><?= (int) $program['duration'] ?></td>
                    <td><?= htmlspecialchars($program['skill_level']) ?></td>
                    <td><?= count(getEnrolledGymnasts($program['id'])) ?></td>
                    <td class="actions">
                        <a href="edit_program.php?id=<?= urlencode($program['id']) ?>">Edit</a>
                        <a href="delete_program.php?id=<?= urlencode($program['id']) ?>"
                            onclick="return confirm('Are you sure you want to delete this program?');">Delete</a>
                        <a href="enrol_gymnast.php?program_id=<?= urlencode($program['id']) ?>">Enrol</a>
                        <a href="attendance.php?program_id=<?= urlencode($program['id']) ?>">Attendance</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</body>

</html>