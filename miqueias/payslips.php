<?php
require_once 'config/session.php';
require_once 'components/breadcrumbs.php';
requireAuth();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php $pageTitle = 'Holerites - Portal do Associado'; ?>
    <?php include 'components/head.php'; ?>
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    
    <?php include 'components/sidebar.php'; ?>
    
    <div class="lg:ml-64">
        <?php include 'components/header.php'; ?>
        
        <main class="p-6">
            <?php renderBreadcrumbs([['label' => 'Holerites']]); ?>
            
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Holerites</h1>
                <p class="text-gray-600 dark:text-gray-400">Consulte e baixe seus contracheques</p>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Holerites Disponíveis</h2>
                    <div id="payslips-container">
                        <div class="skeleton h-20 mb-3"></div>
                        <div class="skeleton h-20 mb-3"></div>
                        <div class="skeleton h-20"></div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/components.js"></script>
    <script>
        async function loadPayslips() {
            try {
                const response = await fetch('api/payslips.php?action=list');
                const data = await response.json();
                
                if (data.success) {
                    const payslips = data.payslips || [];
                    const container = document.getElementById('payslips-container');
                    
                    if (payslips.length === 0) {
                        container.innerHTML = '<p class="text-center text-gray-500 py-8">Nenhum holerite disponível</p>';
                        return;
                    }
                    
                    container.innerHTML = payslips.map((payslip, index) => `
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors fade-in" style="animation-delay: ${index * 0.05}s">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-lg">
                                        <i data-lucide="file-text" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            ${payslip.period}
                                        </h3>
                                        <div class="flex gap-4 mt-1 text-sm text-gray-600 dark:text-gray-400">
                                            <span><strong>Bruto:</strong> ${formatCurrency(payslip.gross_salary)}</span>
                                            <span><strong>Líquido:</strong> ${formatCurrency(payslip.net_salary)}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button onclick="viewPayslip(${payslip.id})" 
                                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                        Visualizar
                                    </button>
                                    <button onclick="downloadPayslip(${payslip.id})" 
                                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center gap-2">
                                        <i data-lucide="download" class="w-4 h-4"></i>
                                        Download
                                    </button>
                                </div>
                            </div>
                        </div>
                    `).join('');
                    
                    lucide.createIcons();
                }
            } catch (error) {
                console.error('Error loading payslips:', error);
            }
        }
        
        async function viewPayslip(id) {
            try {
                const response = await fetch(`api/payslips.php?action=get&id=${id}`);
                const data = await response.json();
                
                if (data.success) {
                    const p = data.payslip;
                    const content = `
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">Funcionário:</span>
                                    <p class="font-semibold text-gray-900 dark:text-white">${p.full_name}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">Matrícula:</span>
                                    <p class="font-semibold text-gray-900 dark:text-white">${p.registration_number}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">Cargo:</span>
                                    <p class="font-semibold text-gray-900 dark:text-white">${p.position}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">Departamento:</span>
                                    <p class="font-semibold text-gray-900 dark:text-white">${p.department}</p>
                                </div>
                            </div>
                            <hr class="border-gray-200 dark:border-gray-700">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                    <span class="text-xs text-green-600 dark:text-green-400">Salário Bruto</span>
                                    <p class="text-2xl font-bold text-green-700 dark:text-green-300">${formatCurrency(p.gross_salary)}</p>
                                </div>
                                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                    <span class="text-xs text-blue-600 dark:text-blue-400">Salário Líquido</span>
                                    <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">${formatCurrency(p.net_salary)}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div><span class="text-gray-600 dark:text-gray-400">Descontos:</span> <strong>${formatCurrency(p.deductions)}</strong></div>
                                <div><span class="text-gray-600 dark:text-gray-400">Benefícios:</span> <strong>${formatCurrency(p.benefits_total)}</strong></div>
                                <div><span class="text-gray-600 dark:text-gray-400">INSS:</span> <strong>${formatCurrency(p.inss)}</strong></div>
                                <div><span class="text-gray-600 dark:text-gray-400">IRRF:</span> <strong>${formatCurrency(p.irrf)}</strong></div>
                                <div><span class="text-gray-600 dark:text-gray-400">FGTS:</span> <strong>${formatCurrency(p.fgts)}</strong></div>
                                <div><span class="text-gray-600 dark:text-gray-400">Horas Trabalhadas:</span> <strong>${p.worked_hours}h</strong></div>
                            </div>
                        </div>
                    `;
                    
                    const modal = new Modal('payslip-modal', `Holerite - ${p.period}`, content, { size: 'lg' });
                    modal.render().show();
                }
            } catch (error) {
                console.error('Error viewing payslip:', error);
                Toast.show('Erro ao carregar holerite', 'error');
            }
        }
        
        function downloadPayslip(id) {
            Toast.show('Gerando PDF...', 'info');
            // In a real implementation, this would generate/download the PDF
            setTimeout(() => Toast.show('Download concluído!', 'success'), 1500);
        }
        
        document.addEventListener('DOMContentLoaded', loadPayslips);
    </script>
</body>
</html>
