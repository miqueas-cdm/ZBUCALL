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
    <title>Ponto Eletrônico - Portal do Colaborador</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    
    <?php include 'components/sidebar.php'; ?>
    
    <div class="lg:ml-64">
        <?php include 'components/header.php'; ?>
        
        <main class="p-6">
            <?php renderBreadcrumbs([['label' => 'Ponto Eletrônico']]); ?>
            
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Registro de Ponto</h1>
                <p class="text-gray-600 dark:text-gray-400">Consulte seu espelho de ponto mensal</p>
            </div>
            
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow fade-in">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Dias Trabalhados</p>
                    <p id="summary-days" class="text-2xl font-bold text-gray-900 dark:text-white mt-1">-</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow fade-in" style="animation-delay: 0.1s">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total de Horas</p>
                    <p id="summary-hours" class="text-2xl font-bold text-gray-900 dark:text-white mt-1">-</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow fade-in" style="animation-delay: 0.2s">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Horas Extras</p>
                    <p id="summary-overtime" class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">-</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow fade-in" style="animation-delay: 0.3s">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Atrasos</p>
                    <p id="summary-late" class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1">-</p>
                </div>
            </div>
            
            <!-- Month Selector and Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow fade-in" style="animation-delay: 0.4s">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Registros</h2>
                        <select id="month-selector" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">Carregando...</option>
                        </select>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Data</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Entrada 1</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Saída 1</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Entrada 2</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Saída 2</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody id="records-tbody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">Carregando...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="assets/js/main.js"></script>
    <script>
        let currentMonth = new Date().getMonth() + 1;
        let currentYear = new Date().getFullYear();
        
        async function loadTimeRecords(month = currentMonth, year = currentYear) {
            try {
                const response = await fetch(`api/time-records.php?month=${month}&year=${year}`);
                const data = await response.json();
                
                if (data.success) {
                    const summary = data.data.summary;
                    document.getElementById('summary-days').textContent = summary.total_days;
                    document.getElementById('summary-hours').textContent = parseFloat(summary.total_hours).toFixed(1) + 'h';
                    document.getElementById('summary-overtime').textContent = parseFloat(summary.overtime_hours).toFixed(1) + 'h';
                    document.getElementById('summary-late').textContent = summary.late_days;
                    
                    // Load available periods
                    const periods = data.data.available_periods || [];
                    const selector = document.getElementById('month-selector');
                    selector.innerHTML = periods.map(p => 
                        `<option value="${p.month}-${p.year}" ${p.month == month && p.year == year ? 'selected' : ''}>${p.label}</option>`
                    ).join('');
                    
                    // Load records table
                    const records = data.data.records || [];
                    const tbody = document.getElementById('records-tbody');
                    
                    if (records.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">Nenhum registro encontrado</td></tr>';
                        return;
                    }
                    
                    tbody.innerHTML = records.map(record => {
                        const statusColors = {
                            normal: 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300',
                            late: 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300',
                            absent: 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300',
                            holiday: 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300'
                        };
                        
                        const statusLabels = {
                            normal: 'Normal',
                            late: 'Atraso',
                            absent: 'Falta',
                            holiday: 'Feriado',
                            leave: 'Licença'
                        };
                        
                        return `
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">${formatDate(record.record_date)}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">${record.clock_in_1 || '-'}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">${record.clock_out_1 || '-'}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">${record.clock_in_2 || '-'}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">${record.clock_out_2 || '-'}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">${parseFloat(record.total_hours).toFixed(1)}h</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${statusColors[record.status] || ''}">
                                        ${statusLabels[record.status] || record.status}
                                    </span>
                                </td>
                            </tr>
                        `;
                    }).join('');
                }
            } catch (error) {
                console.error('Error loading time records:', error);
            }
        }
        
        document.getElementById('month-selector').addEventListener('change', (e) => {
            const [month, year] = e.target.value.split('-');
            currentMonth = parseInt(month);
            currentYear = parseInt(year);
            loadTimeRecords(currentMonth, currentYear);
        });
        
        document.addEventListener('DOMContentLoaded', () => {
            loadTimeRecords();
            lucide.createIcons();
        });
    </script>
</body>
</html>
