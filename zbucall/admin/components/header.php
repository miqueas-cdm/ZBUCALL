<?php
$employee = getCurrentEmployee();
?>
<header class="fixed top-0 right-0 left-0 lg:left-64 h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 z-30 transition-colors">
    <div class="h-full px-6 flex items-center justify-between">
        <!-- Mobile Menu Button -->
        <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            <i data-lucide="menu" class="w-6 h-6 text-gray-600 dark:text-gray-300"></i>
        </button>

        <!-- Search -->
        <div class="hidden md:flex items-center flex-1 max-w-md ml-4">
            <div class="relative w-full">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                <input type="text" 
                       placeholder="Buscar no sistema..." 
                       class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm">
            </div>
        </div>

        <!-- Right Actions -->
        <div class="flex items-center gap-4 ml-auto">
            <!-- Theme Toggle -->
            <button id="theme-toggle" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors relative group">
                <i data-lucide="sun" class="w-5 h-5 text-gray-600 dark:text-gray-300 hidden dark:block"></i>
                <i data-lucide="moon" class="w-5 h-5 text-gray-600 dark:text-gray-300 block dark:hidden"></i>
                <span class="absolute top-full right-0 mt-2 py-1 px-2 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                    Alternar Tema
                </span>
            </button>

            <!-- Notifications -->
            <div class="relative">
                <button id="notifications-btn" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors relative">
                    <i data-lucide="bell" class="w-5 h-5 text-gray-600 dark:text-gray-300"></i>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white dark:border-gray-800"></span>
                </button>
            </div>

            <!-- Profile Dropdown -->
            <div class="relative ml-2">
                <button id="profile-btn" class="flex items-center gap-3 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <img src="../<?= htmlspecialchars($employee['photo_url']) ?>" alt="Profile" class="w-8 h-8 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700">
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($employee['name']) ?></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Administrador</p>
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-500 dark:text-gray-400 hidden md:block"></i>
                </button>

                <!-- Dropdown Menu -->
                <div id="profile-dropdown" class="hidden absolute right-0 top-full mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 animate-in fade-in zoom-in-95 duration-200">
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 md:hidden">
                        <p class="text-sm font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($employee['name']) ?></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($employee['email']) ?></p>
                    </div>
                    <a href="../dashboard.php" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <i data-lucide="layout" class="w-4 h-4 inline mr-2"></i>
                        Portal do Associado
                    </a>
                    <button onclick="handleLogout()" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
                        <i data-lucide="log-out" class="w-4 h-4 inline mr-2"></i>
                        Sair
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>
