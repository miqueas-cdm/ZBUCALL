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
<aside id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-primary border-r border-primary-700 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 z-50 no-print">
    <div class="h-full flex flex-col">
        <div class="p-4 border-b border-primary-700 flex justify-center">
            <div class="bg-white rounded-full p-4">
                <img src="assets/asfa.png" alt="Portal do Associado" class="w-40 h-auto object-contain">
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto p-4 space-y-1">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-lg <?= $currentPage === 'dashboard.php' ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' ?> transition-colors">
                <i data-lucide="home" class="w-5 h-5"></i>
                <span class="font-medium">Dashboard</span>
            </a>

            <a href="transparency.php" class="flex items-center gap-3 px-4 py-3 rounded-lg <?= $currentPage === 'transparency.php' ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' ?> transition-colors">
                <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
                <span class="font-medium">Transparência</span>
            </a>

            <a href="communications.php" class="flex items-center gap-3 px-4 py-3 rounded-lg <?= $currentPage === 'communications.php' ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' ?> transition-colors">
                <i data-lucide="bell" class="w-5 h-5"></i>
                <span class="font-medium">Comunicados</span>
            </a>
            
            <a href="profile.php" class="flex items-center gap-3 px-4 py-3 rounded-lg <?= $currentPage === 'profile.php' ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' ?> transition-colors">
                <i data-lucide="user" class="w-5 h-5"></i>
                <span class="font-medium">Meu Perfil</span>
            </a>
            
            <a href="benefits.php" class="flex items-center gap-3 px-4 py-3 rounded-lg <?= $currentPage === 'benefits.php' ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' ?> transition-colors">
                <i data-lucide="gift" class="w-5 h-5"></i>
                <span class="font-medium">Clube de Vantagens</span>
            </a>

            <a href="documents.php" class="flex items-center gap-3 px-4 py-3 rounded-lg <?= $currentPage === 'documents.php' ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' ?> transition-colors">
                <i data-lucide="folder" class="w-5 h-5"></i>
                <span class="font-medium">Documentos</span>
            </a>
            
            <a href="requests.php" class="flex items-center gap-3 px-4 py-3 rounded-lg <?= $currentPage === 'requests.php' ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' ?> transition-colors">
                <i data-lucide="send" class="w-5 h-5"></i>
                <span class="font-medium">Solicitações</span>
            </a>
        </nav>
        
        <!-- Logout -->
        <div class="p-4 border-t border-primary-700">
            <button onclick="handleLogout()" class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-400 hover:bg-red-500/20 hover:text-white transition-colors w-full">
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
