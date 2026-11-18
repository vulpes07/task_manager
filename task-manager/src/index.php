<?php
require_once 'db.php';

function h($text)
{
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

$fehler = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['add'])) {
        $title       = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($title === '') {
            $fehler = 'Bitte geben Sie einen Titel ein.';
        } else {
            $stmt = $pdo->prepare("INSERT INTO tasks (title, description) VALUES (?, ?)");
            $stmt->execute([$title, $description]);
            header('Location: index.php');
            exit;
        }
    }

    if (isset($_POST['toggle'])) {
        $id = (int) ($_POST['id'] ?? 0);
        $stmt = $pdo->prepare(
            "UPDATE tasks
             SET status = IF(status = 'offen', 'erledigt', 'offen')
             WHERE id = ?"
        );
        $stmt->execute([$id]);
        header('Location: index.php');
        exit;
    }


    if (isset($_POST['delete'])) {
        $id = (int) ($_POST['id'] ?? 0);
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: index.php');
        exit;
    }
}

$stmt  = $pdo->query("SELECT * FROM tasks ORDER BY created_at");
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="box">
        <h1>Meine Aufgaben</h1>

        <?php if ($fehler !== ''): ?>
            <p class="fehler"><?= h($fehler) ?></p>
        <?php endif; ?>

        <form method="post" action="index.php" class="formular">
            <label for="title">Neue Aufgabe</label>
            <input type="text" id="title" name="title" maxlength="255">

            <label for="description">Beschreibung</label>
            <textarea id="description" name="description" rows="3"></textarea>

            <button type="submit" name="add">Aufgabe hinzufügen</button>
        </form>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Aufgabe</th>
            <th>Beschreibung</th>
            <th>Status</th>
            <th>Erstellt am</th>
            <th>Aktionen</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tasks as $task): ?>
        <tr>
            <td><?= h($task['id']) ?></td>
            <td><?= h($task['title']) ?></td>
            <td><?= h($task['description']) ?></td>
            <td class="status-<?= h($task['status']) ?>"><?= h($task['status']) ?></td>
            <td><?= h($task['created_at']) ?></td>
            <td class="aktionen">
                <form method="post">
                    <input type="hidden" name="id" value="<?= h($task['id']) ?>">
                    <button type="submit" name="toggle" class="btn">
                        <?= $task['status'] === 'offen' ? 'Erledigt' : 'Wieder öffnen' ?>
                    </button>
                </form>

                <a href="edit.php?id=<?= h($task['id']) ?>" class="btn">Bearbeiten</a>

                <form method="post" onsubmit="return confirm('Aufgabe wirklich löschen?');">
                    <input type="hidden" name="id" value="<?= h($task['id']) ?>">
                    <button type="submit" name="delete" class="btn btn-loeschen">Löschen</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
    </div>
</body>
</html>