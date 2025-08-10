<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Start session and apply security headers
secure_session_start();
set_security_headers();

// Require admin role for this page
require_role('admin');

// Set sidebar for admin
$sidebarFile = 'sidebar-admin.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Donatur</title>
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
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Semua Donatur</h2>
            <p class="text-gray-600 mt-2">Monitor semua donatur yang terdaftar.</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-xl font-semibold">Daftar Donatur</h3>
            </div>
            
            <div class="data-table">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fundraiser Terkait</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Donasi</th>
                        </tr>
                    </thead>
                    <tbody id="donatur-table" class="bg-white divide-y divide-gray-200">
                        <!-- Data will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="js/utils.js"></script>
    <script src="js/ui.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadAllDonatur();
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

        async function loadAllDonatur() {
            try {
                // We need to add an endpoint in the admin API to get all donatur
                const response = await fetchApi('api/admin_api.php?action=all_donatur');
                renderDonatur(response);
            } catch (error) {
                document.getElementById('donatur-table').innerHTML = `<tr><td colspan="4" class="text-center p-4">Gagal memuat data donatur.</td></tr>`;
            }
        }

        function renderDonatur(donatur) {
            const tableBody = document.getElementById('donatur-table');
            tableBody.innerHTML = '';

            if (donatur.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="4" class="text-center p-4">Belum ada data donatur.</td></tr>`;
                return;
            }

            donatur.forEach(d => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${escapeHTML(d.nama)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${escapeHTML(d.hp)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${escapeHTML(d.fundraiser_name)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp ${new Intl.NumberFormat('id-ID').format(d.total_donasi || 0)}</td>
                `;
                tableBody.appendChild(row);
            });
        }

        function escapeHTML(str) {
            if (str === null || str === undefined) return '';
            return str.toString().replace(/[&<>"']/g, function(match) {
                return {
                    '&': '&amp;',
                    '<': '<',
                    '>': '>',
                    '"': '"',
                    "'": '&#39;'
                }[match];
            });
        }
    </script>
</body>
</html>
