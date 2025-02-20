<?php
session_start();
include 'connect.php';

$query = "SELECT * FROM tugas ORDER BY id DESC";
$result = $conn->query($query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $start = $_POST['startTime'];
        $end = $_POST['endTime'];
        $task = $_POST['activity'];
        $status = 'not complete';

        $stmt = $conn->prepare("INSERT INTO tugas (user_id, category_id, task, start_time, end_time, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissss", $_SESSION['user_id'], $_POST['category_id'], $task, $start, $end, $status);
        $stmt->execute();
        $stmt->close();
        
        header("Location: index.php");
        exit();
    }

    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM tugas WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        
        header("Location: index.php");
        exit();
    }

    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $start = $_POST['startTime'];
        $end = $_POST['endTime'];
        $task = $_POST['activity'];

        $stmt = $conn->prepare("UPDATE tugas SET start_time = ?, end_time = ?, task = ? WHERE id = ?");
        $stmt->bind_param("sssi", $start, $end, $task, $id);
        $stmt->execute();
        $stmt->close();
        
        header("Location: index.php");
        exit();
    }

    if (isset($_POST['done'])) {
        $id = $_POST['id'];

       
        $stmt = $conn->prepare("SELECT * FROM tugas WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result_task = $stmt->get_result();
        $task_data = $result_task->fetch_assoc();
        $stmt->close();

        if ($task_data) {
           
            $stmt = $conn->prepare("INSERT INTO history (user_id, category_id, task, start_time, end_time, status) VALUES (?, ?, ?, ?, ?, 'complete')");
            $stmt->bind_param("iisss", $task_data['user_id'], $task_data['category_id'], $task_data['task'], $task_data['start_time'], $task_data['end_time']);
            $stmt->execute();
            $stmt->close();

           
            $stmt = $conn->prepare("DELETE FROM tugas WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
        
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; }
        .sidebar { width: 250px; height: 100vh; position: fixed; background: #343a40; color: white; padding-top: 20px; }
        .sidebar a { display: block; padding: 10px 20px; color: white; text-decoration: none;  }
        .sidebar a:hover { background: #495057; }
        .content { margin-left: 260px; width: calc(100% - 260px); padding: 20px; }
        .doneText { text-decoration: line-through; color: red; }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4 class="text-center">Menu</h4>
    <a href="index.php">ToDo</a>
    <a href="kategori.php">Kategori</a>
    <a href="history.php">History Tugas</a>
    <a href="profil.php">Profil</a>
    <a href="logout.php" class="text-danger">Logout</a>
</div>

<!-- Main Content -->
<div class="content">
    <h1 class="text-center">To Do List</h1>
    
    <form method="POST" class="row justify-content-center mt-3">
        <div class="col-2"><input type="text" class="form-control" name="startTime" placeholder="Start time" required></div>
        <div class="col-2"><input type="text" class="form-control" name="endTime" placeholder="End time" required></div>
        <div class="col-4"><input type="text" class="form-control" name="activity" placeholder="New activity" required></div>
        <div class="col-2">
           
        </div>
        <div class="col-2"><button class="btn btn-primary form-control" name="add">Add</button></div>
    </form>

    <div class="col-8 mt-5 mx-auto">
        <?php while ($item = $result->fetch_assoc()): ?>
            <div class="p-3 border-bottom">
                <div id="view-mode-<?php echo $item['id']; ?>" style="display: block;">
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                        <button class="btn btn-outline-danger me-2" name="delete">Delete</button>
                    </form>
                    <button class="btn btn-warning me-2" onclick="editItem(<?php echo $item['id']; ?>)">Edit</button>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                        <button class="btn btn-success me-3" name="done">Done</button>
                    </form>
                    <span class="<?php echo $item['status'] === 'complete' ? 'doneText' : ''; ?>">
                        <?php echo htmlspecialchars($item['start_time']) . ' - ' . htmlspecialchars($item['end_time']) . ' : ' . htmlspecialchars($item['task']); ?>
                    </span>
                </div>

                <form method="POST" id="edit-mode-<?php echo $item['id']; ?>" style="display: none;">
                    <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                    <input type="text" class="form-control mb-2" name="startTime" value="<?php echo htmlspecialchars($item['start_time']); ?>" required>
                    <input type="text" class="form-control mb-2" name="endTime" value="<?php echo htmlspecialchars($item['end_time']); ?>" required>
                    <input type="text" class="form-control mb-2" name="activity" value="<?php echo htmlspecialchars($item['task']); ?>" required>
                    <button class="btn btn-success" name="update">Save</button>
                    <button type="button" class="btn btn-secondary" onclick="cancelEdit(<?php echo $item['id']; ?>)">Cancel</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
    function editItem(id) {
        document.getElementById('view-mode-' + id).style.display = 'none';
        document.getElementById('edit-mode-' + id).style.display = 'block';
    }

    function cancelEdit(id) {
        document.getElementById('view-mode-' + id).style.display = 'block';
        document.getElementById('edit-mode-' + id).style.display = 'none';
    }
</script>

</body>
</html>
