<?php
include 'connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } elseif (empty($username) || empty($password)) {
        $error = "Semua field harus diisi.";
    } elseif (strlen($password) < 8) {
        $error = "Password harus minimal 8 karakter!";
    }else{   
        $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
        if (!$stmt) {
            die("Error: " . $conn->error); 
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email sudah digunakan.";
        } else {
            
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if (!$stmt) {
                die("Error: " . $conn->error); 
            }

            $stmt->bind_param("sss", $username, $email, $hashed_password);
            if ($stmt->execute()) {
                $_SESSION['users'] = [
                    'email' => $email,
                    'username' => $username
                ];
                header("Location: login.php");
                exit();
            } else {
                echo "Error: " . $stmt->error; 
            }
        }
        $stmt->close();
    }
}
?>


<html>
<head>
    <title>Register Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-cyan-200">
    <div class="bg-white bg-opacity-50 p-8 rounded-lg shadow-lg w-full max-w-sm">
        <h2 class="text-2xl font-bold text-center mb-6">Register</h2>
        <?php if (isset($error)) echo "<p class='text-red-500 text-sm'>$error</p>"; ?>
        <form method="POST" action="">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none" name="username" type="text" placeholder="Username">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none" name="email" type="email" placeholder="Email">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none" name="password" type="password" placeholder="Password">
            </div>
            <div class="flex items-center justify-center">
                <button class="bg-cyan-500 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded focus:outline-none" type="submit">
                    REGISTER
                </button>
            </div>
        </form>
    </div>
</body>
</html>
