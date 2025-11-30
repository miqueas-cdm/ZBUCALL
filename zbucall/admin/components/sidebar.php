<?php
/**
 * Admin Sidebar Component
 */

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!-- Mobile Overlay -->
<div id="sidebar-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"></div>

<!-- Sidebar -->
<aside id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-gray-900 border-r border-gray-800 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 z-50 no-print">
    <div class="h-full flex flex-col">
        <div class="p-4 border-b border-gray-800 flex justify-center">
            <div class="bg-white rounded-full p-2">
                <img src="../assets/logo.png" alt="Admin Portal" class="w-32 h-auto object-contain">
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto p-4 space-y-1">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 px-4">Administração</div>
            
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-lg <?= $currentPage === 'dashboard.php' ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' ?> transition-colors">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span class="font-medium">Dashboard</span>
            </a>

            <a href="communications.php" class="flex items-center gap-3 px-4 py-3 rounded-lg <?= $currentPage === 'communications.php' ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' ?> transition-colors">
                <i data-lucide="megaphone" class="w-5 h-5"></i>
                <span class="font-medium">Comunicados</span>
            </a>
            
            <a href="requests.php" class="flex items-center gap-3 px-4 py-3 rounded-lg <?= $currentPage === 'requests.php' ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' ?> transition-colors">
                <i data-lucide="file-check" class="w-5 h-5"></i>
                <span class="font-medium">Solicitações</span>
            </a>
            
            <a href="transparency.php" class="flex items-center gap-3 px-4 py-3 rounded-lg <?= $currentPage === 'transparency.php' ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' ?> transition-colors">
                <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
                <span class="font-medium">Transparência</span>
            </a>
            
            <a href="monitor.php" class="flex items-center gap-3 px-4 py-3 rounded-lg <?= $currentPage === 'monitor.php' ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' ?> transition-colors">
                <i data-lucide="activity" class="w-5 h-5"></i>
                <span class="font-medium">Monitoramento</span>
            </a>
            
            <div class="mt-8 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 px-4">Acesso Rápido</div>
            
            <a href="../dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white transition-colors">
                <i data-lucide="external-link" class="w-5 h-5"></i>
                <span class="font-medium">Portal do Associado</span>
            </a>
        </nav>
        
        <!-- Logout -->
        <div class="p-4 border-t border-gray-800">
            <button onclick="handleLogout()" class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-400 hover:bg-red-900/20 hover:text-red-300 transition-colors w-full">
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
        
        const response = await fetch('../api/auth.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        if (data.success) {
            window.location.href = '../index.php';
        }
    } catch (error) {
        console.error('Logout error:', error);
    }
}
</script>
