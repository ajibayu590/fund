<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Securely start the session and apply security headers
secure_session_start();
set_security_headers();

// Require admin role
require_role('admin');

// Set sidebar for admin
$sidebarFile = 'sidebar-admin.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Fundraising</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles/main.css?v=1.1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="js/chart.min.js"></script>
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
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Dashboard Kunjungan Fundraising</h2>
                <p class="text-gray-600 mt-2">Monitoring real-time kunjungan dan performa fundraiser</p>
            </div>
            <div class="flex items-center space-x-2">
                <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors flex items-center">
                    <i class="fas fa-file-export mr-2"></i> Export Data
                </button>
                <button id="refresh-btn" class="bg-green-500 text-white p-2 rounded-lg hover:bg-green-600 transition-colors">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-lg flex items-center justify-between border-l-4 border-blue-500">
                <div>
                    <p class="text-sm text-gray-600">Total Kunjungan Hari Ini</p>
                    <p id="total-kunjungan" class="text-3xl font-bold text-gray-800">0</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full"><i class="fas fa-route text-blue-600 text-2xl"></i></div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg flex items-center justify-between border-l-4 border-green-500">
                <div>
                    <p class="text-sm text-gray-600">Donasi Berhasil</p>
                    <p id="donasi-berhasil" class="text-3xl font-bold text-gray-800">0</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full"><i class="fas fa-check-circle text-green-600 text-2xl"></i></div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg flex items-center justify-between border-l-4 border-red-500">
                <div>
                    <p class="text-sm text-gray-600">Kunjungan Gagal</p>
                    <p id="kunjungan-gagal" class="text-3xl font-bold text-gray-800">0</p>
                </div>
                <div class="p-3 bg-red-100 rounded-full"><i class="fas fa-times-circle text-red-600 text-2xl"></i></div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg flex items-center justify-between border-l-4 border-yellow-500">
                <div>
                    <p class="text-sm text-gray-600">Total Donasi Hari Ini</p>
                    <p id="total-donasi-hari-ini" class="text-2xl font-bold text-gray-800">Rp 0</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full"><i class="fas fa-hand-holding-usd text-yellow-600 text-2xl"></i></div>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-semibold mb-4">Kunjungan per Jam</h3>
                <canvas id="kunjungan-per-jam-chart"></canvas>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-semibold mb-4">Status Kunjungan</h3>
                <canvas id="status-kunjungan-chart"></canvas>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold mb-4">Aktivitas Terbaru</h3>
            <div id="recent-activities" class="space-y-4">
                <!-- Data will be loaded here by JavaScript -->
            </div>
        </div>
    </div>

    <script src="js/config.js"></script>
    <script src="js/utils.js"></script>
    <script src="js/charts.js"></script>
    <script src="js/ui.js"></script>
    <script src="js/app.js"></script>
</body>
</html>
