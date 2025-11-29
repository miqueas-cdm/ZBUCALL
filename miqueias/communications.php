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
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6 fade-in" style="animation-delay: ${index * 0.1}s">
                    <!-- Header -->
                    <div class="p-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-full">
                                <i data-lucide="${categoryIcons[comm.category] || 'bell'}" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white">${comm.title}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    ${comm.author_name || 'Administrador'} • ${formatDate(comm.published_at || comm.created_at)}
                                </p>
                            </div>
                        </div>
                        ${comm.priority === 'urgent' || comm.priority === 'high' ? `
                            <span class="badge badge-${comm.priority === 'urgent' ? 'error' : 'warning'} text-xs">
                                ${comm.priority === 'urgent' ? 'Urgente' : 'Importante'}
                            </span>
                        ` : ''}
                    </div>
                    
                    <!-- Content -->
                    <div class="p-4">
                        <p class="text-gray-800 dark:text-gray-200 whitespace-pre-line text-base leading-relaxed">${comm.content}</p>
                        
                        <!-- Image (Mocked for demo if not present) -->
                        <div class="mt-4 rounded-lg overflow-hidden">
                            <img src="${comm.image_url || `https://picsum.photos/seed/${comm.id}/800/400`}" alt="Imagem do comunicado" class="w-full h-auto object-cover max-h-96 hover:scale-105 transition-transform duration-500">
                        </div>
                    </div>
                    
                    <!-- Stats -->
                    <div class="px-4 py-2 flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-gray-700">
                        <span>${comm.views} visualizações</span>
                        <span>0 comentários</span>
                    </div>
                    
                    <!-- Actions -->
                    <div class="px-4 py-2 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between gap-2">
                        <button class="flex-1 flex items-center justify-center gap-2 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors text-gray-600 dark:text-gray-400">
                            <i data-lucide="thumbs-up" class="w-5 h-5"></i>
                            <span class="hidden sm:inline">Curtir</span>
                        </button>
                        <button class="flex-1 flex items-center justify-center gap-2 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors text-gray-600 dark:text-gray-400" onclick="toggleComments(${comm.id})">
                            <i data-lucide="message-square" class="w-5 h-5"></i>
                            <span class="hidden sm:inline">Comentar</span>
                        </button>
                        <button class="flex-1 flex items-center justify-center gap-2 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors text-gray-600 dark:text-gray-400">
                            <i data-lucide="share-2" class="w-5 h-5"></i>
                            <span class="hidden sm:inline">Compartilhar</span>
                        </button>
                        <button class="flex-1 flex items-center justify-center gap-2 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors text-gray-600 dark:text-gray-400">
                            <i data-lucide="bookmark" class="w-5 h-5"></i>
                            <span class="hidden sm:inline">Salvar</span>
                        </button>
                    </div>
                    
                    <!-- Comments Section (Hidden by default) -->
                    <div id="comments-${comm.id}" class="hidden px-4 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                        <div class="flex gap-3">
                            <img src="assets/images/default-avatar.png" class="w-8 h-8 rounded-full bg-gray-200">
                            <div class="flex-1">
                                <input type="text" placeholder="Escreva um comentário..." class="w-full px-4 py-2 rounded-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
            
            lucide.createIcons();
        }

        function toggleComments(id) {
            const commentsSection = document.getElementById(`comments-${id}`);
            commentsSection.classList.toggle('hidden');
            const input = commentsSection.querySelector('input');
            if (!commentsSection.classList.contains('hidden')) {
                input.focus();
            }
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
