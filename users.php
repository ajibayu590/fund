<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Start session and apply security headers
secure_session_start();
set_security_headers();

// Require admin role for this page
require_role('admin');

// Get user data from session for display purposes
$user = [
    'name' => $_SESSION['user_name'],
    'role' => $_SESSION['role'],
];

// Set sidebar for admin
$sidebarFile = 'sidebar-admin.php';
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Fundraiser</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles/main.css">
</head>
<body class="bg-gray-100">
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn fixed top-4 left-4 z-50 bg-blue-600 text-white p-2 rounded-lg shadow-lg md:hidden" onclick="toggleMobileSidebar()">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>

    <!-- Notification Container -->
    <div id="notification-container"></div>

    <?php include $sidebarFile; ?>
    
    <div class="main-content p-4 md:p-8 md:ml-64">
        <div class="mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Kelola Fundraiser</h2>
            <p class="text-gray-600 mt-2">Manajemen data fundraiser, target, dan performa</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <h3 class="text-xl font-semibold">Daftar Fundraiser</h3>
                    <div class="flex flex-wrap gap-3">
                        <button onclick="bulkUpdateTarget()" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors text-sm">
                            Update Target Massal
                        </button>
                        <button onclick="showUserModal()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm">
                            + Tambah Fundraiser
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Desktop Table -->
            <div class="desktop-table data-table">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" id="select-all-users" onchange="toggleSelectAll('users')">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fundraiser</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target Harian</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress Hari Ini</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Performa Bulan Ini</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="users-table" class="bg-white divide-y divide-gray-200">
                        <!-- Data will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="mobile-card" id="users-mobile">
                <!-- Mobile cards will be populated by JavaScript -->
            </div>
        </div>
    </div>

    <!-- Modals will be loaded dynamically -->
    <div id="modal-container"></div>

    <!-- Enhanced Modal for Adding User -->
    <div id="user-modal" class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold">Tambah Fundraiser</h3>
                <button onclick="hideUserModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="user-form">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                        <input type="text" id="user-nama" name="nama" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" id="user-email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. HP *</label>
                        <input type="text" id="user-hp" name="hp" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required pattern="[0-9]{10,13}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Target Kunjungan Harian *</label>
                        <input type="number" id="user-target" name="target" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="8" required min="1" max="20">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                        <input type="password" id="user-password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required minlength="6">
                    </div>
                </div>
                <div class="flex flex-col md:flex-row justify-end space-y-3 md:space-y-0 md:space-x-3 mt-6">
                    <button type="button" onclick="hideUserModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center">
                        <span class="loading hidden mr-2"></span>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="js/utils.js"></script>
    <script src="js/ui.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadFundraisers();

            document.getElementById('user-form').addEventListener('submit', handleAddUser);
        });

        async function fetchApi(url, options = {}) {
            try {
                const response = await fetch(url, options);
                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({ message: 'An unknown error occurred' }));
                    throw new Error(`HTTP error ${response.status}: ${errorData.error || errorData.message}`);
                }
                return await response.json();
            } catch (error) {
                console.error('API Fetch Error:', error);
                if (typeof showNotification === 'function') {
                    showNotification(error.message, 'error');
                }
                throw error;
            }
        }

        async function loadFundraisers() {
            try {
                const fundraisers = await fetchApi('api/admin_api.php?action=fundraisers');
                renderFundraisers(fundraisers);
            } catch (error) {
                document.getElementById('users-table').innerHTML = `<tr><td colspan="8" class="text-center p-4">Gagal memuat data fundraiser.</td></tr>`;
            }
        }

        function renderFundraisers(fundraisers) {
            const tableBody = document.getElementById('users-table');
            const mobileContainer = document.getElementById('users-mobile');
            tableBody.innerHTML = '';
            mobileContainer.innerHTML = '';

            if (fundraisers.length === 0) {
                const emptyRow = `<tr><td colspan="8" class="text-center p-4">Belum ada data fundraiser.</td></tr>`;
                tableBody.innerHTML = emptyRow;
                mobileContainer.innerHTML = `<div class="p-4 text-center">Belum ada data fundraiser.</div>`;
                return;
            }

            fundraisers.forEach(user => {
                const progressPercentage = user.target_kunjungan > 0 ? (user.progress_today / user.target_kunjungan) * 100 : 0;
                
                // Desktop Row
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="user-checkbox" value="${user.id}">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">${user.name}</div>
                        <div class="text-sm text-gray-500">${user.email}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${user.hp}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">${user.target_kunjungan}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: ${Math.min(100, progressPercentage)}%"></div>
                        </div>
                        <div class="text-xs text-center mt-1">${user.progress_today}/${user.target_kunjungan}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">${user.performance_month} Kunjungan</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${user.status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            ${user.status}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        <a href="#" class="text-red-600 hover:text-red-900 ml-4">Hapus</a>
                    </td>
                `;
                tableBody.appendChild(row);

                // Mobile Card
                const card = document.createElement('div');
                card.className = 'bg-white p-4 rounded-lg shadow mb-3';
                card.innerHTML = `
                    <div class="flex justify-between items-center mb-2">
                        <div>
                            <div class="font-bold text-gray-800">${user.name}</div>
                            <div class="text-sm text-gray-500">${user.email}</div>
                        </div>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${user.status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            ${user.status}
                        </span>
                    </div>
                    <div class="text-sm text-gray-600 mb-2">HP: ${user.hp}</div>
                    <div class="border-t border-gray-200 pt-2">
                        <div class="flex justify-between text-sm">
                            <span>Target Harian: <strong>${user.target_kunjungan}</strong></span>
                            <span>Bulan Ini: <strong>${user.performance_month} Kunjungan</strong></span>
                        </div>
                        <div class="text-xs text-center mt-1">Progress Hari Ini: ${user.progress_today}/${user.target_kunjungan}</div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mt-1">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: ${Math.min(100, progressPercentage)}%"></div>
                        </div>
                    </div>
                    <div class="mt-3 text-right">
                         <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        <a href="#" class="text-red-600 hover:text-red-900 ml-4">Hapus</a>
                    </div>
                `;
                mobileContainer.appendChild(card);
            });
        }

        async function handleAddUser(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            const submitButton = form.querySelector('button[type="submit"]');
            const loadingSpan = submitButton.querySelector('.loading');
            
            submitButton.disabled = true;
            loadingSpan.classList.remove('hidden');

            try {
                const result = await fetchApi('api/admin_api.php?action=fundraisers', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                if (result.success) {
                    if (typeof showNotification === 'function') {
                        showNotification('Fundraiser berhasil ditambahkan.', 'success');
                    }
                    hideUserModal();
                    form.reset();
                    loadFundraisers();
                }
            } catch (error) {
                // Error is handled in fetchApi
            } finally {
                submitButton.disabled = false;
                loadingSpan.classList.add('hidden');
            }
        }
    </script>

</body>
</html>
