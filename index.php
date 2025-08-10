<?php
// Unified Login System & Main Entry Point
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Securely start the session and set security headers
secure_session_start();
set_security_headers();

// Redirect if already logged in
if (is_logged_in()) {
    header('Location: ' . (has_role('admin') ? 'dashboard.php' : 'fundraiser_dashboard.php'));
    exit();
}

$error = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Email and password are required.';
    } else {
        $db = getDB();
        $stmt = $db->prepare("SELECT id, name, email, password, role, status FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            if (isset($user['status']) && $user['status'] !== 'aktif') {
                $error = 'Akun Anda tidak aktif. Silakan hubungi administrator.';
            } else {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                
                header('Location: ' . ($user['role'] === 'admin' ? 'dashboard.php' : 'fundraiser_dashboard.php'));
                exit();
            }
        } else {
            $error = 'Email atau password salah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Fundraising System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles/main.css?v=1.4">
    <style>
        body {
            background-image: url('https://fund.nucaresidoarjo.or.id/assets/img/bg-login.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
</head>
<body class="h-full">
    <div class="flex min-h-full items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">
            <div class="bg-white/90 backdrop-blur-sm shadow-xl rounded-2xl p-8 sm:p-10">
                <div class="text-center">
                    <img class="mx-auto h-20 w-auto mb-4" src="https://fund.nucaresidoarjo.or.id/assets/img/logo-nucare.png" alt="Logo">
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900">
                        Fundraising System Login
                    </h2>
                </div>

                <div class="mt-8">
                    <?php if ($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                        <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <form class="space-y-6" action="index.php" method="POST" novalidate>
                        <div>
                            <label for="email" class="sr-only">Alamat Email</label>
                            <input id="email" name="email" type="email" autocomplete="email" required
                                class="relative block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-green-500 focus:outline-none focus:ring-green-500 sm:text-sm"
                                placeholder="Alamat Email">
                        </div>

                        <div>
                            <label for="password" class="sr-only">Password</label>
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                class="relative block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-green-500 focus:outline-none focus:ring-green-500 sm:text-sm"
                                placeholder="Password">
                        </div>

                        <div>
                            <button type="submit"
                                class="group relative flex w-full justify-center rounded-md border border-transparent bg-green-600 py-2 px-4 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                Sign in
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
