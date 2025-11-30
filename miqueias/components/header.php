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

<header class="bg-primary shadow-sm border-b border-primary-700 sticky top-0 z-40 no-print">
    <div class="px-4 py-3 flex items-center justify-between gap-4">
        <!-- Left: Mobile Menu + Logo -->
        <div class="flex items-center gap-4">
            <button id="mobile-menu-button" class="lg:hidden text-white">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
            <h1 class="text-xl font-bold text-white hidden md:block">Portal do Associado</h1>
        </div>
        
        <!-- Center: Search (hidden on mobile) -->
        <div class="hidden md:block flex-1 max-w-xl relative">
            <div class="relative">
                <i data-lucide="search" class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-white/60"></i>
                <input type="text" 
                       id="global-search"
                       placeholder="Buscar..." 
                       class="w-full pl-10 pr-4 py-2 rounded-lg border border-white/20 bg-white/10 text-white placeholder-white/60 focus:ring-2 focus:ring-white/30 focus:bg-white/20">
            </div>
            <div id="search-results" class="hidden absolute top-full mt-2 w-full bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 max-h-96 overflow-y-auto"></div>
        </div>
        
        <!-- Right: Notifications + Theme + User -->
        <div class="flex items-center gap-3">
            <!-- Notifications -->
            <div class="relative">
                <button id="notification-bell" class="relative p-2 text-white hover:bg-white/10 rounded-lg">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span id="notification-badge" class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
                </button>
                
                <!-- Notifications Dropdown -->
                <div id="notifications-dropdown" class="hidden fixed inset-x-4 top-16 z-50 md:absolute md:inset-auto md:right-0 md:top-full md:mt-2 w-auto md:w-80 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 max-h-[80vh] md:max-h-96 overflow-hidden">
                    <div class="p-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Notificações</h3>
                        <a href="javascript:void(0)" onclick="NotificationManager.markAllAsRead()" class="text-xs text-blue-600 hover:text-blue-700">Marcar todas como lida</a>
                    </div>
                    <div id="notifications-list" class="overflow-y-auto max-h-80"></div>
                </div>
            </div>
            
            <!-- Theme Toggle -->
            <button id="theme-toggle" class="p-2 text-white hover:bg-white/10 rounded-lg">
                <i id="theme-icon" data-lucide="moon" class="w-5 h-5"></i>
            </button>
            
            <!-- User Menu -->
            <div class="flex items-center gap-2 pl-3 border-l border-white/20">
                <img src="<?= htmlspecialchars($employee['photo_url']) ?>" 
                     alt="<?= htmlspecialchars($employee['name']) ?>" 
                     class="w-8 h-8 rounded-full object-cover">
                <div class="hidden md:block">
                    <p class="text-sm font-medium text-white"><?= htmlspecialchars($employee['name']) ?></p>
                    <p class="text-xs text-white/70"><?= htmlspecialchars($employee['position']) ?></p>
                </div>
            </div>
        </div>
    </div>
</header>
