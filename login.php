<?php
include 'connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } else {
        
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {            
                header("Location: index.php");
                exit();
            } else {
                $error = "Password salah.";
            }
        } else {
            $error = "Email tidak ditemukan.";
        }

        $stmt->close();
    }
}
?>

<html>
<head>
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="flex items-center justify-center min-h-screen bg-blue-500">
    <div class="bg-white bg-opacity-50 p-8 rounded-lg shadow-lg w-full max-w-sm">
        <h2 class="text-2xl font-bold text-center mb-6">LogIn</h2>
        <form>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email Address</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" type="email" placeholder="Email Address">
            </div>
            <div class="mb-4 relative">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" placeholder="Password">
            </div>
            <div class="mb-6 text-center">
                <a class="inline-block align-baseline text-sm text-gray-600 hover:text-blue-800" href="register.php">belum ada akun?register</a>
            </div>
            <div class="flex items-center justify-center">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    LOGIN
                </button>
            </div>
        </form>
    </div>
</body>
</html>