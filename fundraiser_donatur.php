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
// Assuming a method exists to get donaturs linked to the fundraiser
$donaturList = $fundraiserAuth->getDonaturList($fundraiser_id);

// Set sidebar for fundraiser
$sidebarFile = 'sidebar-user.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Donatur Saya - Fundraising System</title>
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
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Data Donatur Saya</h2>
            <a href="fundraiser_donatur.php?action=add" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Tambah Donatur
            </a>
        </div>

        <!-- Table Container -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Nama</th>
                            <th scope="col" class="px-6 py-3">Kontak</th>
                            <th scope="col" class="px-6 py-3">Kategori</th>
                            <th scope="col" class="px-6 py-3">Total Donasi</th>
                            <th scope="col" class="px-6 py-3">Kunjungan Berhasil</th>
                            <th scope="col" class="px-6 py-3">Terakhir Kunjungan</th>
                            <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($donaturList)): ?>
                            <tr class="bg-white border-b">
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    Belum ada data donatur.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($donaturList as $donatur): ?>
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        <?= htmlspecialchars($donatur['nama']) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900"><?= htmlspecialchars($donatur['hp']) ?></div>
                                        <div class="text-xs text-gray-500"><?= htmlspecialchars($donatur['email']) ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                            <?= ucfirst($donatur['kategori']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        Rp <?= number_format($donatur['total_donasi_berhasil'] ?? 0) ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <?= number_format($donatur['total_kunjungan_berhasil'] ?? 0) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?= $donatur['terakhir_kunjungan'] ? date('d M Y', strtotime($donatur['terakhir_kunjungan'])) : '-' ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="fundraiser_donatur.php?action=edit&id=<?= $donatur['id'] ?>" class="font-medium text-blue-600 hover:underline mr-3"><i class="fas fa-edit"></i></a>
                                        <a href="fundraiser_donatur.php?action=delete&id=<?= $donatur['id'] ?>" class="font-medium text-red-600 hover:underline" onclick="return confirm('Apakah Anda yakin ingin menghapus donatur ini?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="js/ui.js"></script>
</body>
</html>
