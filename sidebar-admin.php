<!-- Sidebar -->
<div class="sidebar fixed inset-y-0 left-0 w-64 bg-gray-50 shadow-lg z-40 overflow-y-auto flex flex-col" id="sidebar">
    <!-- Logo and Title -->
    <div class="flex items-center justify-center h-20 border-b">
        <img class="h-10 w-auto" src="https://fund.nucaresidoarjo.or.id/assets/img/logo-nucare.png" alt="Logo">
        <h1 class="text-gray-800 text-xl font-bold ml-3">Admin Panel</h1>
    </div>

    <!-- Navigation -->
    <nav class="flex-grow mt-6 px-4 space-y-2">
        <a href="dashboard.php" class="sidebar-link flex items-center px-4 py-2.5 text-gray-600 hover:bg-green-100 hover:text-green-700 rounded-lg transition-colors font-medium" id="nav-dashboard">
            <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i> Dashboard
        </a>
        <a href="kunjungan.php" class="sidebar-link flex items-center px-4 py-2.5 text-gray-600 hover:bg-green-100 hover:text-green-700 rounded-lg transition-colors font-medium" id="nav-kunjungan">
            <i class="fas fa-route w-5 h-5 mr-3"></i> Kunjungan
        </a>
        <a href="donatur.php" class="sidebar-link flex items-center px-4 py-2.5 text-gray-600 hover:bg-green-100 hover:text-green-700 rounded-lg transition-colors font-medium" id="nav-donatur">
            <i class="fas fa-users w-5 h-5 mr-3"></i> Donatur
        </a>
        <a href="users.php" class="sidebar-link flex items-center px-4 py-2.5 text-gray-600 hover:bg-green-100 hover:text-green-700 rounded-lg transition-colors font-medium" id="nav-users">
            <i class="fas fa-user-friends w-5 h-5 mr-3"></i> Fundraiser
        </a>
        <a href="target.php" class="sidebar-link flex items-center px-4 py-2.5 text-gray-600 hover:bg-green-100 hover:text-green-700 rounded-lg transition-colors font-medium" id="nav-target">
            <i class="fas fa-bullseye w-5 h-5 mr-3"></i> Target & Laporan
        </a>
        <a href="analytics.php" class="sidebar-link flex items-center px-4 py-2.5 text-gray-600 hover:bg-green-100 hover:text-green-700 rounded-lg transition-colors font-medium" id="nav-analytics">
            <i class="fas fa-chart-line w-5 h-5 mr-3"></i> Analytics
        </a>
        <a href="settings.php" class="sidebar-link flex items-center px-4 py-2.5 text-gray-600 hover:bg-green-100 hover:text-green-700 rounded-lg transition-colors font-medium" id="nav-settings">
            <i class="fas fa-cog w-5 h-5 mr-3"></i> Pengaturan
        </a>
    </nav>

    <!-- Logout and Info -->
    <div class="mt-auto p-4 border-t">
        <button onclick="logout()" class="w-full flex items-center justify-center px-4 py-2.5 text-red-600 hover:bg-red-100 rounded-lg transition-colors font-semibold">
            <i class="fas fa-sign-out-alt w-5 h-5 mr-3"></i> Logout
        </button>
        <div class="text-center mt-4">
            <div class="text-xs text-gray-500">v2.1.0</div>
        </div>
    </div>
</div>
