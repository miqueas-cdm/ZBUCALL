<?php
require_once 'config/session.php';
require_once 'components/breadcrumbs.php';
requireAuth();

$employee = getCurrentEmployee();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php $pageTitle = 'Dashboard - Portal do Associado'; ?>
    <?php include 'components/head.php'; ?>
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    
    <?php include 'components/sidebar.php'; ?>
    
    <div class="lg:ml-64">
        <?php include 'components/header.php'; ?>
        
        <main class="p-6">
            <?php renderBreadcrumbs([['label' => 'Dashboard']]); ?>
            
            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-primary to-white rounded-xl p-8 mb-6 shadow-lg fade-in relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2 text-white">Olá, <?= htmlspecialchars(explode(' ', $employee['name'])[0]) ?>! 👋</h1>
                        <p class="text-white/90">Seja bem-vindo ao Portal do Associado</p>
                    </div>
                    <div class="hidden md:block absolute -right-10 top-1/2 -translate-y-1/2">
                        <img src="assets/logo.png" alt="Logo" class="h-56 w-auto max-w-none">
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
                        <div class="p-3">
                            <i data-lucide="bell" class="w-6 h-6" style="color: #c21a21;"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow hover:shadow-lg transition-shadow fade-in" style="animation-delay: 0.2s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Solicitações Pendentes</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2" id="stat-requests">-</p>
                        </div>
                        <div class="p-3">
                            <i data-lucide="send" class="w-6 h-6" style="color: #c21a21;"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow hover:shadow-lg transition-shadow fade-in" style="animation-delay: 0.3s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Clube de Vantagens</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2" id="stat-benefits">-</p>
                        </div>
                        <div class="p-3">
                            <i data-lucide="gift" class="w-6 h-6" style="color: #c21a21;"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow hover:shadow-lg transition-shadow fade-in" style="animation-delay: 0.4s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Holerite Disponível</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2" id="stat-payslip">-</p>
                        </div>
                        <div class="p-3">
                            <i data-lucide="file-text" class="w-6 h-6" style="color: #c21a21;"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Access Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Atalhos Rápidos -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 fade-in" style="animation-delay: 0.5s">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="zap" class="w-5 h-5" style="color: #c21a21;"></i>
                        Atalhos Rápidos
                    </h2>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="benefits.php" class="flex flex-col items-center p-4 rounded-lg border-2 border-gray-200 dark:border-gray-700 hover:border-[#c21a21] dark:hover:border-[#c21a21] hover:bg-gray-50 dark:hover:bg-gray-700 transition-all card-hover group">
                            <i data-lucide="gift" class="w-8 h-8 mb-2 transition-colors" style="color: #c21a21;"></i>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Clube de Vantagens</span>
                        </a>
                        
                        <a href="transparency.php" class="flex flex-col items-center p-4 rounded-lg border-2 border-gray-200 dark:border-gray-700 hover:border-[#c21a21] dark:hover:border-[#c21a21] hover:bg-gray-50 dark:hover:bg-gray-700 transition-all card-hover group">
                            <i data-lucide="bar-chart-2" class="w-8 h-8 mb-2 transition-colors" style="color: #c21a21;"></i>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Transparência</span>
                        </a>
                        
                        <a href="communications.php" class="flex flex-col items-center p-4 rounded-lg border-2 border-gray-200 dark:border-gray-700 hover:border-[#c21a21] dark:hover:border-[#c21a21] hover:bg-gray-50 dark:hover:bg-gray-700 transition-all card-hover group">
                            <i data-lucide="bell" class="w-8 h-8 mb-2 transition-colors" style="color: #c21a21;"></i>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Comunicados</span>
                        </a>
                        
                        <a href="documents.php" class="flex flex-col items-center p-4 rounded-lg border-2 border-gray-200 dark:border-gray-700 hover:border-[#c21a21] dark:hover:border-[#c21a21] hover:bg-gray-50 dark:hover:bg-gray-700 transition-all card-hover group">
                            <i data-lucide="folder" class="w-8 h-8 mb-2 transition-colors" style="color: #c21a21;"></i>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Documentos</span>
                        </a>
                    </div>
                </div>
                
                <!-- Nível de Atividade (Gamification) -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 fade-in" style="animation-delay: 0.6s">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="trophy" class="w-5 h-5" style="color: #c21a21;"></i>
                        Nível de Atividade
                    </h2>
                    
                    <div class="flex items-center gap-4 mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full border-2 border-[#c21a21] flex-shrink-0">
                            <i data-lucide="star" class="w-8 h-8" style="color: #c21a21;"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-900 dark:text-white">Super Associado</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Nível 5 • 128 Pontos</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600 dark:text-gray-400">Progresso para Nível 6</span>
                            <span class="font-medium" style="color: #c21a21;">75%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                            <div class="h-2.5 rounded-full" style="width: 75%; background-color: #c21a21;"></div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Complete mais 2 solicitações para subir de nível!</p>
                    </div>
                </div>
            </div>
            
            <!-- Communications -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 fade-in" style="animation-delay: 0.7s">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <i data-lucide="bell" class="w-5 h-5" style="color: #c21a21;"></i>
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
                    const commsHTML = comms.length > 0 ? comms.map(comm => {
                        let surveyHTML = '';
                        if (comm.survey_question && comm.survey_options) {
                            const options = JSON.parse(comm.survey_options);
                            const hasVoted = comm.user_vote !== null;
                            
                            surveyHTML = `
                                <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200 mb-2">${comm.survey_question}</p>
                                    <div class="space-y-2">
                                        ${options.map(opt => `
                                            <button onclick="submitVote(${comm.id}, '${opt}')" 
                                                    class="w-full text-left px-3 py-2 rounded text-sm transition-colors ${hasVoted ? (comm.user_vote === opt ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-gray-50 text-gray-400 cursor-not-allowed') : 'bg-gray-50 hover:bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600'}"
                                                    ${hasVoted ? 'disabled' : ''}>
                                                ${opt} ${hasVoted && comm.user_vote === opt ? '✓' : ''}
                                            </button>
                                        `).join('')}
                                    </div>
                                    ${hasVoted ? '<p class="text-xs text-green-600 mt-2">Obrigado por votar!</p>' : ''}
                                </div>
                            `;
                        }

                        return `
                        <div class="block p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <div class="flex items-start gap-3">
                                <div class="p-2">
                                    <i data-lucide="info" class="w-5 h-5" style="color: #c21a21;"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <a href="communications.php?id=${comm.id}" class="block">
                                        <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-1">${comm.title}</h3>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2">${comm.content}</p>
                                    </a>
                                    ${surveyHTML}
                                </div>
                            </div>
                        </div>
                    `}).join('') : '<p class="text-center text-gray-500 py-4">Nenhum comunicado importante no momento</p>';
                    
                    document.getElementById('communications-container').innerHTML = commsHTML;
                    
                    lucide.createIcons();
                }
            } catch (error) {
                console.error('Error loading dashboard data:', error);
            }
        }

        async function submitVote(commId, option) {
            const formData = new FormData();
            formData.append('action', 'vote');
            formData.append('communication_id', commId);
            formData.append('option', option);

            try {
                const response = await fetch('api/survey_vote.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                
                if (data.success) {
                    loadDashboardData(); // Reload to update UI
                } else {
                    alert(data.message);
                }
            } catch (error) {
                console.error('Error voting:', error);
            }
        }
        
        document.addEventListener('DOMContentLoaded', loadDashboardData);
    </script>
</body>
</html>
