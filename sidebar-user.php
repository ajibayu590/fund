<!-- Sidebar -->
<div class="sidebar fixed inset-y-0 left-0 w-64 bg-gray-50 shadow-lg z-40 overflow-y-auto flex flex-col" id="sidebar">
    <!-- Logo and Title -->
    <div class="flex items-center justify-center h-20 border-b">
        <img class="h-10 w-auto" src="https://fund.nucaresidoarjo.or.id/assets/img/logo-nucare.png" alt="Logo">
        <h1 class="text-gray-800 text-xl font-bold ml-3">Fundraiser</h1>
    </div>

    <!-- Navigation -->
    <nav class="flex-grow mt-6 px-4 space-y-2">
        <a href="fundraiser_dashboard.php" class="sidebar-link flex items-center px-4 py-2.5 text-gray-600 hover:bg-green-100 hover:text-green-700 rounded-lg transition-colors font-medium" id="nav-dashboard">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
            </svg>
            Dashboard
        </a>
        <a href="fundraiser_kunjungan.php" class="sidebar-link flex items-center px-4 py-2.5 text-gray-600 hover:bg-green-100 hover:text-green-700 rounded-lg transition-colors font-medium" id="nav-kunjungan">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17l4 4 4-4m-4-5v9"></path>
            </svg>
            Kunjungan Saya
        </a>
        <a href="fundraiser_donatur.php" class="sidebar-link flex items-center px-4 py-2.5 text-gray-600 hover:bg-green-100 hover:text-green-700 rounded-lg transition-colors font-medium" id="nav-donatur">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.657 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Donatur Saya
        </a>
        <a href="fundraiser_target.php" class="sidebar-link flex items-center px-4 py-2.5 text-gray-600 hover:bg-green-100 hover:text-green-700 rounded-lg transition-colors font-medium" id="nav-target">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
            Target Saya
        </a>
        <a href="fundraiser_profile.php" class="sidebar-link flex items-center px-4 py-2.5 text-gray-600 hover:bg-green-100 hover:text-green-700 rounded-lg transition-colors font-medium" id="nav-profile">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Profil Saya
        </a>
    </nav>

    <!-- Logout and Info -->
    <div class="mt-auto p-4 border-t">
        <button onclick="logout()" class="w-full flex items-center justify-center px-4 py-2.5 text-red-600 hover:bg-red-100 rounded-lg transition-colors font-semibold">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"></path>
            </svg>
            Logout
        </button>
        <div class="text-center mt-4">
            <div class="text-xs text-gray-500">v2.1.0</div>
        </div>
    </div>
</div>
