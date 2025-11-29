<?php
require_once 'config/session.php';
require_once 'components/breadcrumbs.php';
requireAuth();

$employee = getCurrentEmployee();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Portal do Colaborador</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    
    <?php include 'components/sidebar.php'; ?>
    
    <div class="lg:ml-64">
        <?php include 'components/header.php'; ?>
        
        <main class="p-6">
            <?php renderBreadcrumbs([['label' => 'Dashboard']]); ?>
            
            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl p-8 mb-6 text-white shadow-lg fade-in">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Olá, <?= htmlspecialchars(explode(' ', $employee['name'])[0]) ?>! 👋</h1>
                        <p class="text-blue-100">Seja bem-vindo ao Portal do Colaborador</p>
                    </div>
                    <div class="hidden md:block">
                        <svg class="w-24 h-24 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow hover:shadow-lg transition-shadow fade-in" style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Notificações</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2" id="stat-notifications">-</p>
                        </div>
                        <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-lg">
                            <i data-lucide="bell" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow hover:shadow-lg transition-shadow fade-in" style="animation-delay: 0.2s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Solicitações Pendentes</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2" id="stat-requests">-</p>
                        </div>
                        <div class="bg-yellow-100 dark:bg-yellow-900/30 p-3 rounded-lg">
                            <i data-lucide="send" class="w-6 h-6 text-yellow-600 dark:text-yellow-400"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow hover:shadow-lg transition-shadow fade-in" style="animation-delay: 0.3s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Benefícios Ativos</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2" id="stat-benefits">-</p>
                        </div>
                        <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-lg">
                            <i data-lucide="gift" class="w-6 h-6 text-green-600 dark:text-green-400"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow hover:shadow-lg transition-shadow fade-in" style="animation-delay: 0.4s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Holerite Disponível</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2" id="stat-payslip">-</p>
                        </div>
                        <div class="bg-purple-100 dark:bg-purple-900/30 p-3 rounded-lg">
                            <i data-lucide="file-text" class="w-6 h-6 text-purple-600 dark:text-purple-400"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Access Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Atalhos Rápidos -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 fade-in" style="animation-delay: 0.5s">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="zap" class="w-5 h-5 text-blue-600"></i>
                        Atalhos Rápidos
                    </h2>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="benefits.php" class="flex flex-col items-center p-4 rounded-lg border-2 border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all card-hover">
                            <i data-lucide="gift" class="w-8 h-8 text-blue-600 dark:text-blue-400 mb-2"></i>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Benefícios</span>
                        </a>
                        
                        <a href="transparency.php" class="flex flex-col items-center p-4 rounded-lg border-2 border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all card-hover">
                            <i data-lucide="bar-chart-2" class="w-8 h-8 text-green-600 dark:text-green-400 mb-2"></i>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Transparência</span>
                        </a>
                        
                        <a href="communications.php" class="flex flex-col items-center p-4 rounded-lg border-2 border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all card-hover">
                            <i data-lucide="bell" class="w-8 h-8 text-orange-600 dark:text-orange-400 mb-2"></i>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Comunicados</span>
                        </a>
                        
                        <a href="documents.php" class="flex flex-col items-center p-4 rounded-lg border-2 border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all card-hover">
                            <i data-lucide="folder" class="w-8 h-8 text-purple-600 dark:text-purple-400 mb-2"></i>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Documentos</span>
                        </a>
                    </div>
                </div>
                
                <!-- Nível de Atividade (Gamification) -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 fade-in" style="animation-delay: 0.6s">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="trophy" class="w-5 h-5 text-yellow-500"></i>
                        Nível de Atividade
                    </h2>
                    
                    <div class="flex items-center gap-4 mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex-shrink-0">
                            <i data-lucide="star" class="w-8 h-8 text-yellow-500"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-900 dark:text-white">Super Colaborador</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Nível 5 • 128 Pontos</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600 dark:text-gray-400">Progresso para Nível 6</span>
                            <span class="font-medium text-blue-600 dark:text-blue-400">75%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: 75%"></div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Complete mais 2 solicitações para subir de nível!</p>
                    </div>
                </div>
            </div>
            
            <!-- Communications -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 fade-in" style="animation-delay: 0.7s">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <i data-lucide="bell" class="w-5 h-5 text-blue-600"></i>
                        Comunicados Importantes
                    </span>
                    <a href="communications.php" class="text-sm text-blue-600 hover:text-blue-700">Ver todos</a>
                </h2>
                <div id="communications-container" class="space-y-3">
                    <div class="skeleton h-20"></div>
                    <div class="skeleton h-20"></div>
                </div>
            </div>
        </main>
    </div>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/components.js"></script>
    <script>
        // Load dashboard data
        async function loadDashboardData() {
            try {
                const response = await fetch('api/dashboard.php');
                const data = await response.json();
                
                if (data.success) {
                    // Update stats
                    document.getElementById('stat-notifications').textContent = data.data.stats.notifications;
                    document.getElementById('stat-requests').textContent = data.data.stats.pending_requests;
                    document.getElementById('stat-benefits').textContent = data.data.stats.active_benefits;
                    document.getElementById('stat-payslip').textContent = data.data.stats.payslip_available ? 'Sim' : 'Não';
                    
                    // Update communications
                    const comms = data.data.important_communications || [];
                    const commsHTML = comms.length > 0 ? comms.map(comm => `
                        <a href="communications.php?id=${comm.id}" class="block p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <div class="flex items-start gap-3">
                                <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-lg">
                                    <i data-lucide="info" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-1">${comm.title}</h3>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2">${comm.content}</p>
                                </div>
                            </div>
                        </a>
                    `).join('') : '<p class="text-center text-gray-500 py-4">Nenhum comunicado importante no momento</p>';
                    
                    document.getElementById('communications-container').innerHTML = commsHTML;
                    
                    lucide.createIcons();
                }
            } catch (error) {
                console.error('Error loading dashboard data:', error);
            }
        }
        
        document.addEventListener('DOMContentLoaded', loadDashboardData);
    </script>
</body>
</html>
