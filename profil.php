<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo List - Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; }
        .sidebar { width: 250px; height: 100vh; position: fixed; background: #343a40; color: white; padding-top: 20px; }
        .sidebar a { display: block; padding: 10px 20px; color: white; text-decoration: none; }
        .sidebar a:hover { background: #495057; }
        .content { margin-left: 260px; width: calc(100% - 260px); padding: 20px; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .profile-container { text-align: center; background: #f8f9fa; padding: 30px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        .profile-container img { width: 150px; height: 150px; border-radius: 50%; margin-bottom: 15px; }
        .profile-container h2 { margin-bottom: 10px; }
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

<!-- Content -->
<div class="content">
    <div class="profile-container">
        <img src="profile.jpg" alt="User">
        <h2>Muhamad Rendy</h2>
        <p>Email: rendyy@gmail.com</p>
        <p>Joined: Januari 2025</p>
    </div>
</div>

</body>
</html>
