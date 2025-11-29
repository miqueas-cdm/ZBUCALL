<?php
require_once 'config/session.php';
require_once 'components/breadcrumbs.php';
requireAuth();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comunicados - Portal do Colaborador</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    
    <?php include 'components/sidebar.php'; ?>
    
    <div class="lg:ml-64">
        <?php include 'components/header.php'; ?>
        
        <main class="p-6">
            <?php renderBreadcrumbs([['label' => 'Comunicados']]); ?>
            
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Comunicados Internos</h1>
                <p class="text-gray-600 dark:text-gray-400">Acompanhe as novidades e avisos da empresa</p>
            </div>
            
            <!-- Filter Tabs -->
            <div class="mb-6">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex gap-6">
                        <button onclick="filterCategory('all')" class="category-tab active border-b-2 border-blue-600 dark:border-blue-400 text-blue-600 dark:text-blue-400 py-3 px-1 font-medium text-sm">
                            Todos
                        </button>
                        <button onclick="filterCategory('announcement')" class="category-tab border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 py-3 px-1 font-medium text-sm">
                            Avisos
                        </button>
                        <button onclick="filterCategory('news')" class="category-tab border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 py-3 px-1 font-medium text-sm">
                            Notícias
                        </button>
                        <button onclick="filterCategory('campaign')" class="category-tab border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 py-3 px-1 font-medium text-sm">
                            Campanhas
                        </button>
                    </nav>
                </div>
            </div>
            
            <div id="communications-container" class="space-y-4">
                <div class="skeleton h-32"></div>
                <div class="skeleton h-32"></div>
                <div class="skeleton h-32"></div>
            </div>
        </main>
    </div>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="assets/js/main.js"></script>
    <script>
        let allCommunications = [];
        let currentCategory = 'all';
        
        async function loadCommunications() {
            try {
                const response = await fetch('api/communications.php?action=list');
                const data = await response.json();
                
                if (data.success) {
                    allCommunications = data.communications || [];
                    renderCommunications();
                }
            } catch (error) {
                console.error('Error loading communications:', error);
            }
        }
        
        function renderCommunications() {
            const filtered = currentCategory === 'all' 
                ? allCommunications 
                : allCommunications.filter(comm => comm.category === currentCategory);
            
            const container = document.getElementById('communications-container');
            
            if (filtered.length === 0) {
                container.innerHTML = '<p class="text-center text-gray-500 py-8">Nenhum comunicado encontrado</p>';
                return;
            }
            
            const categoryIcons = {
                announcement: 'megaphone',
                news: 'newspaper',
                campaign: 'target',
                notice: 'alert-circle',
                classified: 'tag'
            };
            
            const priorityColors = {
                urgent: 'bg-red-100 dark:bg-red-900/30 border-red-500 dark:border-red-600',
                high: 'bg-orange-100 dark:bg-orange-900/30 border-orange-500 dark:border-orange-600',
                normal: 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700',
                low: 'bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700'
            };
            
            container.innerHTML = filtered.map((comm, index) => `
                <div class="border-l-4 ${priorityColors[comm.priority] || priorityColors.normal} rounded-lg shadow p-6 fade-in" style="animation-delay: ${index * 0.1}s">
                    <div class="flex items-start gap-4">
                        <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-lg flex-shrink-0">
                            <i data-lucide="${categoryIcons[comm.category] || 'bell'}" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">${comm.title}</h3>
                                <div class="flex gap-2 ml-4">
                                    ${comm.priority === 'urgent' || comm.priority === 'high' ? `
                                        <span class="badge badge-${comm.priority === 'urgent' ? 'error' : 'warning'} text-xs">
                                            ${comm.priority === 'urgent' ? 'Urgente' : 'Importante'}
                                        </span>
                                    ` : ''}
                                </div>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300 mb-4 whitespace-pre-line">${comm.content}</p>
                            <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex items-center gap-4">
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="user" class="w-4 h-4"></i>
                                        ${comm.author_name || 'Administrador'}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="calendar" class="w-4 h-4"></i>
                                        ${formatDate(comm.published_at || comm.created_at)}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                        ${comm.views} visualizações
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
            
            lucide.createIcons();
        }
        
        function filterCategory(category) {
            currentCategory = category;
            // Update active tab
            document.querySelectorAll('.category-tab').forEach(tab => {
                tab.classList.remove('active', 'border-blue-600', 'dark:border-blue-400', 'text-blue-600', 'dark:text-blue-400');
                tab.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
            });
            event.target.classList.add('active', 'border-blue-600', 'dark:border-blue-400', 'text-blue-600', 'dark:text-blue-400');
            event.target.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
            
            renderCommunications();
        }
        
        document.addEventListener('DOMContentLoaded', loadCommunications);
    </script>
</body>
</html>
