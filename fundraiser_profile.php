<?php
require_once 'includes/db.php';
require_once 'includes/fundraiser_auth.php';

// Securely start the session and apply security headers
secure_session_start();
set_security_headers();

// Require fundraiser role
require_role('user');

$fundraiser_id = $_SESSION['user_id'];
$fundraiserAuth = new FundraiserAuth();
$fundraiser = $fundraiserAuth->getCurrentFundraiser($fundraiser_id);

$message = '';
$message_type = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nama_lengkap' => $_POST['nama_lengkap'] ?? '',
        'email' => $_POST['email'] ?? '',
        'hp' => $_POST['hp'] ?? '',
        'alamat' => $_POST['alamat'] ?? '',
        'target_kunjungan' => $_POST['target_kunjungan'] ?? 8,
    ];

    if ($fundraiserAuth->updateProfile($fundraiser_id, $data)) {
        $message = 'Profil berhasil diperbarui!';
        $message_type = 'success';
        // Refresh fundraiser data
        $fundraiser = $fundraiserAuth->getCurrentFundraiser($fundraiser_id);
    } else {
        $message = 'Gagal memperbarui profil. Silakan coba lagi.';
        $message_type = 'error';
    }
}

// Set sidebar for fundraiser
$sidebarFile = 'sidebar-user.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - <?= htmlspecialchars($fundraiser['name'] ?? 'Fundraiser') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles/main.css?v=1.1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn fixed top-4 left-4 z-50 bg-green-600 text-white p-2 rounded-lg shadow-lg md:hidden" onclick="toggleMobileSidebar()">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>

    <?php include $sidebarFile; ?>

    <div class="main-content p-4 md:p-8 md:ml-64">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Profil Saya</h2>
            <p class="text-gray-600 mt-2">Kelola informasi pribadi dan target Anda.</p>
        </div>

        <!-- Profile Form Container -->
        <div class="bg-white rounded-xl shadow-lg p-6 md:p-8 max-w-2xl mx-auto">
            <?php if ($message): ?>
                <div class="mb-6 p-4 rounded-lg <?= $message_type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="space-y-6">
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?= htmlspecialchars($fundraiser['nama_lengkap'] ?? '') ?>" required
                               class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($fundraiser['email'] ?? '') ?>" required
                               class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="hp" class="block text-sm font-medium text-gray-700">No. HP</label>
                        <input type="text" id="hp" name="hp" value="<?= htmlspecialchars($fundraiser['hp'] ?? '') ?>" required
                               class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea id="alamat" name="alamat" rows="3"
                                  class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"><?= htmlspecialchars($fundraiser['alamat'] ?? '') ?></textarea>
                    </div>
                    <div>
                        <label for="target_kunjungan" class="block text-sm font-medium text-gray-700">Target Kunjungan Harian</label>
                        <input type="number" id="target_kunjungan" name="target_kunjungan" value="<?= htmlspecialchars($fundraiser['target_kunjungan'] ?? 8) ?>" required
                               class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                    </div>
                </div>
                <div class="mt-8 pt-5 border-t border-gray-200">
                    <div class="flex justify-end">
                        <button type="submit"
                                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center">
                            <i class="fas fa-save mr-2"></i>
                            Update Profil
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="js/ui.js"></script>
</body>
</html>
