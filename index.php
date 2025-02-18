<?php
session_start();

if (!isset($_SESSION['todoList'])) {
    $_SESSION['todoList'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $newItem = [
            'start' => $_POST['startTime'],
            'end' => $_POST['endTime'],
            'text' => $_POST['activity'],
            'done' => false
        ];
        $_SESSION['todoList'][] = $newItem;
    }

    if (isset($_POST['delete'])) {
        array_splice($_SESSION['todoList'], $_POST['index'], 1);
    }

    if (isset($_POST['edit'])) {
        $index = $_POST['index'];
        $_SESSION['todoList'][$index] = [
            'start' => $_POST['startTime'],
            'end' => $_POST['endTime'],
            'text' => $_POST['activity'],
            'done' => $_SESSION['todoList'][$index]['done']
        ];
    }

    if (isset($_POST['done'])) {
        $_SESSION['todoList'][$_POST['index']]['done'] = true;
    }

    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .doneText {
            text-decoration: line-through;
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-3 text-center">To Do List</h1>
        <form method="POST" class="row justify-content-center mt-3">
            <div class="col-2"><input type="text" class="form-control" name="startTime" placeholder="Start time" required></div>
            <div class="col-2"><input type="text" class="form-control" name="endTime" placeholder="End time" required></div>
            <div class="col-6"><input type="text" class="form-control" name="activity" placeholder="New activity" required></div>
            <div class="col-2"><button class="btn btn-primary form-control" name="add">Add</button></div>
        </form>
        <div class="col-7 mt-5 mx-auto">
            <?php foreach ($_SESSION['todoList'] as $index => $item): ?>
                <div class="p-3" style="border-bottom: 1px solid #ddd;">
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                        <button class="btn btn-outline-danger me-2" name="delete">Delete</button>
                    </form>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                        <input type="hidden" name="startTime" value="<?php echo htmlspecialchars($item['start']); ?>">
                        <input type="hidden" name="endTime" value="<?php echo htmlspecialchars($item['end']); ?>">
                        <input type="hidden" name="activity" value="<?php echo htmlspecialchars($item['text']); ?>">
                        <button class="btn btn-warning me-2" name="edit">Edit</button>
                    </form>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                        <button class="btn btn-success me-3" name="done">Done</button>
                    </form>
                    <span class="<?php echo $item['done'] ? 'doneText' : ''; ?>">
                        <?php echo htmlspecialchars($item['start']) . ' - ' . htmlspecialchars($item['end']) . ' : ' . htmlspecialchars($item['text']); ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
