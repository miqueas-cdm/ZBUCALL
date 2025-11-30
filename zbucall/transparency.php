<?php
require_once 'config/session.php';
require_once 'components/breadcrumbs.php';
requireAuth();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php $pageTitle = 'Transparência - Portal do Associado'; ?>
    <?php include 'components/head.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-white dark:bg-gray-900">
    
    <?php include 'components/sidebar.php'; ?>
    
    <div class="lg:ml-64">
        <?php include 'components/header.php'; ?>
        
        <main class="p-6">
            <?php renderBreadcrumbs([['label' => 'Transparência']]); ?>
            
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Portal da Transparência</h1>
                <p class="text-gray-600 dark:text-gray-400">Acompanhe as contas e documentos da associação (ASFA)</p>
            </div>
            
            <!-- Financial Summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-card dark:bg-gray-800 rounded-lg shadow p-6 fade-in" style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Receitas (Mês Atual)</h3>
                        <div class="bg-green-100 dark:bg-green-900/30 p-2 rounded-lg">
                            <i data-lucide="arrow-up-circle" class="w-5 h-5 text-green-600 dark:text-green-400"></i>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">R$ 45.280,00</p>
                    <p class="text-xs text-green-600 dark:text-green-400 mt-1 flex items-center gap-1">
                        <i data-lucide="trending-up" class="w-3 h-3"></i>
                        +12% vs mês anterior
                    </p>
                </div>
                
                <div class="bg-card dark:bg-gray-800 rounded-lg shadow p-6 fade-in" style="animation-delay: 0.2s">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Despesas (Mês Atual)</h3>
                        <div class="bg-red-100 dark:bg-red-900/30 p-2 rounded-lg">
                            <i data-lucide="arrow-down-circle" class="w-5 h-5 text-red-600 dark:text-red-400"></i>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">R$ 32.150,00</p>
                    <p class="text-xs text-red-600 dark:text-red-400 mt-1 flex items-center gap-1">
                        <i data-lucide="trending-down" class="w-3 h-3"></i>
                        -5% vs mês anterior
                    </p>
                </div>
                
                <div class="bg-card dark:bg-gray-800 rounded-lg shadow p-6 fade-in" style="animation-delay: 0.3s">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Saldo em Caixa</h3>
                        <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-lg">
                            <i data-lucide="wallet" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">R$ 128.450,00</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Atualizado hoje</p>
                </div>
            </div>
            
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Charts -->
                <div class="lg:col-span-2 bg-card dark:bg-gray-800 rounded-lg shadow p-6 fade-in" style="animation-delay: 0.4s">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Despesas por Categoria</h3>
                    <div style="height: 300px; position: relative;">
                        <canvas id="expensesChart"></canvas>
                    </div>
                </div>
                
                <!-- Recent Documents -->
                <div class="bg-card dark:bg-gray-800 rounded-lg shadow p-6 fade-in" style="animation-delay: 0.5s">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Documentos Recentes</h3>
                        <a href="documents.php" class="text-sm text-blue-600 hover:text-blue-700">Ver todos</a>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-center gap-4 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors border border-gray-100 dark:border-gray-700">
                            <div class="bg-red-100 dark:bg-red-900/30 p-3 rounded-lg">
                                <i data-lucide="file-text" class="w-6 h-6 text-red-600 dark:text-red-400"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900 dark:text-white">Balancete Outubro/2025</h4>
                                <p class="text-xs text-gray-500">PDF • 2.4 MB • 15/11/2025</p>
                            </div>
                            <button class="p-2 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-full">
                                <i data-lucide="download" class="w-5 h-5 text-gray-500"></i>
                            </button>
                        </div>
                        
                        <div class="flex items-center gap-4 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors border border-gray-100 dark:border-gray-700">
                            <div class="bg-red-100 dark:bg-red-900/30 p-3 rounded-lg">
                                <i data-lucide="file-text" class="w-6 h-6 text-red-600 dark:text-red-400"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900 dark:text-white">Ata da Assembleia Geral</h4>
                                <p class="text-xs text-gray-500">PDF • 1.8 MB • 10/11/2025</p>
                            </div>
                            <button class="p-2 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-full">
                                <i data-lucide="download" class="w-5 h-5 text-gray-500"></i>
                            </button>
                        </div>
                        
                        <div class="flex items-center gap-4 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors border border-gray-100 dark:border-gray-700">
                            <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-lg">
                                <i data-lucide="file-spreadsheet" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900 dark:text-white">Prestação de Contas Trimestral</h4>
                                <p class="text-xs text-gray-500">XLSX • 450 KB • 01/11/2025</p>
                            </div>
                            <button class="p-2 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-full">
                                <i data-lucide="download" class="w-5 h-5 text-gray-500"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Detailed Expenses Table -->
            <div class="bg-card dark:bg-gray-800 rounded-lg shadow overflow-hidden fade-in" style="animation-delay: 0.6s">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Últimos Lançamentos</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-4 font-medium">Data</th>
                                <th class="px-6 py-4 font-medium">Descrição</th>
                                <th class="px-6 py-4 font-medium">Categoria</th>
                                <th class="px-6 py-4 font-medium text-right">Valor</th>
                                <th class="px-6 py-4 font-medium text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-6 py-4 text-gray-900 dark:text-white">28/11/2025</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">Empréstimo Consignado - Parc. 12/24</td>
                                <td class="px-6 py-4 text-gray-500">Empréstimos</td>
                                <td class="px-6 py-4 text-gray-900 dark:text-white font-medium text-right text-green-600">+ R$ 1.250,00</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Recebido</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-6 py-4 text-gray-900 dark:text-white">27/11/2025</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">Repasse Convênio Farmácia</td>
                                <td class="px-6 py-4 text-gray-500">Convênios</td>
                                <td class="px-6 py-4 text-gray-900 dark:text-white font-medium text-right text-red-600">- R$ 15.400,00</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Pago</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-6 py-4 text-gray-900 dark:text-white">25/11/2025</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">Repasse Convênio Mercado</td>
                                <td class="px-6 py-4 text-gray-500">Convênios</td>
                                <td class="px-6 py-4 text-gray-900 dark:text-white font-medium text-right text-red-600">- R$ 450,00</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Pago</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-6 py-4 text-gray-900 dark:text-white">24/11/2025</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">Eventos e Confraternizações</td>
                                <td class="px-6 py-4 text-gray-500">Eventos</td>
                                <td class="px-6 py-4 text-gray-900 dark:text-white font-medium text-right text-red-600">- R$ 3.500,00</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Pago</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="assets/js/main.js"></script>
    <script>
        // Initialize Chart
        const ctx = document.getElementById('expensesChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Eventos', 'Empréstimos', 'Convênios', 'Outros'],
                datasets: [{
                    data: [30, 35, 25, 10],
                    backgroundColor: [
                        '#3b82f6', // Blue
                        '#10b981', // Green
                        '#f59e0b', // Yellow
                        '#6b7280'  // Gray
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 10
                        }
                    }
                },
                cutout: '70%'
            }
        });
    </script>
</body>
</html>
