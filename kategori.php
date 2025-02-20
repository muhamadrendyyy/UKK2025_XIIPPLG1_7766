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