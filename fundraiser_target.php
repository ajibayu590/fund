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
$targetProgress = $fundraiserAuth->getTargetProgress($fundraiser_id);
$stats = $fundraiserAuth->getDashboardStats($fundraiser_id);

// Set sidebar for fundraiser
$sidebarFile = 'sidebar-user.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Target Saya - Fundraising System</title>
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
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Target & Progress Saya</h2>
            <p class="text-gray-600 mt-2">Pantau pencapaian target bulanan Anda di sini.</p>
        </div>

        <!-- Target Container -->
        <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Progress Section -->
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Progress Target Kunjungan</h3>
                    <div class="mb-4">
                        <div class="flex justify-between mb-1">
                            <span class="text-base font-medium text-gray-700">Progress Bulan Ini</span>
                            <span class="text-sm font-medium text-gray-700"><?= number_format($stats['kunjungan_bulan_ini'] ?? 0) ?> / <?= number_format($targetProgress['target_kunjungan'] ?? 8) ?></span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="bg-green-500 h-4 rounded-full" style="width: <?= min($targetProgress['progress_percentage'] ?? 0, 100) ?>%"></div>
                        </div>
                        <p class="text-right text-lg font-bold text-green-600 mt-2"><?= number_format($targetProgress['progress_percentage'] ?? 0) ?>%</p>
                    </div>
                </div>

                <!-- Stats Summary Section -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Ringkasan Performa</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-100 rounded-full mr-3">
                                    <i class="fas fa-hand-holding-usd text-blue-600"></i>
                                </div>
                                <p class="text-gray-600">Total Donasi Bulan Ini</p>
                            </div>
                            <p class="font-bold text-gray-800 text-lg">Rp <?= number_format($stats['total_donasi_bulan_ini'] ?? 0) ?></p>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="p-2 bg-green-100 rounded-full mr-3">
                                    <i class="fas fa-user-plus text-green-600"></i>
                                </div>
                                <p class="text-gray-600">Donatur Baru Bulan Ini</p>
                            </div>
                            <p class="font-bold text-gray-800 text-lg"><?= number_format($stats['donatur_baru_bulan_ini'] ?? 0) ?></p>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="p-2 bg-yellow-100 rounded-full mr-3">
                                    <i class="fas fa-route text-yellow-700"></i>
                                </div>
                                <p class="text-gray-600">Total Kunjungan Bulan Ini</p>
                            </div>
                            <p class="font-bold text-gray-800 text-lg"><?= number_format($stats['kunjungan_bulan_ini'] ?? 0) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/ui.js"></script>
</body>
</html>
