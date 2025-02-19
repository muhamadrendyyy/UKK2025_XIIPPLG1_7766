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

    if (isset($_POST['update'])) {
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
        body {
            display: flex;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background: #343a40;
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            display: block;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover {
            background: #495057;
        }
        .content {
            margin-left: 260px;
            width: calc(100% - 260px);
            padding: 20px;
        }
        .doneText {
            text-decoration: line-through;
            color: red;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4 class="text-center">Menu</h4>
    <a href="#">Profil</a>
    <a href="#">Settings</a>
    <a href="logout.php" class="text-danger">Logout</a>
</div>

<!-- Main Content -->
<div class="content">
    <h1 class="text-center">To Do List</h1>
    
    <form method="POST" class="row justify-content-center mt-3">
        <div class="col-2"><input type="text" class="form-control" name="startTime" placeholder="Start time" required></div>
        <div class="col-2"><input type="text" class="form-control" name="endTime" placeholder="End time" required></div>
        <div class="col-6"><input type="text" class="form-control" name="activity" placeholder="New activity" required></div>
        <div class="col-2"><button class="btn btn-primary form-control" name="add">Add</button></div>
    </form>

    <div class="col-7 mt-5 mx-auto">
        <?php foreach ($_SESSION['todoList'] as $index => $item): ?>
            <div class="p-3 border-bottom">
                <div id="view-mode-<?php echo $index; ?>" style="display: block;">
                    <button class="btn btn-outline-danger me-2" onclick="deleteItem(<?php echo $index; ?>)">Delete</button>
                    <button class="btn btn-warning me-2" onclick="editItem(<?php echo $index; ?>)">Edit</button>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                        <button class="btn btn-success me-3" name="done">Done</button>
                    </form>
                    <span class="<?php echo $item['done'] ? 'doneText' : ''; ?>">
                        <?php echo htmlspecialchars($item['start']) . ' - ' . htmlspecialchars($item['end']) . ' : ' . htmlspecialchars($item['text']); ?>
                    </span>
                </div>

                <form method="POST" id="edit-mode-<?php echo $index; ?>" style="display: none;">
                    <input type="hidden" name="index" value="<?php echo $index; ?>">
                    <input type="text" class="form-control mb-2" name="startTime" value="<?php echo htmlspecialchars($item['start']); ?>" required>
                    <input type="text" class="form-control mb-2" name="endTime" value="<?php echo htmlspecialchars($item['end']); ?>" required>
                    <input type="text" class="form-control mb-2" name="activity" value="<?php echo htmlspecialchars($item['text']); ?>" required>
                    <button class="btn btn-success" name="update">Save</button>
                    <button type="button" class="btn btn-secondary" onclick="cancelEdit(<?php echo $index; ?>)">Cancel</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    function editItem(index) {
        document.getElementById('view-mode-' + index).style.display = 'none';
        document.getElementById('edit-mode-' + index).style.display = 'block';
    }

    function cancelEdit(index) {
        document.getElementById('view-mode-' + index).style.display = 'block';
        document.getElementById('edit-mode-' + index).style.display = 'none';
    }

    function deleteItem(index) {
        if (confirm("anda yakin mau menghapus ini?")) {
            let form = document.createElement('form');
            form.method = "POST";
            form.style.display = "none";

            let inputIndex = document.createElement('input');
            inputIndex.type = "hidden";
            inputIndex.name = "index";
            inputIndex.value = index;

            let deleteButton = document.createElement('input');
            deleteButton.type = "hidden";
            deleteButton.name = "delete";

            form.appendChild(inputIndex);
            form.appendChild(deleteButton);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

</body>
</html>
