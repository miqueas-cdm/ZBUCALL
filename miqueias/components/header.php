<?php
/**
 * Header Component
 * Includes user info, notifications, search, and theme toggle
 */

require_once __DIR__ . '/../config/session.php';

$employee = getCurrentEmployee();
if (!$employee) {
    header('Location: index.php');
    exit;
}
?>

<header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40 no-print">
    <div class="px-4 py-3 flex items-center justify-between gap-4">
        <!-- Left: Mobile Menu + Logo -->
        <div class="flex items-center gap-4">
            <button id="mobile-menu-button" class="lg:hidden text-gray-600 dark:text-gray-300">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
            <h1 class="text-xl font-bold text-primary hidden md:block">Portal do Colaborador</h1>
        </div>
        
        <!-- Center: Search (hidden on mobile) -->
        <div class="hidden md:block flex-1 max-w-xl relative">
            <div class="relative">
                <i data-lucide="search" class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" 
                       id="global-search"
                       placeholder="Buscar..." 
                       class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
            </div>
            <div id="search-results" class="hidden absolute top-full mt-2 w-full bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 max-h-96 overflow-y-auto"></div>
        </div>
        
        <!-- Right: Notifications + Theme + User -->
        <div class="flex items-center gap-3">
            <!-- Notifications -->
            <div class="relative">
                <button id="notification-bell" class="relative p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span id="notification-badge" class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
                </button>
                
                <!-- Notifications Dropdown -->
                <div id="notifications-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 max-h-96 overflow-hidden">
                    <div class="p-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Notificações</h3>
                        <a href="javascript:void(0)" onclick="NotificationManager.markAllAsRead()" class="text-xs text-blue-600 hover:text-blue-700">Marcar todas como lida</a>
                    </div>
                    <div id="notifications-list" class="overflow-y-auto max-h-80"></div>
                </div>
            </div>
            
            <!-- Theme Toggle -->
            <button id="theme-toggle" class="p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                <i id="theme-icon" data-lucide="moon" class="w-5 h-5"></i>
            </button>
            
            <!-- User Menu -->
            <div class="flex items-center gap-2 pl-3 border-l border-gray-300 dark:border-gray-600">
                <img src="<?= htmlspecialchars($employee['photo_url']) ?>" 
                     alt="<?= htmlspecialchars($employee['name']) ?>" 
                     class="w-8 h-8 rounded-full object-cover">
                <div class="hidden md:block">
                    <p class="text-sm font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($employee['name']) ?></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($employee['position']) ?></p>
                </div>
            </div>
        </div>
    </div>
</header>
