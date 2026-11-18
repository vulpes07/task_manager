<?php
require_once 'db.php';

function h($text)
{
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

$fehler = '';
$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($title === '') {
        $fehler = 'Bitte geben Sie einen Titel ein.';
    } else {
        $stmt = $pdo->prepare(
            "UPDATE tasks SET title = ?, description = ? WHERE id = ?"
        );
        $stmt->execute([$title, $description, $id]);
        header('Location: index.php');
        exit;
    }
}


$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
$stmt->execute([$id]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$task) {
    http_response_code(404);
    die('Aufgabe nicht gefunden.');
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aufgabe bearbeiten</title>
    <link rel="stylesheet" href="style.css?v=4">
</head>
<body>
    <div class="box">
        <h1>Aufgabe bearbeiten</h1>

        <?php if ($fehler !== ''): ?>
            <p class="fehler"><?= h($fehler) ?></p>
        <?php endif; ?>

        <form method="post" class="formular">
            <input type="hidden" name="id" value="<?= h($task['id']) ?>">

            <label for="title">Titel</label>
            <input type="text" id="title" name="title" maxlength="255"
                   value="<?= h($_POST['title'] ?? $task['title']) ?>">

            <label for="description">Beschreibung</label>
            <textarea id="description" name="description" rows="3"><?= h($_POST['description'] ?? $task['description']) ?></textarea>

            <button type="submit" name="save">Speichern</button>
            <a href="index.php">Abbrechen</a>
        </form>
    </div>
</body>
</html>