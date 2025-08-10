<?php
// Updated Login System with Fundraiser Role Support
// This file handles user authentication and redirects based on role

require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/fundraiser_auth.php';

session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Email dan password harus diisi';
    } else {
        $db = getDB();
        $stmt = $db->prepare("SELECT id, name, email, password, role FROM users WHERE email = ? AND status = 'aktif'");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];
            
            // Redirect based on role
            switch ($user['role']) {
                case 'admin':
                    header('Location: dashboard.php');
                    break;
                case 'fundraiser':
                    header('Location: fundraiser_dashboard.php');
                    break;
                default:
                    header('Location: user_dashboard.php');
                    break;
            }
            exit();
        } else {
            $error = 'Email atau password salah';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Fundraising System</title>
    <link rel="stylesheet" href="styles/main.css">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col justify-center items-center">
    <div class="w-full max-w-md mx-auto">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Login Fundraising System</h2>
            
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                    Login
                </button>
            </form>
            
            <p class="mt-4 text-center text-sm text-gray-600">
                <a href="register.php" class="text-blue-600 hover:text-blue-800">Daftar sebagai Fundraiser Baru</a>
            </p>
        </div>
    </div>
</body>
</html>
