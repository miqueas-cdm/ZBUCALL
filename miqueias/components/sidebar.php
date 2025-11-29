<?php
/**
 * Sidebar Component
 * Main navigation menu
 */

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!-- Mobile Overlay -->
<div id="sidebar-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"></div>

<!-- Sidebar -->
<aside id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 z-50 no-print">
    <div class="h-full flex flex-col">
        <!-- Logo -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-bold text-primary dark:text-blue-400">Portal do Colaborador</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Bem-vindo ao sistema</p>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto p-4 space-y-1">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-lg <?= $currentPage === 'dashboard.php' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' ?> transition-colors">
                <i data-lucide="home" class="w-5 h-5"></i>
                <span class="font-medium">Dashboard</span>
            </a>

            <a href="transparency.php" class="flex items-center gap-3 px-4 py-3 rounded-lg <?= $currentPage === 'transparency.php' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' ?> transition-colors">
                <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
                <span class="font-medium">Transparência</span>
            </a>

            <a href="communications.php" class="flex items-center gap-3 px-4 py-3 rounded-lg <?= $currentPage === 'communications.php' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' ?> transition-colors">
                <i data-lucide="bell" class="w-5 h-5"></i>
                <span class="font-medium">Comunicados</span>
            </a>
            
            <a href="profile.php" class="flex items-center gap-3 px-4 py-3 rounded-lg <?= $currentPage === 'profile.php' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' ?> transition-colors">
                <i data-lucide="user" class="w-5 h-5"></i>
                <span class="font-medium">Meu Perfil</span>
            </a>
            
            <a href="benefits.php" class="flex items-center gap-3 px-4 py-3 rounded-lg <?= $currentPage === 'benefits.php' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' ?> transition-colors">
                <i data-lucide="gift" class="w-5 h-5"></i>
                <span class="font-medium">Benefícios</span>
            </a>
            
            <a href="requests.php" class="flex items-center gap-3 px-4 py-3 rounded-lg <?= $currentPage === 'requests.php' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' ?> transition-colors">
                <i data-lucide="send" class="w-5 h-5"></i>
                <span class="font-medium">Solicitações</span>
            </a>
        </nav>
        
        <!-- Logout -->
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            <button onclick="handleLogout()" class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors w-full">
                <i data-lucide="log-out" class="w-5 h-5"></i>
                <span class="font-medium">Sair</span>
            </button>
        </div>
    </div>
</aside>

<script>
async function handleLogout() {
    if (!confirm('Tem certeza que deseja sair?')) return;
    
    try {
        const formData = new FormData();
        formData.append('action', 'logout');
        
        const response = await fetch('api/auth.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        if (data.success) {
            window.location.href = 'index.php';
        }
    } catch (error) {
        console.error('Logout error:', error);
    }
}
</script>
