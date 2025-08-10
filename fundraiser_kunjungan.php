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
$kunjunganList = $fundraiserAuth->getKunjunganHistory($fundraiser_id);

// Set sidebar for fundraiser
$sidebarFile = 'sidebar-user.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kunjungan Saya - Fundraising System</title>
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
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Data Kunjungan Saya</h2>
            <a href="fundraiser_kunjungan.php?action=add" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Tambah Kunjungan
            </a>
        </div>

        <!-- Table Container -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Donatur</th>
                            <th scope="col" class="px-6 py-3">Waktu</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Nominal</th>
                            <th scope="col" class="px-6 py-3">Catatan</th>
                            <th scope="col" class="px-6 py-3">Follow Up</th>
                            <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($kunjunganList)): ?>
                            <tr class="bg-white border-b">
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    Belum ada data kunjungan.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($kunjunganList as $kunjungan): ?>
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        <?= htmlspecialchars($kunjungan['donatur_nama']) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?= date('d M Y, H:i', strtotime($kunjungan['waktu'])) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                                            <?= $kunjungan['status'] === 'berhasil' ? 'bg-green-100 text-green-800' : '' ?>
                                            <?= $kunjungan['status'] === 'tidak-berhasil' ? 'bg-red-100 text-red-800' : '' ?>
                                            <?= $kunjungan['status'] === 'follow-up' ? 'bg-yellow-100 text-yellow-800' : '' ?>
                                        ">
                                            <?= ucfirst($kunjungan['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        Rp <?= number_format($kunjungan['nominal']) ?>
                                    </td>
                                    <td class="px-6 py-4 max-w-xs truncate">
                                        <?= htmlspecialchars($kunjungan['catatan']) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?= $kunjungan['follow_up_date'] ? date('d M Y', strtotime($kunjungan['follow_up_date'])) : '-' ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="fundraiser_kunjungan.php?action=edit&id=<?= $kunjungan['id'] ?>" class="font-medium text-blue-600 hover:underline mr-3"><i class="fas fa-edit"></i></a>
                                        <a href="fundraiser_kunjungan.php?action=delete&id=<?= $kunjungan['id'] ?>" class="font-medium text-red-600 hover:underline" onclick="return confirm('Apakah Anda yakin ingin menghapus kunjungan ini?')"><i class="fas fa-trash"></i></a>
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
