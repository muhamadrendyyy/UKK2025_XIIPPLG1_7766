<?php
session_start();
require 'connect.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT history.task, history.end_time, categories.category AS category_name 
          FROM history 
          JOIN categories ON history.category_id = categories.id 
          WHERE history.user_id = ? AND history.status = 'complete' 
          ORDER BY history.end_time DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Cek apakah ada hasil yang ditemukan
if ($result->num_rows === 0) {
    echo "<p class='text-muted'>Tidak ada tugas yang selesai.</p>";
} else {
    echo "<ul class='list-group'>";
    while ($row = $result->fetch_assoc()):
        echo "<li class='list-group-item doneText'>" . 
             htmlspecialchars($row['task']) . " - " . 
             htmlspecialchars($row['category_name']) . " (Selesai pada: " . 
             htmlspecialchars($row['end_time']) . ")</li>";
    endwhile;
    echo "</ul>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; }
        .sidebar { width: 250px; height: 100vh; position: fixed; background: #343a40; color: white; padding-top: 20px; }
        .sidebar a { display: block; padding: 10px 20px; color: white; text-decoration: none; }
        .sidebar a:hover { background: #495057; }
        .content { margin-left: 260px; padding: 20px; width: calc(100% - 260px); }
        .doneText { text-decoration: line-through; color: red; }
    </style>
</head>
<body>

<div class="sidebar">
    <h4 class="text-center">Menu</h4>
    <a href="index.php">ToDo</a>
    <a href="kategori.php">Kategori</a>
    <a href="history.php">History Tugas</a>
    <a href="profil.php">Profil</a>
    <a href="logout.php" class="text-danger">Logout</a>
</div>

<div class="content">
    <h2>Riwayat Tugas</h2>
    <ul class="list-group">
        <?php while ($row = $result->fetch_assoc()): ?>
            <li class="list-group-item doneText">
                <strong><?php echo htmlspecialchars($row['task']); ?></strong> 
                <br> 
                <small>Kategori: <?php echo htmlspecialchars($row['category']); ?></small>  
                <br> 
                <small>Selesai pada: <?php echo htmlspecialchars($row['end_time']); ?></small>
            </li>
        <?php endwhile; ?>
    </ul>
</div>

</body>
</html>
