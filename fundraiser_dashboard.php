<?php
require_once 'includes/db.php';
require_once 'includes/fundraiser_auth.php';

// Securely start the session and apply security headers
secure_session_start();
set_security_headers();

// Require fundraiser role
require_role('user'); // 'user' is the role for fundraisers in the users table

$fundraiser_id = $_SESSION['user_id'];
$fundraiserAuth = new FundraiserAuth();
$fundraiser = $fundraiserAuth->getCurrentFundraiser($fundraiser_id);
$stats = $fundraiserAuth->getDashboardStats($fundraiser_id);
$targetProgress = $fundraiserAuth->getTargetProgress($fundraiser_id);
$recentKunjungan = $fundraiserAuth->getKunjunganHistory($fundraiser_id, 5); // Limit to 5 for dashboard

// Set sidebar for fundraiser
$sidebarFile = 'sidebar-user.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?= htmlspecialchars($fundraiser['name'] ?? 'Fundraiser') ?></title>
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
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Selamat Datang, <?= htmlspecialchars($fundraiser['name'] ?? 'Fundraiser') ?>!</h2>
            <p class="text-gray-600 mt-2">Ini adalah ringkasan performa dan aktivitas Anda.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-lg flex items-center justify-between border-l-4 border-blue-500">
                <div>
                    <p class="text-sm text-gray-600">Kunjungan Hari Ini</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['kunjungan_hari_ini'] ?? 0) ?></p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full"><i class="fas fa-route text-blue-600 text-2xl"></i></div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg flex items-center justify-between border-l-4 border-green-500">
                <div>
                    <p class="text-sm text-gray-600">Donasi Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-800">Rp <?= number_format($stats['total_donasi_hari_ini'] ?? 0) ?></p>
                </div>
                <div class="p-3 bg-green-100 rounded-full"><i class="fas fa-money-bill-wave text-green-600 text-2xl"></i></div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg flex items-center justify-between border-l-4 border-yellow-500">
                <div>
                    <p class="text-sm text-gray-600">Kunjungan Bulan Ini</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['kunjungan_bulan_ini'] ?? 0) ?></p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full"><i class="fas fa-calendar-check text-yellow-600 text-2xl"></i></div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg flex items-center justify-between border-l-4 border-purple-500">
                <div>
                    <p class="text-sm text-gray-600">Target Tercapai</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($targetProgress['progress_percentage'] ?? 0) ?>%</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full"><i class="fas fa-bullseye text-purple-600 text-2xl"></i></div>
            </div>
        </div>

        <!-- Progress and Recent Activities -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="lg:col-span-1 bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-semibold mb-4">Progress Target Bulan Ini</h3>
                <p class="text-gray-600 mb-2"><?= number_format($stats['kunjungan_bulan_ini'] ?? 0) ?> dari <?= number_format($targetProgress['target_kunjungan'] ?? 8) ?> kunjungan</p>
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="bg-green-500 h-4 rounded-full" style="width: <?= min($targetProgress['progress_percentage'] ?? 0, 100) ?>%"></div>
                </div>
            </div>
            <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-semibold mb-4">Aktivitas Terbaru</h3>
                <div class="space-y-4">
                    <?php if (empty($recentKunjungan)): ?>
                        <p class="text-gray-500">Belum ada aktivitas kunjungan.</p>
                    <?php else: ?>
                        <?php foreach ($recentKunjungan as $kunjungan): ?>
                            <div class="flex items-center">
                                <div class="p-2 bg-green-100 rounded-full mr-4"><i class="fas fa-handshake text-green-600"></i></div>
                                <div class="flex-grow">
                                    <p class="font-medium text-gray-800"><?= htmlspecialchars($kunjungan['donatur_nama']) ?></p>
                                    <p class="text-sm text-gray-500"><?= htmlspecialchars($kunjungan['catatan']) ?></p>
                                </div>
                                <div class="text-right">
                                    <?php if ($kunjungan['nominal'] > 0): ?>
                                        <p class="font-semibold text-green-600">Rp <?= number_format($kunjungan['nominal']) ?></p>
                                    <?php endif; ?>
                                    <p class="text-xs text-gray-400"><?= date('d M, H:i', strtotime($kunjungan['waktu'])) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold mb-4">Aksi Cepat</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="fundraiser_kunjungan.php?action=add" class="text-center p-4 bg-gray-50 hover:bg-green-100 rounded-lg transition-colors">
                    <i class="fas fa-plus-circle text-green-600 text-3xl mb-2"></i>
                    <p class="font-medium text-gray-700">Tambah Kunjungan</p>
                </a>
                <a href="fundraiser_donatur.php?action=add" class="text-center p-4 bg-gray-50 hover:bg-green-100 rounded-lg transition-colors">
                    <i class="fas fa-user-plus text-green-600 text-3xl mb-2"></i>
                    <p class="font-medium text-gray-700">Tambah Donatur</p>
                </a>
                <a href="fundraiser_kunjungan.php" class="text-center p-4 bg-gray-50 hover:bg-blue-100 rounded-lg transition-colors">
                    <i class="fas fa-list-alt text-blue-600 text-3xl mb-2"></i>
                    <p class="font-medium text-gray-700">Lihat Kunjungan</p>
                </a>
                <a href="fundraiser_donatur.php" class="text-center p-4 bg-gray-50 hover:bg-blue-100 rounded-lg transition-colors">
                    <i class="fas fa-address-book text-blue-600 text-3xl mb-2"></i>
                    <p class="font-medium text-gray-700">Lihat Donatur</p>
                </a>
            </div>
        </div>
    </div>
    
    <script src="js/ui.js"></script>
</body>
</html>
